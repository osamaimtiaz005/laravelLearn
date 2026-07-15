<?php

/*
|--------------------------------------------------------------------------
| WORD BY WORD — Session basics (read this first)
|--------------------------------------------------------------------------
|
| WHAT IS A SESSION?
|   HTTP is "stateless": every page request is separate. The server forgets
|   you between pages UNLESS it stores data in a SESSION.
|
|   Simple picture:
|     1. You login → Laravel saves email in session storage
|     2. Browser gets a cookie: laravel-session = random ID
|     3. Next request sends that cookie → Laravel loads YOUR bag of data
|     4. Profile page can read Session::get('email') even on a new URL
|
| WHERE IS DATA STORED?
|   In this project usually:  storage/framework/sessions/  (files)
|   Or in the `sessions` database table if SESSION_DRIVER=database
|   The browser only keeps a small ID cookie — NOT your email/password.
|
| SESSION vs COOKIE:
|   Cookie  = small data stored in the browser
|   Session = data stored on the SERVER, keyed by that cookie ID
|
| FACADE Session::
|   Illuminate\Support\Facades\Session
|   A short "static-looking" way to call session helpers:
|       Session::put('key', $value)   // save
|       Session::get('key')           // read
|       Session::has('key')           // exists?
|       Session::forget('key')        // delete one key
|       Session::flush()              // delete ALL session data (logout)
|       Session::all()                // dump every key => value
|
| YOU CAN ALSO USE helper or Request:
|       session('email')              // same as Session::get('email')
|       session(['cart' => []])       // same as Session::put(...)
|       $request->session()->put(...)
|
| FLASH DATA (temporary one-request messages):
|       Session::flash('success', 'Saved!')
|       return redirect(...)->with('success', 'Saved!')  // same idea
|   Lives for the NEXT request only, then Laravel auto-forgets it.
|
|   now('key', value)     → available on THIS same request only (not next)
|   keep(['key'])         → keep listed flash keys for ONE more request
|   reflash()             → keep ALL current flash keys for ONE more request
|
| AUTO EXPIRE (two levels):
|   1) Whole session idle timeout → config/session.php  'lifetime' (minutes)
|      Default often 120. After idle that long, Laravel drops the session.
|   2) One key with your own deadline → put expires_at, check on each page,
|      then Session::forget('key') when time is up (OTP / coupon demos).
|
| API / MICROSERVICES:
|   Mobile apps & APIs usually do NOT use cookie sessions.
|   They use tokens (Sanctum personal access token, Passport, JWT).
|   Client sends:  Authorization: Bearer <token>
|   Each microservice validates the token (or asks auth service).
|
| WHY STORE SESSION IN DATABASE (SESSION_DRIVER=database)?
|   - Multiple app servers share ONE session table (load balancer OK)
|   - Survive server restart (file sessions on one machine get lost)
|   - Can list / force-logout user sessions (see sessions table)
|   - Easier in Docker / cloud / microservices fronted by many nodes
|   File driver is fine for single-server learning apps.
|
|   Laravel sessions store user data (such as login state, user ID, cart, and flash messages) on the backend, 
|   while the browser stores only a session ID in a cookie (e.g., laravel_session). 
|   By default, Laravel uses the file session driver (SESSION_DRIVER=file), which stores sessions on the local server.
|   This works well for a single server. However, in applications running behind a load balancer or in a microservices/distributed environment,
|   sessions should be stored in a shared storage like Redis (preferred for speed) or a database
|   so every server can access the same session regardless of which server handles the request. 
|   For REST APIs, React/Vue SPAs, and mobile apps, JWTs (JSON Web Tokens) 
|   or other token-based authentication are commonly used instead of server sessions
|   because they are stateless. The client stores the JWT (or a secure cookie,
|   depending on the authentication strategy) and sends it with every request, 
|   allowing any server to verify the user without looking up session data.
| SECURITY NOTE (important):
|   NEVER store real passwords in session (or plain anywhere).
|   Real apps store a user id + use Auth / hashed passwords in DB.
|   This demo stores email + logged_in flag only.
|
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Hub page — all session demos in one place
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // Visit counter: every time you open this page, add +1
        // Session::get('visits', 0) → if key missing, use default 0
        $visits = Session::get('visits', 0) + 1;
        Session::put('visits', $visits);

        // Auto-forget OTP demo key if its time is over
        $otpStatus = $this->checkOtpExpiry();

        return view('sessions.index', [
            'visits' => $visits,
            'email' => Session::get('email'),
            'loggedIn' => Session::get('logged_in', false),
            'theme' => Session::get('theme', 'light'),
            'cart' => Session::get('cart', []),
            'lastProduct' => Session::get('last_product'),
            'otp' => Session::get('otp_code'),
            'otpExpiresAt' => Session::get('otp_expires_at'),
            'otpStatus' => $otpStatus,
            'sessionLifetimeMinutes' => config('session.lifetime'),
            'sessionDriver' => config('session.driver'),
            'allSession' => Session::all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Theory page — flash / expiry / API tokens / why DB sessions
    |--------------------------------------------------------------------------
    */
    public function theory()
    {
        return view('sessions.theory', [
            'sessionLifetimeMinutes' => config('session.lifetime'),
            'sessionDriver' => config('session.driver'),
            'expireOnClose' => config('session.expire_on_close'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | YOUR LOGIN FLOW (from your original code)
    |--------------------------------------------------------------------------
    |
    | login()  → show the form          (GET  /sessions/login)
    | store()  → save session + redirect (POST /sessions/store)
    | profile()→ read session data       (GET  /sessions/profile)
    | logout() → Session::flush()        (GET  /sessions/logout)
    |
    |--------------------------------------------------------------------------
    */

    // Show login form (no session write yet)
    public function login()
    {
        return view('sessions.login');
    }

    /*
    | store(Request $request)
    |--------------------------------------------------------------------------
    | YOUR original idea:
    |   validate email + password
    |   Session::put(...)
    |   redirect to profile with flash success
    |
    | Changes for real-world safety:
    |   - still validate password to practice rules
    |   - do NOT Session::put('password', ...)  ← dangerous
    |   - put email + logged_in flag instead
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // validate = check form fields before trusting them
        $request->validate([
            'email' => 'required|email',
            // your rule: letters/numbers only, length 8–20
            'password' => 'required|min:8|max:20|regex:/^[a-zA-Z0-9]+$/',
            'name' => 'nullable|string|max:80',
        ]);

        /*
        | Session::put(key, value)
        |   Save one piece of data into the current user's session bag.
        |   key   = string name you choose ('email', 'logged_in', ...)
        |   value = any PHP data (string, bool, array, …)
        |
        | After put(), NEXT pages can read it with Session::get('email')
        | until the session ends (logout, expire, or browser clears cookie).
        */
        Session::put('email', $request->email);
        Session::put('name', $request->input('name', 'Guest'));
        Session::put('logged_in', true);
        Session::put('login_at', now()->format('Y-m-d H:i:s'));

        // Optional: remember how many times this browser logged in
        Session::put('login_count', Session::get('login_count', 0) + 1);

        /*
        | redirect(...)->with('success', '...')
        |   redirect()  = send browser to another URL
        |   with()      = FLASH message (lives for the next request only)
        |   On profile Blade: Session::get('success') or session('success')
        |
        | route('sessions.profile') = named route nickname from web.php
        */
        return redirect()
            ->route('sessions.profile')
            ->with('success', 'Login successful');
    }

    /*
    | profile()
    |--------------------------------------------------------------------------
    | Real world: "My Account" page — only for logged-in users.
    | If session has no logged_in, kick user back to login (guard).
    |--------------------------------------------------------------------------
    */
    public function profile()
    {
        // Guard: must be logged in (your session flag)
        if (! Session::get('logged_in')) {
            return redirect()
                ->route('sessions.login')
                ->with('error', 'Please login first to see your profile');
        }

        /*
        | Session::all()
        |   Returns every key currently stored for this browser session.
        |   Useful while learning. In real apps, do not dump secrets on screen.
        */
        return view('sessions.profile', [
            'email' => Session::get('email'),
            'name' => Session::get('name'),
            'loginAt' => Session::get('login_at'),
            'loginCount' => Session::get('login_count', 1),
            'theme' => Session::get('theme', 'light'),
            'allSession' => Session::all(),
        ]);
    }

    /*
    | logout()
    |--------------------------------------------------------------------------
    | YOUR original: Session::flush() then redirect to login.
    |
    | flush() = wipe the WHOLE session bag (email, cart, theme, flags…).
    | Real shops sometimes keep cart and only forget auth keys:
    |   Session::forget(['email', 'logged_in', 'name']);
    |--------------------------------------------------------------------------
    */
    public function logout()
    {
        Session::flush();

        return redirect()
            ->route('sessions.login')
            ->with('success', 'Logout successful — session cleared');
    }

    /*
    |--------------------------------------------------------------------------
    | REAL WORLD 1 — Shopping cart in session
    |--------------------------------------------------------------------------
    | Amazon/Daraz keep cart items before checkout even if you are a guest.
    | Data shape: array of items stored under key "cart".
    |--------------------------------------------------------------------------
    */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product' => 'required|string|max:100',
            'price' => 'required|numeric|min:1',
            'qty' => 'required|integer|min:1|max:20',
        ]);

        // Read current cart (or empty array if first time)
        $cart = Session::get('cart', []);

        $cart[] = [
            'product' => $request->product,
            'price' => (float) $request->price,
            'qty' => (int) $request->qty,
            'added_at' => now()->format('H:i:s'),
        ];

        // Save updated cart back into session
        Session::put('cart', $cart);

        // Also remember last viewed/added product (recommendation style)
        Session::put('last_product', $request->product);

        return redirect()
            ->route('sessions.index')
            ->with('success', $request->product . ' added to cart');
    }

    public function clearCart()
    {
        // forget = remove ONE key only (cart), keep login/theme/etc.
        Session::forget('cart');

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Cart cleared');
    }

    /*
    |--------------------------------------------------------------------------
    | REAL WORLD 2 — User preference (theme / language)
    |--------------------------------------------------------------------------
    | YouTube dark mode, Urdu/English language — saved in session (or cookie).
    |--------------------------------------------------------------------------
    */
    public function saveTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        Session::put('theme', $request->theme);

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Theme saved as ' . $request->theme);
    }

    /*
    |--------------------------------------------------------------------------
    | REAL WORLD 3 — Basic flash (auto gone after one page)
    |--------------------------------------------------------------------------
    | flash('key', 'value')  OR  redirect()->with('key', 'value')
    | Same idea: store for the NEXT request, then Laravel deletes it.
    |--------------------------------------------------------------------------
    */
    public function flashDemo()
    {
        // Same as: Session::flash('success', '...'); return redirect(...)
        return redirect()
            ->route('sessions.index')
            ->with('success', 'FLASH: refresh this page — message disappears (auto forget)');
    }

    /*
    | Session::now('key', value)
    | Available on THIS request only (not even the next redirect page).
    | Rare in controllers that redirect; useful when you render a view
    | in the SAME request after setting a temporary notice.
    |--------------------------------------------------------------------------
    */
    public function flashNowDemo()
    {
        Session::now('success', 'Session::now() — only on THIS request (no redirect kept it)');

        // We render index in the SAME request so you can see "now" data
        $visits = Session::get('visits', 0);
        $otpStatus = $this->checkOtpExpiry();

        return view('sessions.index', [
            'visits' => $visits,
            'email' => Session::get('email'),
            'loggedIn' => Session::get('logged_in', false),
            'theme' => Session::get('theme', 'light'),
            'cart' => Session::get('cart', []),
            'lastProduct' => Session::get('last_product'),
            'otp' => Session::get('otp_code'),
            'otpExpiresAt' => Session::get('otp_expires_at'),
            'otpStatus' => $otpStatus,
            'sessionLifetimeMinutes' => config('session.lifetime'),
            'sessionDriver' => config('session.driver'),
            'allSession' => Session::all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FLASH WORKSHOP — flash → keep / reflash → still alive?
    |--------------------------------------------------------------------------
    |
    | Flow:
    |   1) flashStart()     → flash a message + go to workshop page
    |   2) workshop Blade   → you SEE the flash
    |   3) keep OR reflash  → extend flash for ONE more redirect
    |   4) flashAfter()     → proves whether the message survived
    |
    | keep(['flash_demo'])
    |   Keep ONLY these flash keys alive for one more request.
    |
    | reflash()
    |   Keep ALL flash keys alive for one more request.
    |
    | If you redirect WITHOUT keep/reflash, flash is already consumed
    | on the workshop page and will be empty on the next page.
    |--------------------------------------------------------------------------
    */
    public function flashStart()
    {
        Session::flash('flash_demo', 'I am flash data. Without keep/reflash I die after this workshop page.');
        Session::flash('flash_extra', 'Second flash key (reflash keeps BOTH; keep can pick one)');

        // Clear hold flag so workshop can extend flash once for the button POSTs
        Session::forget('_workshop_held');

        return redirect()->route('sessions.flashWorkshop');
    }

    public function flashWorkshop()
    {
        /*
        | Important Laravel detail:
        |   Flash lives for ONE request. If we show this page (request A) and
        |   the user clicks a button (request B), flash is already gone —
        |   unless we reflash() once during request A so the POST still sees it.
        |   (_workshop_held stops infinite keep-on-refresh)
        */
        if (! Session::get('_workshop_held')) {
            Session::reflash();
            Session::put('_workshop_held', true);
        }

        return view('sessions.flash-workshop', [
            'flashDemo' => Session::get('flash_demo'),
            'flashExtra' => Session::get('flash_extra'),
        ]);
    }

    public function flashKeep()
    {
        // Extend ONLY flash_demo for one more hop (flash_extra will die)
        Session::keep(['flash_demo']);
        Session::forget('_workshop_held');

        return redirect()->route('sessions.flashAfter')
            ->with('note', 'Called Session::keep([\'flash_demo\']) — only that key should survive');
    }

    public function flashReflash()
    {
        // Extend EVERY current flash key for one more hop
        Session::reflash();
        Session::forget('_workshop_held');

        return redirect()->route('sessions.flashAfter')
            ->with('note', 'Called Session::reflash() — all flash keys should survive');
    }

    public function flashSkipKeep()
    {
        // Do not keep/reflash — flash ends after this POST, next page empty
        Session::forget('_workshop_held');

        return redirect()->route('sessions.flashAfter')
            ->with('note', 'No keep/reflash — flash_demo should be EMPTY below');
    }

    public function flashAfter()
    {
        return view('sessions.flash-after', [
            'flashDemo' => Session::get('flash_demo'),
            'flashExtra' => Session::get('flash_extra'),
            'note' => session('note'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REAL WORLD 4 — Forget one key (keep the rest)
    |--------------------------------------------------------------------------
    */
    public function forgetLastProduct()
    {
        Session::forget('last_product');

        return redirect()
            ->route('sessions.index')
            ->with('success', 'last_product key removed (session still alive)');
    }

    /*
    |--------------------------------------------------------------------------
    | REAL WORLD 5 — Auto-forget ONE key after a few seconds (OTP style)
    |--------------------------------------------------------------------------
    |
    | Whole session lifetime (idle minutes) is config('session.lifetime').
    | For one value (OTP / reset code / limited coupon) we store:
    |   otp_code
    |   otp_expires_at   ← unix timestamp or datetime string
    |
    | On every page we call checkOtpExpiry():
    |   if now > expires_at → Session::forget those keys
    |
    | Demo uses 20 seconds so you can wait and refresh.
    |--------------------------------------------------------------------------
    */
    public function startOtp()
    {
        $seconds = 20;

        Session::put('otp_code', random_int(100000, 999999));
        //random_int is a function that generates a random integer between 100000 and 999999    
        Session::put('otp_expires_at', now()->addSeconds($seconds)->toDateTimeString());
        //now() is a function that returns the current time and addSeconds($seconds) is a function that adds the number of seconds to the current time
        //toDateTimeString() is a function that converts the time to a string
        //here otp_expires_at is a key and the value is the current time plus the number of seconds
        Session::put('otp_seconds', $seconds);

        return redirect()
            ->route('sessions.index')
            ->with('success', "OTP saved for {$seconds}s — wait, then refresh; it auto-forgets");
    }

    /*
    | checkOtpExpiry()
    |   private helper — not a route.
    |   Returns status text for the Blade page.
    */
    private function checkOtpExpiry(): string
    {
        if (! Session::has('otp_expires_at')) {
            return 'No OTP in session';
        }

        $expiresAt = Session::get('otp_expires_at');

        // Check if the OTP has expired by comparing the current time to the expiry timestamp.
        // now() returns a Carbon instance representing the current server time.
        // The '->' symbol is the PHP object operator, used here for method chaining:
        //     now()->gt($expiresAt)
        // means "call the gt() method on the Carbon object returned by now()".
        // gt($expiresAt) ("greater than") returns true if the current time is AFTER $expiresAt.
        // So: if the current time has passed the OTP expiration, clear it from session.
        if (now()->gt($expiresAt)) {
            Session::forget(['otp_code', 'otp_expires_at', 'otp_seconds']);

            return 'OTP expired — auto Session::forget() already ran';
        }
        //diffInSeconds is a function that returns the difference in seconds between the current time and the expires time
        $left = now()->diffInSeconds($expiresAt);

        return "OTP still valid — about {$left} second(s) left";
    }
}
