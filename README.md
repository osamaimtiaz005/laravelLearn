🧠 🔥 LARAVEL CHEATSHEET (SIGNS + SYMBOLS + WORDS)
🔹 1. Basic PHP Symbols (used everywhere)
$var // variable
-> // access object method/property
:: // static access getting static methods of class
=> // array key => value
[] // array
{} // block of code
() // function call

🔹 2. Laravel Routing

Route::get('/url', fn() => "Hello");
Route::post('/url', [Controller::class, 'method']);
Route::put('/url', ...);
Route::delete('/url', ...);

🔹 Route Parameters

/users/{id} // required
/users/{id?} // optional

🔹 Named Routes

Route::get('/home', ...)->name('home');

route('home'); // use it

🔹 3. Controllers

php artisan make:controller UserController
class UserController extends Controller {
public function index() {}
}

🔹 4. Models (Eloquent)

php artisan make:model User
User::all(); // get all
User::find(1); // find by id
User::where('name','Ali')->get();
User::create([...]);

🔹 Relationships

hasOne()
hasMany()
belongsTo()
belongsToMany()

🔹 5. Blade (View) Syntax
Blade is template engine for executing laravel code

{{ $var }} // print (safe)
{!! $var !!} // print (unsafe)

@if(condition)
@endif

@foreach($items as $item)
@endforeach

🔹 Blade Shortcuts
@csrf // security token
@method('PUT')

🔹 6. Request Handling

request('name');
$request->input('name');
$request->all();

🔹 7. Dependency Injection
public function store(UserService $service)

👉 Laravel auto injects

🔹 8. Middleware

Route::get('/dashboard', ...)->middleware('auth');

Create:

php artisan make:middleware CheckUser

🔹 9. Database (Query Builder)

DB::table('users')->get();
DB::table('users')->insert([...]);
DB::table('users')->update([...]);
DB::table('users')->delete();

🔹 10. Migrations

php artisan make:migration create_users_table
Schema::create('users', function (Blueprint $table) {
$table->id();
$table->string('name');
});

🔹 11. Artisan Commands

php artisan serve
php artisan migrate
php artisan make:model
php artisan make:controller
php artisan route:list

🔹 12. Helpers (very useful)
dd($data);          // dump & die
dump($data); // debug
route('name');  
url('/home');
redirect('/home');

🔹 13. Validation
$request->validate([
'name' => 'required|min:3',
]);

🔹 14. Auth (basic)

auth()->user();
auth()->check();

🔹 15. Collections

collect([1,2,3])->map(fn($x) => $x \* 2);

🔹 16. Service Container (DI core)

app(Service::class);

🔹 17. Environment

env('APP_NAME');
config('app.name');

🔹 18. API Response
return response()->json([
'success' => true
]);

🔹 19. File Upload
$request->file('image')->store('images');

---

🔥 Laravel Keywords (Easy + Short)

1. Service

👉 A class where you write main business logic
(“what should happen”)

2. Repository

👉 A class that handles database queries only
(“get/save data”)

3. Middleware

👉 Runs before request to check things
(like login, auth, permissions)

4. Service Provider

👉 Bootstraps things in Laravel
(registers services, configs)

5. Facade

👉 Shortcut to access Laravel features easily
Example:

Cache::get()
DB::table() 6. Eloquent

👉 Laravel’s ORM (database system)
Lets you use DB like objects

7. Migration

👉 File to create/modify database tables

8. Seeder

👉 Used to insert fake or default data

9. Factory

👉 Creates dummy/test data automatically

10. Controller

👉 Handles request and calls logic
(bridge between route & logic)

11. Model

👉 Represents a database table

12. Route

👉 Defines URL → what should run

13. Blade

👉 Laravel’s template engine (UI files)

14. Request

👉 Data coming from user
(form, API, URL)

15. Response

👉 Data sent back to user
(view, JSON, etc.)

16. Validation

👉 Checks if input is correct or not

17. Auth

👉 Authentication system
(login, logout, user check)

18. Collection

👉 Powerful array tools
(filter, map, sort easily)

19. Helper

👉 Small ready-made functions
Example:

dd()
route() 20. Dependency Injection

👉 Laravel automatically gives you objects
(no need to create manually)

🧠 SUPER SHORT MEMORY VERSION
Service → logic
Repository → DB
Middleware → check
Controller → handle
Model → table
Route → URL
Blade → UI
Request → input
Response → output

---

. Laravel File Structure (Important Folders)

👉 Laravel has many folders, but focus on these 👇

📁 app/

👉 Main logic lives here

Models/ → database models
Http/Controllers/ → controllers
Http/Middleware/ → middleware
📁 routes/

👉 All routes (URLs)

web.php → UI routes
api.php → API routes
📁 resources/

👉 Frontend files

views/ → Blade UI files
📁 database/

👉 DB related

migrations/ → table structure
seeders/ → dummy data

📁 public/

👉 Entry point

index.php → app starts here
📁 config/

👉 App settings

📁 storage/

👉 Logs, cache, uploads

📁 vendor/

👉 Installed packages (don’t touch)

🧠 Simple memory:
app → logic
routes → URLs
resources → UI
database → tables
public → start point

---

Laravel Lifecycle (Step-by-step)

👉 Lifecycle = what happens when user hits your app

🔁 Full flow:

1. User sends request
   example.com/users
2. Goes to entry point
   public/index.php

👉 Laravel starts here

3. Laravel boots
   Loads config
   Loads services
4. Route is matched
   /users → UserController@index
5. Middleware runs
   Check login
   Security checks
6. Controller runs
   UserController@index
7. Model fetches data
   User::all();
8. View is returned
   users.blade.php
9. Response sent to browser

👉 You see the page 🎉

🧠 One-line lifecycle:

🔥 What is a Directive in Laravel?

👉 A directive is a special instruction that starts with @

🧠 Simple meaning:

Directive = shortcut command for Blade

🔹 Example
@if($user)

   <p>User exists</p>
@endif

👉 @if and @endif are directives

🔥 What does it do?

👉 Laravel converts it into normal PHP

Behind the scenes:
@if($user)

becomes:

<?php if($user): ?>

🔹 Why use directives?
Cleaner code ✨
Easier to read 👀
Less PHP syntax 😵
🔥 Common Laravel Directives
🔹 1. Conditional directives
@if()
@elseif()
@else
@endif
🔹 2. Loop directives
@foreach()
@endforeach

@for()
@endfor

@while()
@endwhile
🔹 3. Layout directives
@extends('layout')

@section('content')
@endsection

@yield('content')
🔹 4. Include directives
@include('header')
🔹 5. Auth directives
@auth
@endauth

@guest
@endguest
🔹 6. CSRF directive
@csrf

👉 Adds security token in forms 🔒

🔹 7. Method directive
@method('PUT')

👉 Used for PUT/PATCH/DELETE in forms

🔹 8. Raw PHP
@php
$a = 10;
@endphp
🔹 9. Escaping / output
{{ $name }} // safe
{!! $html !!} // unsafe

🔥 What is a Custom Directive?

👉 Your own @something in Blade

Example:

@upper('hello')

👉 You define what it does 😎

🔹 Step-by-step: Create Custom Directive
✅ Step 1: Open AppServiceProvider

📁 File:

app/Providers/AppServiceProvider.php
✅ Step 2: Use Blade
use Illuminate\Support\Facades\Blade;
✅ Step 3: Register directive

Inside boot() method:

public function boot()
{
Blade::directive('upper', function ($expression) {
        return "<?php echo strtoupper($expression); ?>";
});
}
🔥 What this means:
'upper' → directive name
$expression → value passed
strtoupper() → PHP function
🔹 Step 4: Use in Blade
@upper('hello world')

👉 Output:

HELLO WORLD
🔥 Another Example (real use)
Date formatting
Blade::directive('formatDate', function ($expression) {
    return "<?php echo date('d M Y', strtotime($expression)); ?>";
});
Use:
@formatDate($user->created_at)

👉 Output:

14 Apr 2026
🔹 Important: $expression
@upper('hello')

👉 'hello' becomes:

$expression = 'hello'
🔹 Multiple parameters (advanced)
Blade::directive('sum', function ($expression) {
return "<?php echo array_sum([$expression]); ?>";
});

Use:

@sum(1,2,3)

👉 Output:

6

---

Laravel Blade has 3 main ways to reuse UI:

Concept Meaning
Layout Main template (base page)
Sub-view / Include Small reusable file
Component Reusable UI block with props (modern way)
🏗️ 2. Layout (Master Page)

👉 Layout = full page skeleton

Example:

<!-- resources/views/layout.blade.php -->
<html>
<head>
    <title>My App</title>
</head>
<body>

    @yield('content')

</body>
</html>
🔹 What is @yield?

👉 Placeholder (empty space)

“Child page will fill this area”

🔥 3. Child View (using layout)
@extends('layout')

@section('content')
<h1>Hello User</h1>
@endsection
🧠 How it works:
Laravel loads layout.blade.php
Finds @yield('content')
Replaces it with @section('content')
🔁 Flow:
layout.blade.php
↓
@yield('content')
↓
user.blade.php injects content
↓
Final HTML page
🔥 4. Sub-view (Include)

👉 Small reusable file

Example:

<!-- header.blade.php -->
<h1>My Header</h1>
Use it:
@include('header')
🧠 Meaning:

“Just paste this file here”

🔥 Difference: include vs layout
Feature @include @extends
Purpose small parts full layout
Usage header/footer full page structure
Replacement no yes (via yield)
🔥 5. Sections
@section('content')
@endsection

👉 This defines content for @yield

Bonus:
@show

👉 Immediately displays section (rare use)

🔥 6. Components (MODERN WAY 🚀)

👉 Reusable UI like buttons, cards, alerts

Example component:

<!-- resources/views/components/button.blade.php -->
<button class="btn">
    {{ $slot }}
</button>
Use it:
<x-button>
    Click Me
</x-button>
🧠 Meaning:

$slot = content inside component

🔥 Component with props:
<x-button type="submit" color="red" />

Component:

<button class="{{ $color }}">
    {{ $slot }}
</button>
🔥 7. Main Directives Summary
Feature	Directive
Layout	@extends
Content injection	@yield
Define content	@section
Small reuse	@include
Components	<x-...>
🧠 8. Real-life analogy
🏗️ Layout

👉 House structure (walls, roof)

🧩 Include

👉 Bricks / small parts (header, footer)

🎯 Components

👉 Ready-made furniture (button, card, alert)

🔥 9. How everything runs (FULL FLOW)
Route
↓
Controller
↓
return view()
↓
Layout loads
↓
@yield waits
↓
@extend fills sections
↓
@include inserts small parts
↓
<x-component> renders reusable UI
↓
Final HTML shown
🚀 10. Key Differences (VERY IMPORTANT)
Concept Purpose Reusability Complexity
Layout full page medium simple
Include small parts high simple
Components UI blocks very high modern
🔥 Final memory trick:
@extends = full structure 🏗️
@yield = empty space ⬜
@section = fill space ✍️
@include = paste file 📄
<x-\*> = reusable UI widget ⚙️

---

# firstLearning — Laravel Learning Project

A hands-on Laravel 12 learning repository. Each commit (and topic branch) adds a small, commented example so you can follow routing, Blade, controllers, forms, validation, middleware, and Eloquent step by step.

**Stack:** PHP 8.2+, Laravel 12, Blade, MySQL/XAMPP-friendly local setup  
**Current tip:** `main` / `db-start` → `7eb6315`

---

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Configure DB in .env (see .env.example comments), then migrate
php artisan migrate

# Start the dev server
php artisan serve
```

Open `http://127.0.0.1:8000` in your browser. Always use the same host for browsing and form submissions (see CSRF notes below).

**Switch to a topic branch:**

```bash
git branch -a                 # list all branches
git checkout views            # example: Blade lessons tip
git checkout Middleware       # middleware tip
git checkout db-start         # database / Eloquent tip (same as main)
```

---

## Branches Map

Each feature/topic was developed on its own branch, then merged into `main`. Tip commit = last commit unique to that topic tip.

| Branch | Tip commit | What this branch covers |
|--------|------------|-------------------------|
| `Routes-handling` | `5bd60bb` | Routing basics — GET/POST/view, redirects, parameters, HTTP methods |
| `controllers` | `c4ea075` | `UserController`, dynamic params, nested views (`admin.login`) |
| `views` | `4ab01fe` | Blade — loops, `@if`, `@include`, layouts, components |
| `postforminput-1` | `0888232` | User form POST + CSRF / 419 explanations |
| `postforminput-2` | `dd0b541` | Checkbox, radio, select — `storeUserAttributes` |
| `formValidation` | `21434a5` | `$request->validate()`, display `$errors` / `@error` |
| `validation_customErrors` | `83dd2cb` | Custom messages in controller + `lang/en/validation.php` |
| `ownCustom-rules` | `bfbc6d2` | Custom `ageLimit` rule + `AppServiceProvider` |
| `getting-url` | `5d9f7e5` | URL generation / inspect current URL in browser |
| `named-routed` | `5a4f438` | Named routes, `route()`, `to_route()`, product demo |
| `route-grouping` | `eba3ba4` | `Route::prefix()` admin group + controller grouping (`StudentController`) |
| `Middleware` | `cd7e11e` | Global, route, alias, and grouped middleware |
| `db-start` | `7eb6315` | DB setup, migrations, Eloquent `User` / `Customer`, list views |
| `main` | `7eb6315` | Latest full learning path (includes everything above) |

---

## Topic Explanations

Short plain-English guide for every topic practiced in this repo.

### 1. Project Setup & First View
- **What:** Create a Laravel app, edit Blade views under `resources/views`.
- **Why:** Views are the HTML users see; Laravel starts from `public/index.php`.
- **In this project:** Welcome page HTML in `welcome.blade.php`, README cheatsheet.

### 2. Routing Basics (`Routes-handling`)
- **What:** A route maps a URL + HTTP method to code that returns a response.
- **Key ideas:**
  - `Route::get()` — browser visit / link
  - `Route::view()` — return a Blade file with no logic
  - `Route::post()` — form submit
  - `{name}` — dynamic path segment (e.g. `/user/Ali`)
  - `Route::redirect()` — send user to another URL
- **Why:** Every page and form needs a route first.

### 3. Controllers (`controllers`)
- **What:** A class that holds request-handling methods (cleaner than huge closures in `web.php`).
- **Flow:** Route → Controller method → view / string / JSON.
- **Key ideas:**
  - `php artisan make:controller UserController`
  - `[UserController::class, 'getUser']` in the route
  - Dot notation for folders: `view('admin.login')` → `admin/login.blade.php`
- **Why:** Keeps routes short and logic organized.

### 4. Blade Templates (`views`)
- **What:** Laravel’s template engine — PHP-powered HTML with `@` directives.
- **Key ideas:**
  - `{{ $var }}` — print safely (escaped)
  - `@if` / `@foreach` — conditions and loops
  - `@include` — paste a small sub-view (header/footer)
  - `@extends` / `@section` / `@yield` — layout master page
  - `<x-component>` — reusable UI block with props (e.g. message banner)
- **Why:** Build UI without writing raw PHP mixed in HTML everywhere.

### 5. Forms & Request Handling (`postforminput-1`, `postforminput-2`)
- **What:** HTML forms send data to the server; Laravel reads it via `Request`.
- **Key ideas:**
  - GET shows the form; POST processes it
  - `$request->input('name')` or `$request->name`
  - Checkbox / radio / select — multiple or single values
  - `@csrf` — security token (without it → **419 Page Expired**)
- **Why:** Almost every app collects user input.

### 6. Form Validation (`formValidation`, `validation_customErrors`, `ownCustom-rules`)
- **What:** Check input before trusting it (required, email format, min length, etc.).
- **Key ideas:**
  - `$request->validate([...])` — built-in rules
  - Custom messages in the second array of `validate()`
  - Global messages in `lang/en/validation.php`
  - `old('field')` — keep typed values after an error
  - `@error('field')` / `$errors->any()` — show errors in Blade
  - Custom rule class (`ageLimit`) — your own logic (e.g. age 18–100)
  - `Validator::extend()` in `AppServiceProvider` — use the rule as a string `'ageLimit'`
- **Why:** Bad/missing data must be rejected with clear feedback.

### 7. URL Generation (`getting-url`)
- **What:** Helpers to build and inspect URLs (current path, links, absolute URLs).
- **Key ideas:** `url()`, `asset()`, current request URL helpers, demo pages under `/url-check/*`.
- **Why:** Don’t hardcode full URLs; Laravel helpers stay correct if the domain changes.

### 8. Named Routes (`named-routed`)
- **What:** Give a route a short alias and generate URLs from the name.
- **Key ideas:**
  - `->name('product')` on the route
  - `route('product')` in Blade/PHP — builds the URL
  - `to_route('product')` — redirect by name after save
  - Short name (`pf`) for a long path like `/named-route/profile/details/id`
- **Why:** Change the URL path later without hunting every link in the project.

### 9. Route Grouping (`route-grouping`)
- **What:** Share a prefix, middleware, or controller across many routes.
- **Key ideas:**
  - `Route::prefix('admin')->group(...)` → `/admin/users`, `/admin/dashboard`
  - Controller group — same controller, many actions (`StudentController`)
- **Why:** Less duplication, cleaner `web.php`.

### 10. Middleware (`Middleware`)
- **What:** Code that runs **before** (or after) the controller — filter/check the request.
- **Types practiced:**
  - **Global** — every request (`globalMid`)
  - **Route / alias** — only some routes (`EnsureAccessKey`)
  - **Grouped** — several middleware stacked as one
  - Extra check like `countryCheck`
- **Register:** `bootstrap/app.php` (aliases + global).
- **Why:** Login, API keys, country filters, etc. stay out of every controller method.

### 11. Database & Eloquent (`db-start`)
- **What:** Store and read data in MySQL via Laravel.
- **Key ideas:**
  - Configure DB in `.env` → `php artisan migrate`
  - **Model** = PHP class for a table (`User`, `Customer`)
  - `User::all()` / `Customer::all()` — fetch rows
  - `$table = 'customers'` when table name is non-default
  - Custom method on the model, called from the controller
  - `php artisan model:show customers` — inspect columns/relations
  - Controllers: `User_dbController`, `Customer_dbController`
  - Views: `database/users.blade.php`, `database/customers.blade.php`
- **Why:** Real apps persist users, customers, products — not only echo form data.

### How topics connect (big picture)

```
URL request
  → Route (match URL + method)
  → Middleware (optional checks)
  → Controller (logic)
  → Validation (if form)
  → Model / Database (if data)
  → Blade view (HTML response)
```

---

## Learning Path (Commit History)

Topics are listed in the order they were added to this repo.

### 1. Project Setup & Views
| Commit | Topic |
|--------|-------|
| `f6abb0f` | Initial Laravel project |
| `be7a2ee` | First Blade view — `resources/views/welcome.blade.php` |
| `c078891` / `2332d65` | README cheatsheet (PHP symbols, routing, Blade, etc.) |

### 2. Routing Basics — branch `Routes-handling`
| Commit | Topic |
|--------|-------|
| `1272cf7` | Home and test routes — `Route::get()` vs `Route::view()` |
| `777765f` | Pass data to views via route parameters |
| `50c99e9` | Navigate between routes using anchor tags in views |
| `3754410` | Redirect routes — `Route::redirect()` |
| `5bd60bb` | HTTP methods — GET, POST, PUT, DELETE, PATCH, match, any |
| `23dc77e` | PHP file rules (`<?php` at line 1, no closing `?>`) + route comments |

### 3. Controllers — branch `controllers`
| Commit | Topic |
|--------|-------|
| `872d3b6` | Create `UserController` — `php artisan make:controller UserController` |
| `83d1e9a` | Dynamic route parameters — `/dynamicUser-controller/{name}` |
| `2174cd6` | Return views from controller with dynamic data |
| `c4ea075` | Nested view folders — `view('admin.login')` → `admin/login.blade.php` |

### 4. Blade Templates — branch `views`
| Commit | Topic |
|--------|-------|
| `c64c447` | Blade basics, layout design, `::` static access operator |
| `87188d8` | Variables, arrays, `@if`, `@foreach` directives |
| `37b09c8` | Conditional rendering with `@if` |
| `c029ec0` | Sub-views with `@include` |
| `ad45b27` | Pass data into included sub-views |
| `4c52cfb` | Conditional `@include` with `@if` |
| `4ab01fe` | Blade components — `<x-messagebanner>` with dynamic type/style |

**Learning routes:** `/learn/blade-basics`, `/learn/blade-loops-data`, `/learn/interview-checklist`

### 5. Forms & Request Handling — branches `postforminput-1`, `postforminput-2`
| Commit | Topic |
|--------|-------|
| `096b857` | User form — GET form page + POST handler (`addUser`) |
| `0888232` | CSRF token (`@csrf`) — causes of 419 errors, Postman tips |
| `dd0b541` | Checkbox, radio, select inputs — `storeUserAttributes` |

### 6. Form Validation — branches `formValidation`, `validation_customErrors`, `ownCustom-rules`
| Commit | Topic |
|--------|-------|
| `47c7eea` | `$request->validate()` — rules for name, email, password |
| `21434a5` | Display errors — `$errors->any()`, `@error`, `@enderror` |
| `112dce9` | Custom validation messages in controller |
| `98c2917` | Retain input on error — `old('field')` |
| `8a9d23e` | Dynamic error CSS class — `$errors->has('name')` |
| `83dd2cb` | Global messages — `lang/en/validation.php` |
| `5fd9606` | Custom rule class — `app/Rules/ageLimit.php` |
| `ea32367` | Register rule via `Validator::extend()` in `AppServiceProvider` |
| `bfbc6d2` | Detailed comments on Service Provider, `extends`, `boot()` |
| `a1d2a40` | README learning docs (project overview + cheatsheet append) |

### 7. URL Generation — branch `getting-url`
| Commit | Topic |
|--------|-------|
| `5d9f7e5` | Generate / inspect URLs in browser — `/url-check/*` pages |

### 8. Named Routes — branch `named-routed`
| Commit | Topic |
|--------|-------|
| `4d42623` | Named route for user profile (`->name('user')`) |
| `b885d0e` | Short alias `pf` for long profile path |
| `5a3f2f8` | `route('pf')` helper usage in Blade |
| `86156dc` | Product routes + `to_route()` helper after save |
| `5a4f438` | Find product by name — named routes `product.find` / `product.show` |

### 9. Route Grouping — branch `route-grouping`
| Commit | Topic |
|--------|-------|
| `8457471` | Admin prefix group — `/admin/users`, `/admin/dashboard` |
| `eba3ba4` | Controller grouping — `StudentController` show/edit/delete/create/about |

### 10. Middleware — branch `Middleware`
| Commit | Topic |
|--------|-------|
| `c34b5e1` | Global middleware registered in `bootstrap/app.php` |
| `f288c42` | Access-key middleware alias + conditional route demo |
| `40657d7` | Group multiple middleware and apply as one stack on a route |
| `cd7e11e` | Extra comments clarifying middleware flow |

**Key middleware files:** `globalMid.php`, `EnsureAccessKey.php`, `countryCheck.php`  
**Demo routes:** `/middleware-demo/*`, `/middleware-group-check`, `/multiple-middleware-to-route`

### 11. Database & Eloquent — branch `db-start` (current `main`)
| Commit | Topic |
|--------|-------|
| `a5c115f` | DB + session setup in `.env.example`, migrate, seed user, `User_dbController` |
| `901f466` | Show users table in UI — `/user-list` |
| `a5a5c12` | `Customer` Eloquent model + `Customer::all()` — `/customer-list` |
| `01c8a08` | Explicit `$table` on Customer model |
| `f8e828b` | Custom model method invoked from `Customer_dbController` |
| `7eb6315` | Inspect model — `php artisan model:show customers` |

---

## Routes Reference

### Core learning routes
| URL | Method | Handler | Purpose |
|-----|--------|---------|---------|
| `/` | GET | closure | Welcome page |
| `/home` | GET | closure | Home view |
| `/test` | GET | closure / `Route::view` | Test view |
| `/user/{name}` | GET | closure | Pass name to `user` view |
| `/check/redirect` | GET | redirect | Redirect to `/` |
| `/user-controller` | GET | `UserController@getUser` | Return string from controller |
| `/dynamicUser-controller/{name}` | GET | `UserController@getDynamicUser` | Dynamic URL parameter |
| `/viewbycontroller/{name}` | GET | `UserController@getViewByController` | View from controller |
| `/viewArraydata` | GET | `UserController@getViewArrayData` | Blade `@foreach` demo |
| `/admin/login` | GET | `UserController@getAdminLogin` | Nested folder view |
| `/dashboard` | GET | closure (named) | Named route `dashboard` |
| `/search?q=&page=` | GET | closure | Query string demo |
| `/hello/{name}` | GET | closure | Path parameter demo |
| `/learn/blade-basics` | GET | closure | Blade basics lesson |
| `/learn/blade-loops-data` | GET | closure | Loops and data lesson |
| `/learn/interview-checklist` | GET | closure | Interview checklist |
| `/mainview` | GET | closure | Layout + `@include` demo |
| `/user-form` | GET | closure | User registration form |
| `/addUser` | POST | `UserController@addUser` | Process user form |
| `/user-attributes-form` | GET | closure | Checkbox/radio form |
| `/store-user-attributes` | POST | `UserController@storeUserAttributes` | Process attributes form |
| `/form-validation` | GET | closure | Validation form |
| `/validate-form` | POST | `UserController@validateForm` | Validate and show errors |

### URL check, named routes, grouping, middleware, DB
| URL | Method | Handler | Purpose |
|-----|--------|---------|---------|
| `/url-check/landing` | GET | closure | URL generation demo |
| `/url-check/about` | GET | closure | URL generation demo |
| `/url-check/products` | GET | closure | URL generation demo |
| `/named-route/profile/details/id` | GET | closure (`pf`) | Named route short alias |
| `/named-route/product/details/id` | GET | `getProduct` (`product`) | Product page |
| `/named-route/product/add` | GET | `showAddProductForm` (`product.add`) | Add product form |
| `/named-route/product/save` | POST | `saveProduct` (`product.save`) | Save + `to_route()` |
| `/named-route/product/find` | GET/POST | find methods | Find product by name |
| `/named-route/product/show/{name}` | GET | `showProductByName` | Show found product |
| `/admin/users` | GET | group closure | Prefix group demo |
| `/admin/moderators` | GET | group closure | Prefix group demo |
| `/admin/dashboard` | GET | group closure | Prefix group demo |
| `/student/show` etc. | GET | `StudentController` | Controller route group |
| `/middleware-demo/*` | GET | middleware demos | Access-key / country checks |
| `/middleware-group-check` | GET | grouped middleware | Multiple middleware as one |
| `/multiple-middleware-to-route` | GET | multi middleware | Stack middleware on one route |
| `/user-db` | GET | `User_dbController@user_db` | DB user fetch demo |
| `/user-list` | GET | `User_dbController@userList` | Users table UI |
| `/customer-list` | GET | `Customer_dbController@customerList` | Customers via Eloquent |

---

## Key Files

| File | What it teaches |
|------|-----------------|
| `routes/web.php` | All routes with inline learning comments |
| `app/Http/Controllers/UserController.php` | Controllers, forms, validation, named-route products |
| `app/Http/Controllers/StudentController.php` | Controller-based route grouping |
| `app/Http/Controllers/User_dbController.php` | Fetch users from database |
| `app/Http/Controllers/Customer_dbController.php` | Eloquent customers list |
| `app/Models/User.php` | Default Eloquent user model |
| `app/Models/Customer.php` | Custom model, `$table`, custom `show()` method |
| `app/Http/Middleware/globalMid.php` | Global middleware |
| `app/Http/Middleware/EnsureAccessKey.php` | Access-key / alias middleware |
| `app/Http/Middleware/countryCheck.php` | Country check middleware |
| `bootstrap/app.php` | Register global + middleware aliases |
| `app/Providers/AppServiceProvider.php` | `Validator::extend()` for custom rules |
| `app/Rules/ageLimit.php` | Custom `ValidationRule` class (age 18–100) |
| `lang/en/validation.php` | Customize default Laravel error messages |
| `resources/views/validation/form-validation.blade.php` | `old()`, `@error`, `$errors->has()` |
| `resources/views/user-form.blade.php` | CSRF, form fields, POST submission |
| `resources/views/user-attributesForm.blade.php` | Checkbox, radio, select inputs |
| `resources/views/database/users.blade.php` | Users list from DB |
| `resources/views/database/customers.blade.php` | Customers list from Eloquent |
| `resources/views/learn/*.blade.php` | Structured Blade lessons |
| `resources/views/components/messagebanner.blade.php` | Reusable Blade component |

---

## Custom Validation — Two Approaches

### Approach 1: Rule class directly (no Service Provider)

```php
use App\Rules\ageLimit;

$request->validate([
    'age' => ['required', new ageLimit],
]);
```

### Approach 2: String rule via AppServiceProvider (used in this project)

```php
// AppServiceProvider::boot()
Validator::extend('ageLimit', function ($attribute, $value, $parameters, $validator) {
    (new ageLimit())->validate($attribute, $value, function ($message) use ($attribute, $validator) {
        $validator->errors()->add($attribute, $message);
    });
    return true;
});

// UserController
'age' => 'required|ageLimit',
```

---

## CSRF Tips

- Always include `@csrf` inside `<form method="post">`.
- Use the same URL host for browsing and submitting (e.g. always `127.0.0.1`, not mixing with `localhost`).
- Session expiry or missing cookies cause **419 Page Expired**.
- For Postman/API testing, send the `_token` value and session cookie together.

---

## Useful Artisan Commands

```bash
php artisan serve                    # Start dev server
php artisan route:list               # List all routes
php artisan make:controller Name
php artisan make:model Name
php artisan make:middleware Name
php artisan migrate                  # Run migrations
php artisan model:show customers     # Inspect Eloquent model / table
php artisan model:show User
```

---

## Full Commit Log (newest → oldest)

| Hash | Date | Message (short) |
|------|------|-----------------|
| `7eb6315` | 2026-07-14 | `model:show` explanation in Customer model |
| `f8e828b` | 2026-07-14 | Model method invoked from Customer controller |
| `01c8a08` | 2026-07-14 | Explicit `$table` on Customer model |
| `a5a5c12` | 2026-07-14 | Customer Eloquent model + `/customer-list` |
| `901f466` | 2026-07-14 | User list UI — `/user-list` |
| `a5c115f` | 2026-07-13 | DB setup, migrate, User_dbController |
| `cd7e11e` | 2026-07-13 | Middleware comments |
| `40657d7` | 2026-07-13 | Grouped middleware on route |
| `f288c42` | 2026-07-13 | Access-key middleware + demos |
| `c34b5e1` | 2026-07-13 | Global middleware in app.php |
| `eba3ba4` | 2026-07-13 | StudentController route grouping |
| `8457471` | 2026-07-13 | Admin route prefix grouping |
| `5a4f438` | 2026-07-13 | Product find by name (named routes) |
| `86156dc` | 2026-07-13 | Product CRUD routes + `to_route()` |
| `5a3f2f8` | 2026-07-13 | `route('pf')` comment clarity |
| `b885d0e` | 2026-07-11 | Short named route `pf` |
| `4d42623` | 2026-07-11 | Named profile route |
| `5d9f7e5` | 2026-07-11 | URL generation / URL check pages |
| `a1d2a40` | 2026-07-09 | README project learning docs |
| `bfbc6d2` | 2026-07-09 | AppServiceProvider custom-rule comments |
| `ea32367` | 2026-07-09 | Fix ageLimit Validator::extend errors |
| `5fd9606` | 2026-07-09 | ageLimit rule + form age field |
| `83dd2cb` | 2026-07-09 | Custom lang validation messages |
| `8a9d23e` | 2026-07-09 | `old()` + error CSS class comments |
| `98c2917` | 2026-07-09 | Retain form input with `old()` |
| `112dce9` | 2026-07-09 | Custom controller validation messages |
| `21434a5` | 2026-07-07 | Display validation errors in Blade |
| `47c7eea` | 2026-07-07 | Form validation routes + method |
| `dd0b541` | 2026-07-07 | Checkbox / radio / select attributes |
| `0888232` | 2026-07-07 | CSRF / 419 explanations |
| `096b857` | 2026-07-07 | User form POST (`addUser`) |
| `4ab01fe` | 2026-04-14 | Blade components (message banner) |
| `4c52cfb` | 2026-04-14 | Conditional footer `@include` |
| `ad45b27` | 2026-04-14 | Pass data into `@include` |
| `c029ec0` | 2026-04-14 | Sub-views with `@include` |
| `37b09c8` | 2026-04-14 | Blade `@if` condition |
| `87188d8` | 2026-04-14 | Blade loops / variables / directives |
| `c64c447` | 2026-04-14 | Blade keywords + learning routes |
| `c4ea075` | 2026-04-07 | Nested folder views via controller |
| `2174cd6` | 2026-04-07 | Return view from controller |
| `83d1e9a` | 2026-04-07 | Dynamic route param in controller |
| `23dc77e` | 2026-04-07 | PHP file `<?php` rules + route comments |
| `872d3b6` | 2026-04-07 | Create UserController |
| `5bd60bb` | 2026-04-06 | Routing methods explained |
| `3754410` | 2026-04-06 | Redirect route |
| `50c99e9` | 2026-04-06 | Anchor navigation between routes |
| `777765f` | 2026-04-06 | Pass data via route parameter |
| `1272cf7` | 2026-04-06 | Home / test routes + comments |
| `2332d65` | 2026-04-06 | README Laravel cheatsheet |
| `be7a2ee` | 2026-04-06 | Welcome Blade HTML |
| `c078891` | 2026-04-04 | Early README update |
| `f6abb0f` | 2026-04-04 | First commit |


---

# Newer Topics — Detailed Code Explanations (after db-start)

Everything below covers commits and branches added **after** `7eb6315` / the earlier README sections. Each topic shows what you learnt, the important code, and how to try it.

**New branches:** `httpClient-api` · `db-queryBuilder` · `eloquent-queryBuilder` · `route-methods` · `allRequestMethods` · `Sessions` · `upload-files` · `localization` · `pagination` · `LayoutCssJs` · `migrations`

**Latest tip:** `main` / `migrations` → `f0954fd`

---

## Branches Map (new topics)

| Branch | Tip | What you learnt |
|--------|-----|-----------------|
| `httpClient-api` | `81a381a` | Call external APIs with `Http` facade, decode JSON, show in Blade |
| `db-queryBuilder` | `2e31b24` | `DB::table()` CRUD — get, where, insert, update, delete, search, sort |
| `eloquent-queryBuilder` | `614bbd6` | `Student` model — `all`, `find`, `where`, `create`, `update`, `delete` |
| `route-methods` | `32dbb5d` | GET/POST/PUT/PATCH/DELETE/ANY/MATCH via `AllrouteController` (mini shop) |
| `allRequestMethods` | `36c992e` | `Illuminate\Http\Request` methods — input, query, headers, method, etc. |
| `Sessions` | `ea87a34` | Session put/get/flash/forget/flush — login / profile demos |
| `upload-files` | `10fb3a6` | File upload, `store` / `storeAs`, Storage, download, delete |
| `localization` | `a0c2e70` | Multi-language with locale param + `SetLocale` middleware + session |
| `pagination` | `ffccd21` | `paginate()`, page links, `?page=`, LengthAwarePaginator |
| `LayoutCssJs` | `99962c1` | Shared Blade layout + CSS/JS assets + dynamic dashboard page |
| `migrations` | `f0954fd` | Create/alter/drop tables, `up`/`down`, rollback, migrate commands |

---

## 12. HTTP Client & API — branch `httpClient-api`

### What
Your Laravel app becomes a **client**: it sends an HTTP request to another server (API), gets JSON back, and shows it in a view.

### Important code (`httpController`)

```php
use Illuminate\Support\Facades\Http;

$response = Http::get('https://jsonplaceholder.typicode.com/users/1');
$data = json_decode($response->body());
return view('httpRes.users', ['data' => $data]);
```

### Word-by-word
| Piece | Meaning |
|-------|---------|
| `Http` | Facade — shortcut to Laravel’s HTTP client |
| `Http::get(url)` | Send GET request to that URL |
| `$response->body()` | Raw response string (JSON text) |
| `json_decode(...)` | Turn JSON string into a PHP object/array |
| `return view(...)` | Pass decoded data to Blade |

### Also know
- **API** = endpoints on a server that return data (often JSON)
- **HTTP Client** = your app calling those endpoints
- Alternative to `json_decode`: `$response->json()` (Laravel helper → array)

### Try
Visit: `/http-controller`

### Commit
`81a381a`

---

## 13. Database Query Builder — branch `db-queryBuilder`

### What
Talk to MySQL **without** a Model — use `DB::table('users')`. Laravel builds SQL for you.

### Important patterns (`DBQueryController`)

```php
use Illuminate\Support\Facades\DB;

// All rows
$data = DB::table('users')->get();

// Filter
$data = DB::table('users')->where('id', $id)->get();
$data = DB::table('users')->where('name', $name)->get();

// Insert / Update / Delete
DB::table('users')->insert([...]);
DB::table('users')->where('id', $id)->update([...]);
DB::table('users')->where('id', $id)->delete();

// Search / Sort
DB::table('users')->where('name', 'like', '%'.$keyword.'%')->get();
DB::table('users')->orderBy('name', 'asc')->get();
```

### Flow
`Browser → route → DBQueryController → DB::table(...) → Blade table`

### Query Builder vs Eloquent
| Query Builder | Eloquent |
|---------------|----------|
| `DB::table('users')` | `User::all()` / `Student::find(1)` |
| Returns stdClass rows | Returns Model objects |
| No `$fillable` needed | Needs Model + often `$fillable` |

### Try
- `/db-query-builder/all`
- `/db-query-builder/byid/1`
- `/db-query-builder/search?keyword=...`

### Commit
`2e31b24`

---

## 14. Eloquent Query Builder — branch `eloquent-queryBuilder`

### What
Use a **Model** (`Student`) as an object for a table. Cleaner CRUD for app logic.

### Important code (`ElqQueryBuilder` + `Student` model)

```php
use App\Models\Student;

Student::all();                          // every student
Student::find($id);                      // one by primary key (or null)
Student::where('name', $name)->get();    // filter → Collection
Student::where('batch', $batch)->get();

Student::create([                        // needs $fillable on model
    'name' => $request->name,
    'email' => $request->email,
    'batch' => $request->batch,
]);

$student = Student::find($id);
$student->update([...]);
$student->delete();

// Search with LIKE
Student::where('name', 'like', '%'.$keyword.'%')->get();
```

### Key ideas
- `find()` returns **one model or null** — wrap as `[$student]` if Blade `@forelse` needs a list
- `where()->get()` already returns a **Collection** — do not wrap again in `[...]`
- `create()` / mass assignment needs `protected $fillable = [...]` on the model
- Flash after redirect: `->with('success', '...')`

### Try
Routes under `/elqQueryBuilder/...` (studentList, studentById, add, update, delete, search)

### Commit
`614bbd6`

---

## 15. Route HTTP Methods (mini shop) — branch `route-methods`

### What
Practice every verb on real-ish shop URLs via `AllrouteController`.

### Methods you learnt
| Method | Typical use | Example idea |
|--------|-------------|--------------|
| `GET` | Show page / list | View products |
| `POST` | Create | Add to cart / place order |
| `PUT` | Replace whole resource | Replace product |
| `PATCH` | Partial update | Change price only |
| `DELETE` | Remove | Delete product |
| `Route::match(['get','post'], ...)` | Same URL, two verbs | Form show + submit |
| `Route::any(...)` | Any verb | Debugging / catch-all |

### Forms tip
Browsers only send GET/POST. For PUT/PATCH/DELETE use:

```blade
@method('PUT')
@csrf
```

### Commit
`32dbb5d`

---

## 16. Request Class Methods — branch `allRequestMethods`

### What
`Illuminate\Http\Request` holds **everything** the browser sent. Laravel injects it when you type-hint `Request $request`.

### Important methods (`RequestMethodsController`)

```php
$request->method();          // GET, POST, PUT...
$request->isMethod('post');  // true/false
$request->all();             // all input as array
$request->input('name');     // one field
$request->name;              // magic property (same idea)
$request->query('q');        // query string ?q=
$request->has('email');      // field present?
$request->filled('email');   // present and not empty?
$request->only(['name','email']);
$request->except(['password']);
$request->header('User-Agent');
$request->ip();
$request->path();            // path without domain
$request->url(); / $request->fullUrl();
$request->file('photo');     // uploaded file
```

### Dependency injection
```php
public function show(Request $request) {
    // Laravel creates $request for you — no "new Request()"
}
```

### Try
Routes / views under `RequestMethods` section (linked from index page)

### Commit
`36c992e`

---

## 17. Session & Flash Session — branch `Sessions`

### What
HTTP is **stateless**. Session stores data on the **server** between requests. Browser only keeps a session **cookie ID**.

### Important API (`SessionsController`)

```php
use Illuminate\Support\Facades\Session;

Session::put('email', $email);     // save
Session::get('email');              // read
Session::has('email');              // exists?
Session::forget('email');           // delete one key
Session::flush();                   // clear all (logout)
Session::all();                     // dump everything

// Flash = lives for the NEXT request only
Session::flash('success', 'Saved!');
return redirect(...)->with('success', 'Saved!');  // same idea
```

### Helpers
```php
session('email');
session(['cart' => []]);
$request->session()->put('key', 'value');
```

### Flash extras
| Method | Meaning |
|--------|---------|
| `now('key', value)` | Available on **this** request only |
| `keep(['key'])` | Keep listed flash keys one more request |
| `reflash()` | Keep **all** flash keys one more request |

### Storage
- Default learning setup: files in `storage/framework/sessions/`
- Multi-server apps: `SESSION_DRIVER=database` or Redis
- Never store real passwords in session

### Try
Session hub routes (login / profile / logout / flash demos) from `SessionsController`

### Commit
`ea87a34`

---

## 18. File Uploads & Storage — branch `upload-files`

### What
Accept files from a form, validate them, save to disk, preview / download / delete.

### Form requirements
```blade
<form method="post" enctype="multipart/form-data" action="...">
    @csrf
    <input type="file" name="image">
</form>
```
Without `enctype="multipart/form-data"`, files **never arrive**.

### Important code (`UploadFileController`)

```php
$request->validate([
    'image' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
]);

if ($request->hasFile('image') && $request->file('image')->isValid()) {
    // Random name under storage/app/public/files/
    $path = $request->file('image')->store('files', 'public');

    // OR fixed name:
    // $path = $request->file('image')->storeAs('files', 'my.jpg', 'public');
}

use Illuminate\Support\Facades\Storage;
Storage::disk('public')->url($path);
Storage::disk('public')->exists($path);
Storage::disk('public')->delete($path);

return response()->download(...);  // force Save As
return response()->file(...);      // show inline
```

### UploadedFile helpers
`getClientOriginalName()` · `getClientOriginalExtension()` · `getSize()` · `getMimeType()` · `isValid()` · `store()` · `storeAs()`

### Public access
```bash
php artisan storage:link
```
Creates `public/storage` → `storage/app/public` so URLs like `/storage/files/xxx.jpg` work.

### Commit
`10fb3a6`

---

## 19. Localization (multi-language) — branch `localization`

### What
Show the same page in different languages (`en`, `ur`, etc.) using locale + translation files + middleware.

### How it works in this project
1. URL or choice sets locale (e.g. `/localization/welcome/{locale}`)
2. `SetLocale` middleware reads session / route param and calls `App::setLocale(...)`
3. Blade / `__()` / `trans()` pick strings from `lang/{locale}/...`

### Controller idea
```php
public function welcome(string $locale)
{
    return view('localization.welcome', [
        'locale' => session('locale', $locale),
    ]);
}
```

### Key ideas
- Locale can come from **URL param**, **session**, or later **IP / Accept-Language**
- Middleware keeps locale applied on every web request after user chooses language
- Translation files live under `lang/`

### Commit
`a0c2e70`

---

## 20. Pagination — branch `pagination`

### What
Split large lists into pages so you do not load every row at once.

### Important code (`PaginationController`)

```php
$students = Student::paginate(10);   // 10 per page
// URL: /paginate/list?page=2

return view('...', ['students' => $students]);
```

### In Blade
```blade
@foreach($students as $student)
    ...
@endforeach

{{ $students->links() }}   {{-- page 1 2 3 Next --}}
```

### Why
| Without | With |
|---------|------|
| `Student::all()` loads everything | `paginate(n)` loads one page + total count |
| Slow / heavy memory | Fast + numbered links |

### Related methods (learnt in comments)
- `paginate()` — LengthAwarePaginator (knows total pages)
- `simplePaginate()` — only Prev/Next (lighter)
- `cursorPaginate()` — cursor-based (huge datasets)

### Commit
`ffccd21`

---

## 21. Layout + CSS + JS — branch `LayoutCssJs`

### What
One shared Blade **layout** (nav, footer, CSS, JS) used by many pages. Child pages only fill `@section('content')`.

### Structure
```
resources/views/layout-demo/     ← layout + pages
public/css/layout-demo/          ← shared CSS
public/js/layout-demo/           ← shared JS
LayoutDemoController             ← home / about / dashboard / flash
```

### Pattern
```blade
{{-- layout --}}
<html>
  <link href="{{ asset('css/layout-demo/app.css') }}">
  @yield('content')
  <script src="{{ asset('js/layout-demo/app.js') }}"></script>
</html>

{{-- child page --}}
@extends('layout-demo.layout')
@section('content')
  <h1>Home</h1>
@endsection
```

### Dynamic page example
`dashboard()` passes arrays (`$user`, `$stats`, `$activities`) into the view — same pattern as real apps with Auth + Eloquent.

### Try
`/layout-demo` · `/layout-demo/about` · `/layout-demo/dashboard` · `/layout-demo/flash`

### Commit
`99962c1`

---

## 22. Migrations — branch `migrations`

### What
Version-controlled PHP files that **create / change / drop** database tables. Team-safe way to share DB structure.

### Anatomy
```php
return new class extends Migration
{
    public function up(): void
    {
        // APPLY change
        Schema::create('test', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // UNDO change (for rollback)
        Schema::dropIfExists('test');
    }
};
```

### Alter existing table (add / drop column)
```php
public function up(): void
{
    Schema::table('test', function (Blueprint $table) {
        $table->string('username');
    });
}

public function down(): void
{
    Schema::table('test', function (Blueprint $table) {
        $table->dropColumn('username');
    });
}
```

### Commands you learnt
```bash
php artisan make:migration create_test_table
php artisan make:migration add_username_to_test_table --table=test
php artisan migrate                 # run pending up()
php artisan migrate:rollback        # run down() of last batch
php artisan migrate:reset           # rollback all
php artisan migrate:refresh         # reset + migrate again
php artisan migrate:status          # see which ran
```

### Files in this project
- `create_test_table` / `create_test1` / `test2` / `test3`
- `add_username_to_test_table`
- `add_permanent_address_to_test_table`
- `create_students_table`
- Explanation page: `resources/views/migration/explain.blade.php`

### Try
Route for migration explanation (added in `a96c6e0`) + practice migrate / rollback locally

### Commits
`a96c6e0` (commands + explain view) · `f0954fd` (update/delete table + rollback `down()`)

---

## New Routes Quick List

| Area | Example URLs |
|------|----------------|
| HTTP Client | `/http-controller` |
| Query Builder | `/db-query-builder/all`, `/byid/{id}`, insert/update/delete/search/sort |
| Eloquent | `/elqQueryBuilder/studentList`, `studentById/{id}`, add/update/delete/search |
| Sessions | Session login / profile / logout / flash demos |
| Uploads | Upload form, store, preview, download, delete |
| Localization | `/localization/...` welcome by locale |
| Pagination | `/paginate/list?page=2` |
| Layout demo | `/layout-demo`, `/about`, `/dashboard`, `/flash` |
| Migrations | Migration explain view route |

---

## New Key Files

| File | Topic |
|------|--------|
| `app/Http/Controllers/httpController.php` | HTTP client / API |
| `app/Http/Controllers/DBQueryController.php` | Query Builder CRUD |
| `app/Http/Controllers/ElqQueryBuilder.php` | Eloquent Student CRUD |
| `app/Models/Student.php` | Student Eloquent model |
| `app/Http/Controllers/AllrouteController.php` | HTTP method routes |
| `app/Http/Controllers/RequestMethodsController.php` | Request class demos |
| `app/Http/Controllers/SessionsController.php` | Session & flash |
| `app/Http/Controllers/UploadFileController.php` | File upload / Storage |
| `app/Http/Controllers/LocalizationController.php` | Locale welcome page |
| `app/Http/Middleware/SetLocale.php` (or similar) | Apply locale from session |
| `app/Http/Controllers/PaginationController.php` | `paginate()` list |
| `app/Http/Controllers/LayoutDemoController.php` | Layout + CSS/JS pages |
| `database/migrations/*test*` | Create / alter tables |
| `resources/views/migration/explain.blade.php` | Migration commands explained |

---

## Full Commit Log (newer only — newest → oldest)

| Hash | Date | Topic |
|------|------|--------|
| `f0954fd` | 2026-07-20 | Migration update/delete table + rollback `down()` |
| `a96c6e0` | 2026-07-20 | Migration commands + explain view/route |
| `99962c1` | 2026-07-18 | Layout + CSS/JS integration |
| `ffccd21` | 2026-07-17 | Pagination methods |
| `a0c2e70` | 2026-07-17 | Localization + SetLocale middleware |
| `10fb3a6` | 2026-07-16 | File uploading & Storage |
| `ea87a34` | 2026-07-15 | Session & flash session |
| `36c992e` | 2026-07-15 | Request class methods |
| `32dbb5d` | 2026-07-15 | All route HTTP methods (mini shop) |
| `614bbd6` | 2026-07-14 | Eloquent query builder (Student) |
| `2e31b24` | 2026-07-14 | Database query builder |
| `81a381a` | 2026-07-14 | HTTP client & API |
| `b89ab4a` | 2026-07-14 | README topic explanations update |

---

## How all new topics connect

```
External API ──Http::get──► httpController ──► Blade
                                    │
Form / URL ──Request methods──► Controller
                                    │
                         ┌──────────┼──────────┐
                         ▼          ▼          ▼
                    Query Builder  Eloquent   Session
                    DB::table()    Model::    put/get/flash
                         │          │
                         ▼          ▼
                    Migrations define tables first
                         │
                         ▼
              Pagination / Layout+CSS+JS / File Storage / Localization
              (how you show & shape the response for users)
```
