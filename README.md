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
**Current tip:** `main` / `migrations` → `6d8ef13` (latest code tip before README: `f0954fd`)

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

| Branch                    | Tip commit | What this branch covers                                                   |
| ------------------------- | ---------- | ------------------------------------------------------------------------- |
| `Routes-handling`         | `5bd60bb`  | Routing basics — GET/POST/view, redirects, parameters, HTTP methods       |
| `controllers`             | `c4ea075`  | `UserController`, dynamic params, nested views (`admin.login`)            |
| `views`                   | `4ab01fe`  | Blade — loops, `@if`, `@include`, layouts, components                     |
| `postforminput-1`         | `0888232`  | User form POST + CSRF / 419 explanations                                  |
| `postforminput-2`         | `dd0b541`  | Checkbox, radio, select — `storeUserAttributes`                           |
| `formValidation`          | `21434a5`  | `$request->validate()`, display `$errors` / `@error`                      |
| `validation_customErrors` | `83dd2cb`  | Custom messages in controller + `lang/en/validation.php`                  |
| `ownCustom-rules`         | `bfbc6d2`  | Custom `ageLimit` rule + `AppServiceProvider`                             |
| `getting-url`             | `5d9f7e5`  | URL generation / inspect current URL in browser                           |
| `named-routed`            | `5a4f438`  | Named routes, `route()`, `to_route()`, product demo                       |
| `route-grouping`          | `eba3ba4`  | `Route::prefix()` admin group + controller grouping (`StudentController`) |
| `Middleware`              | `cd7e11e`  | Global, route, alias, and grouped middleware                              |
| `db-start`                | `7eb6315`  | DB setup, Eloquent `User` / `Customer`, list views                        |
| `httpClient-api`          | `81a381a`  | Call external APIs with `Http` facade, decode JSON, show in Blade         |
| `db-queryBuilder`         | `2e31b24`  | `DB::table()` CRUD — get, where, insert, update, delete, search, sort     |
| `eloquent-queryBuilder`   | `614bbd6`  | `Student` model — `all`, `find`, `where`, `create`, `update`, `delete`    |
| `route-methods`           | `32dbb5d`  | GET/POST/PUT/PATCH/DELETE/ANY/MATCH via `AllrouteController`              |
| `allRequestMethods`       | `36c992e`  | `Illuminate\Http\Request` methods — input, query, headers, files          |
| `Sessions`                | `ea87a34`  | Session put/get/flash/forget/flush — login / profile demos                 |
| `upload-files`            | `10fb3a6`  | File upload, `store` / `storeAs`, Storage, download, delete               |
| `localization`            | `a0c2e70`  | Multi-language with locale + `SetLocale` middleware + session             |
| `pagination`              | `ffccd21`  | `paginate()`, page links, `?page=`                                        |
| `LayoutCssJs`             | `99962c1`  | Shared Blade layout + CSS/JS assets + dynamic dashboard                   |
| `migrations`              | `f0954fd`  | Create/alter/drop tables, `up`/`down`, rollback, migrate commands         |
| `main`                    | `6d8ef13`  | Latest full learning path (includes everything above)                     |

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

# Detailed Code Lessons — Examples, Commands & Q/A

For each topic: a short explanation, code, commands, then common Q&A.

---

## A. Routing

**Explain:** A route tells Laravel: “when someone visits this URL with this HTTP method, run this code.” Without routes, nothing is reachable. Closures are fine for tiny demos; controllers are better for real logic.

**Code**

```php
Route::get('/home', fn () => view('home'));     // GET → show page
Route::view('/test', 'test');                   // GET → view only (no logic)
Route::get('/user/{name}', function ($name) {   // {name} from URL
    return view('user', ['name' => $name]);
});
Route::redirect('/check/redirect', '/');        // send user elsewhere
Route::post('/addUser', [UserController::class, 'addUser']); // form submit
```

**Commands**

```bash
php artisan serve          # start app → http://127.0.0.1:8000
php artisan route:list     # list every registered URL + method
```

**Q/A**

- **Q: `Route::get` vs `Route::view`?**  
  A: `view` only returns a Blade file. Use `get` when you need logic or a controller.
- **Q: What is `{name}`?**  
  A: A path parameter. `/user/Ali` makes `$name = "Ali"`.
- **Q: Why does order matter?**  
  A: Laravel uses the **first** matching route. A wide route like `/user/{name}` can “shadow” a more specific one below it.

**Try:** `/home` · `/user/Ali` · `/check/redirect`

---

## B. Controllers

**Explain:** Controllers are PHP classes that hold methods for routes. They keep `web.php` short and group related actions (show form, save user, list data) in one place.

**Commands**

```bash
php artisan make:controller UserController
# creates app/Http/Controllers/UserController.php
```

**Code**

```php
// Route points to Class + method
Route::get('/dynamicUser-controller/{name}', [UserController::class, 'getDynamicUser']);

public function getDynamicUser(string $name): string
{
    return 'Hello ' . $name;   // . joins strings
}

public function getAdminLogin()
{
    return view('admin.login'); // folder.file → admin/login.blade.php
}
```

**Q/A**

- **Q: Why not put all logic in `web.php`?**  
  A: Routes become huge and hard to test. Controllers separate “URL map” from “business logic.”
- **Q: What does `::class` mean?**  
  A: PHP gives the full class name string so Laravel can resolve the controller.
- **Q: Dot in `admin.login`?**  
  A: Dot = folder. `admin.login` → `resources/views/admin/login.blade.php`.

**Try:** `/user-controller` · `/dynamicUser-controller/Osama` · `/admin/login`

---

## C. Blade

**Explain:** Blade is Laravel’s template engine. You write HTML plus `@` directives; Laravel compiles them to PHP. Use `{{ }}` to print safely (escaped), and `@if` / `@foreach` instead of raw PHP in views.

**Code**

```blade
{{ $name }}                          {{-- escape HTML (safe) --}}
@if($user) ... @endif
@foreach($items as $item)
    <p>{{ $item }}</p>
@endforeach
@include('partial.headerSubView')    {{-- paste small partial --}}
@extends('learn.layout')             {{-- use master layout --}}
@section('content') ... @endsection
<x-messagebanner type="success">OK</x-messagebanner>
```

**Q/A**

- **Q: `{{ }}` vs `{!! !!}`?**  
  A: `{{ }}` escapes XSS. `{!! !!}` prints raw HTML — only for trusted content.
- **Q: Layout vs include vs component?**  
  A: Layout = full page skeleton. Include = small piece. Component = reusable UI with props (`<x-...>`).
- **Q: What is a directive?**  
  A: An `@` keyword Blade turns into PHP (`@if` → `<?php if (): ?>`).

**Try:** `/learn/blade-basics` · `/mainview` · `/viewArraydata`

---

## D. Forms + CSRF

**Explain:** Forms send user input to the server (usually POST). Laravel protects POST/PUT/PATCH/DELETE with a CSRF token so other websites cannot submit forms as your logged-in user. Missing/wrong token → **419 Page Expired**.

**Code**

```blade
<form action="/addUser" method="post">
    @csrf
    <input name="name">
    <button>Save</button>
</form>
```

```php
public function addUser(Request $request)
{
    $name = $request->input('name');  // read field "name"
}
```

**Q/A**

- **Q: What does `@csrf` do?**  
  A: Adds a hidden `_token` field. Laravel checks it against the session token.
- **Q: Why 419 when using Postman?**  
  A: You must send `_token` and the session cookie together, or use the same host you started the session on.
- **Q: Checkbox / radio / select?**  
  A: Same idea — names become keys on `$request`. Checkboxes can be arrays (`skill[]`).

**Try:** `/user-form` · `/user-attributes-form`

---

## E. Validation

**Explain:** Validation checks input before you trust it (required, email format, length, custom rules). On failure, Laravel redirects back with an `$errors` bag and `old()` input so the form is not emptied.

**Code**

```php
$request->validate([
    'name' => 'required|min:3|max:10',
    'email' => 'required|email|unique:users',
    'age' => 'required|ageLimit',
], [
    'name.required' => 'Name cannot be empty',
]);
```

```blade
<input value="{{ old('name') }}">
@error('name') <p>{{ $message }}</p> @enderror
```

**Commands**

```bash
php artisan make:rule ageLimit
# then register string rule in AppServiceProvider with Validator::extend(...)
# optional: customize messages in lang/en/validation.php
```

**Q/A**

- **Q: Where do error messages come from?**  
  A: Built-in defaults, controller custom array, or `lang/en/validation.php`.
- **Q: What is `old('name')`?**  
  A: Last submitted value for that field after a failed validation redirect.
- **Q: Custom rule as string vs `new ageLimit`?**  
  A: `new ageLimit` needs no provider. String `'ageLimit'` needs `Validator::extend()` in `AppServiceProvider`.

**Try:** `/form-validation`

---

## F. Named routes + URL helpers

**Explain:** A named route is a short alias for a URL path. You generate links with `route('name')` so if the path changes later, you only update the route definition — not every Blade file.

**Code**

```php
Route::get('/named-route/product/details/id', [...])->name('product');

return redirect()->route('product');
return to_route('product');   // helper redirect by name
```

```blade
<a href="{{ route('product') }}">Product</a>
```

**Q/A**

- **Q: `route()` vs hardcoding `/named-route/...`?**  
  A: `route('product')` stays correct if you rename the path.
- **Q: What is `to_route()`?**  
  A: Shortcut for `redirect()->route(...)`.
- **Q: Why short name `pf`?**  
  A: Long URLs get a short alias for cleaner Blade links.

**Try:** `/url-check/landing` · `/named-route/product/...`

---

## G. Route grouping

**Explain:** Groups share a prefix, controller, or middleware across many routes so you do not repeat the same path/controller on every line.

**Code**

```php
Route::prefix('admin')->group(function () {
    Route::get('/users', ...);      // becomes /admin/users
    Route::get('/dashboard', ...);  // /admin/dashboard
});

Route::controller(StudentController::class)->prefix('student')->group(function () {
    Route::get('/show', 'show');    // StudentController@show
});
```

**Q/A**

- **Q: What does `prefix('admin')` do?**  
  A: Prepends `/admin` to every route inside the group.
- **Q: Controller group benefit?**  
  A: You write method names only (`'show'`) instead of `[StudentController::class, 'show']` each time.

---

## H. Middleware

**Explain:** Middleware runs **before** (or after) the controller — like a gate. Use it for access keys, country checks, login, locale, etc., so controllers stay focused on their main job.

**Commands**

```bash
php artisan make:middleware EnsureAccessKey
php artisan make:middleware countryCheck
php artisan make:middleware globalMid
# register aliases / global in bootstrap/app.php
```

**Code**

```php
Route::get('/path', ...)->middleware('access.key');
Route::middleware(['access.key', 'country'])->group(function () { ... });
```

**Q/A**

- **Q: Global vs route middleware?**  
  A: Global = every request. Route/alias = only routes you attach it to.
- **Q: Can I stack several?**  
  A: Yes — array of middleware, or a named group that bundles them.
- **Q: Where is alias registered?**  
  A: Laravel 11/12: `bootstrap/app.php`.

**Try:** `/middleware-demo/*` · `/middleware-group-check`

---

## I. Eloquent basics (`db-start`)

**Explain:** A Model is a PHP class that represents a database table. Eloquent lets you use `User::all()` instead of writing SQL. Configure DB in `.env`, run migrations, then fetch rows in a controller and pass them to Blade.

**Commands**

```bash
php artisan make:model Customer
php artisan migrate
php artisan model:show User
php artisan model:show customers   # inspect columns / table mapping
```

**Code**

```php
$users = User::all();
$customers = Customer::all();  // may need protected $table = 'customers';
```

**Q/A**

- **Q: Model vs table?**  
  A: Model = class. Table = DB storage. By default `Customer` → `customers`.
- **Q: Why set `$table`?**  
  A: When the real table name does not match Laravel’s plural guess.
- **Q: What does `model:show` do?**  
  A: Prints model info (table, columns, etc.) in the terminal — great for learning.

**Try:** `/user-list` · `/customer-list`

---

## J. HTTP Client & API (`httpClient-api`)

**Explain:** An API is a server endpoint that returns data (often JSON). An HTTP client is your app calling that endpoint. Laravel’s `Http` facade sends the request; you decode JSON and show it in a view.

**Code**

```php
use Illuminate\Support\Facades\Http;

$response = Http::get('https://jsonplaceholder.typicode.com/users/1');
$data = $response->json();   // or json_decode($response->body())
return view('httpRes.users', ['data' => $data]);
```

**Q/A**

- **Q: Client vs API?**  
  A: Client **sends** requests (your Laravel app). API **receives** and responds (external server).
- **Q: Why JSON?**  
  A: Common data format for APIs — easy for PHP/JS to parse.
- **Q: Facade?**  
  A: Shortcut class (`Http::get`) without manually building the client object.

**Try:** `/http-controller` · Commit `81a381a`

---

## K. Database Query Builder (`db-queryBuilder`)

**Explain:** Query Builder talks to MySQL with fluent PHP (`DB::table('users')->get()`) instead of raw SQL. Good for learning SQL concepts without a Model. Returns simple row objects (`stdClass`), not Eloquent models.

**Code**

```php
use Illuminate\Support\Facades\DB;

DB::table('users')->get();
DB::table('users')->where('id', $id)->first();
DB::table('users')->where('name', 'like', '%'.$q.'%')->get();
DB::table('users')->insert([...]);
DB::table('users')->where('id', $id)->update([...]);
DB::table('users')->where('id', $id)->delete();
```

**Q/A**

- **Q: Query Builder vs Eloquent?**  
  A: QB = `DB::table` (no model). Eloquent = `User::all()` (model objects + relations).
- **Q: What does `where(...)->get()` return?**  
  A: A collection/list of matching rows.
- **Q: When use `first()`?**  
  A: When you expect **one** row (or null), not a list.

**Try:** `/db-query-builder/all` · `/db-query-builder/byid/1` · Commit `2e31b24`

---

## L. Eloquent Query Builder (`eloquent-queryBuilder`)

**Explain:** Same CRUD idea as Query Builder, but through the `Student` model. You get model instances, mass assignment via `$fillable`, and cleaner app code (`create`, `update`, `delete` on models).

**Commands**

```bash
php artisan make:model Student -m   # model + migration
php artisan migrate
```

**Code**

```php
Student::all();
Student::find($id);                   // one model or null
Student::where('name', $name)->get(); // Collection
Student::create([...]);               // needs $fillable
$student->update([...]);
$student->delete();
```

**Q/A**

- **Q: Why `$fillable`?**  
  A: Protects against mass-assignment attacks — only listed columns can be set via `create([...])`.
- **Q: `find` vs `where()->get`?**  
  A: `find` = one by primary key. `where()->get` = list. Do not wrap a Collection again in `[...]`.
- **Q: What is `->with('success', ...)`?**  
  A: Flash message for the next request after redirect.

**Try:** `/elqQueryBuilder/studentList` · Commit `614bbd6`

---

## M. Route HTTP Methods (`route-methods`)

**Explain:** HTTP methods describe the intent: GET read, POST create, PUT replace, PATCH partial update, DELETE remove. Browsers only send GET/POST in forms, so Laravel uses `@method('PUT')` to spoof other verbs.

**Code**

```php
Route::get(...); Route::post(...); Route::put(...);
Route::patch(...); Route::delete(...);
Route::match(['get','post'], '/path', ...);
Route::any('/path', ...);
```

```blade
@csrf
@method('PUT')
```

**Q/A**

- **Q: PUT vs PATCH?**  
  A: PUT ≈ replace whole resource. PATCH ≈ change a few fields.
- **Q: Why `@method`?**  
  A: HTML forms cannot send PUT/PATCH/DELETE natively; Laravel reads `_method`.
- **Q: When use `match` / `any`?**  
  A: Same URL for show+submit (`match`), or debugging catch-all (`any`).

**Try:** `/allroute` · Commit `32dbb5d`

---

## N. Request Class Methods (`allRequestMethods`)

**Explain:** `Illuminate\Http\Request` is the object that holds everything the browser sent: method, input fields, query string, headers, IP, files. Type-hint `Request $request` and Laravel injects it (dependency injection).

**Code**

```php
$request->method();
$request->all();
$request->input('name');
$request->query('q');      // from ?q=
$request->has('email');
$request->filled('email');
$request->only(['name','email']);
$request->header('User-Agent');
$request->ip();
$request->file('photo');
```

**Q/A**

- **Q: `input` vs `query`?**  
  A: `input` = body/form (and often query too). `query` = only `?key=` URL params.
- **Q: `has` vs `filled`?**  
  A: `has` = key exists. `filled` = exists and not empty.
- **Q: Do I `new Request()`?**  
  A: No — Laravel creates and injects it into the method.

**Try:** `/request-methods` · Commit `36c992e`

---

## O. Session & Flash (`Sessions`)

**Explain:** HTTP is stateless — each request is separate. A session stores data on the **server** between pages (login email, cart). The browser only keeps a small cookie ID. **Flash** data lives for one next request (success messages after redirect).

**Code**

```php
Session::put('email', $email);
Session::get('email');
Session::forget('email');
Session::flush();                    // clear all (logout)
Session::flash('success', 'Saved!');
return redirect(...)->with('success', 'Saved!');
```

**Q/A**

- **Q: Session vs cookie?**  
  A: Cookie = small data in browser. Session = data on server, keyed by cookie ID.
- **Q: What is flash?**  
  A: Temporary message for the **next** page only, then auto-removed.
- **Q: `now` / `keep` / `reflash`?**  
  A: `now` = this request only. `keep`/`reflash` = extend flash one more request.
- **Q: File vs database driver?**  
  A: File is fine for learning. Database/Redis when many servers must share sessions.

**Try:** `/sessions` · `/sessions/login` · `/sessions/flash-demo` · Commit `ea87a34`

---

## P. File Uploads & Storage (`upload-files`)

**Explain:** Uploads need a special form (`enctype="multipart/form-data"`), validation (type/size), then save with `store()` / `storeAs()`. Public files go under `storage/app/public` and need `storage:link` so the browser can open `/storage/...`.

**Code**

```blade
<form method="post" enctype="multipart/form-data" action="...">
    @csrf
    <input type="file" name="image">
</form>
```

```php
$request->validate(['image' => 'required|file|mimes:jpg,png|max:5120']);
$path = $request->file('image')->store('files', 'public'); // random name
Storage::disk('public')->url($path);
```

**Commands**

```bash
php artisan storage:link
# public/storage → storage/app/public  (required for public URLs)
```

**Q/A**

- **Q: Why `enctype`?**  
  A: Without it, the file never arrives on the server.
- **Q: `store` vs `storeAs`?**  
  A: `store` = random safe name. `storeAs` = you choose the filename.
- **Q: Why `storage:link`?**  
  A: Web root is `public/`. Link exposes `storage/app/public` as `/storage/...`.

**Try:** `/upload-files` · Commit `10fb3a6`

---

## Q. Localization (`localization`)

**Explain:** Localization shows the same UI in different languages. You pick a locale (`en`, `ur`), middleware calls `App::setLocale()`, and `__('key')` loads text from `lang/{locale}/...`.

**Code**

```php
public function welcome(string $locale)
{
    return view('localization.welcome', [
        'locale' => session('locale', $locale),
    ]);
}
// SetLocale middleware: App::setLocale(...) from session / route
```

**Q/A**

- **Q: Where do translations live?**  
  A: Usually `lang/en/...`, `lang/ur/...` files.
- **Q: Why middleware?**  
  A: So every request after the user chooses a language keeps that locale.
- **Q: How to switch language?**  
  A: URL param, session value, or later browser/IP detection.

**Try:** `/localization/welcome/en` · Commit `a0c2e70`

---

## R. Pagination (`pagination`)

**Explain:** Pagination loads only one “page” of rows (e.g. 10 students) instead of the whole table. Laravel reads `?page=` from the URL and Blade `links()` builds page numbers.

**Code**

```php
$students = Student::paginate(10);
return view('...', compact('students'));
```

```blade
@foreach($students as $student) ... @endforeach
{{ $students->links() }}
```

**Q/A**

- **Q: Why not `all()` for big tables?**  
  A: Loads everything into memory — slow. `paginate` uses LIMIT/OFFSET.
- **Q: `paginate` vs `simplePaginate`?**  
  A: Full = numbered pages + total count. Simple = Prev/Next only (cheaper).
- **Q: Who sets the page number?**  
  A: Query string `?page=2` — Laravel reads it automatically.

**Try:** `/paginate/list` · `/paginate/list?page=2` · Commit `ffccd21`

---

## S. Layout + CSS + JS (`LayoutCssJs`)

**Explain:** One master layout holds shared nav, CSS, and JS. Child pages `@extends` the layout and only fill `@section('content')`. `asset()` builds correct public URLs for CSS/JS files.

**Code**

```blade
{{-- layout --}}
<link href="{{ asset('css/layout-demo/app.css') }}">
@yield('content')
<script src="{{ asset('js/layout-demo/app.js') }}"></script>

{{-- child --}}
@extends('layout-demo.layout')
@section('content')
  <h1>Home</h1>
@endsection
```

**Q/A**

- **Q: Why one layout?**  
  A: Change header/footer/CSS once — all pages update.
- **Q: What does `asset()` do?**  
  A: Builds a URL under `public/` (works with your app URL).
- **Q: Can layout pages be dynamic?**  
  A: Yes — controller passes `$user` / `$stats` into the child view (dashboard demo).

**Try:** `/layout-demo` · `/layout-demo/dashboard` · Commit `99962c1`

---

## T. Migrations (`migrations`)

**Explain:** Migrations are versioned PHP files that create or change tables. `up()` applies a change; `down()` undoes it for rollback. This lets teams share the same DB structure with Artisan commands instead of manual SQL.

**Code**

```php
public function up(): void
{
    Schema::create('test', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::dropIfExists('test');
}

// Alter existing table:
Schema::table('test', function (Blueprint $table) {
    $table->string('username');          // up
    // $table->dropColumn('username');   // down
});
```

**Commands**

```bash
php artisan make:migration create_test_table
php artisan make:migration add_username_to_test_table --table=test
php artisan migrate                  # run pending up()
php artisan migrate:rollback         # run last batch down()
php artisan migrate:rollback --step=1
php artisan migrate:status
php artisan migrate:fresh            # drop all + migrate (dev only)
```

**Q/A**

- **Q: Why `down()` matters?**  
  A: Rollback must undo `up()`. If `down()` is empty/wrong, DB and migration history get out of sync.
- **Q: `create` vs `table`?**  
  A: `Schema::create` = new table. `Schema::table` = alter existing (add/drop columns).
- **Q: `migrate:fresh` vs `rollback`?**  
  A: Rollback undoes last batch(es). Fresh drops **all** tables then migrates — destructive, learning/dev only.
- **Q: Where to learn commands in this app?**  
  A: Visit `/migration` — `resources/views/migration/explain.blade.php`.

**Try:** `/migration` · Commits `a96c6e0` · `f0954fd`

---

## New Routes Quick List

| Area | Example URLs |
|------|----------------|
| HTTP Client | `/http-controller` |
| Query Builder | `/db-query-builder/all`, `/byid/{id}`, search/sort |
| Eloquent | `/elqQueryBuilder/studentList`, add/update/delete/search |
| Route methods | `/allroute` |
| Request methods | `/request-methods` |
| Sessions | `/sessions`, login, profile, flash demos |
| Uploads | `/upload-files` |
| Localization | `/localization/welcome/{locale}` |
| Pagination | `/paginate/list?page=2` |
| Layout demo | `/layout-demo`, `/dashboard`, `/flash` |
| Migrations | `/migration` |

---

## New Key Files

| File | Topic |
|------|--------|
| `httpController.php` | HTTP client / API |
| `DBQueryController.php` | Query Builder |
| `ElqQueryBuilder.php` + `Student.php` | Eloquent CRUD |
| `AllrouteController.php` | HTTP methods |
| `RequestMethodsController.php` | Request API |
| `SessionsController.php` | Session & flash |
| `UploadFileController.php` | Uploads / Storage |
| `LocalizationController.php` + SetLocale | Multi-language |
| `PaginationController.php` | `paginate()` |
| `LayoutDemoController.php` | Layout + assets |
| `database/migrations/*` | Schema changes |
| `resources/views/migration/explain.blade.php` | Migration Q/A page |

---

## Learning Path (Commit History)

Topics are listed in the order they were added to this repo.

### 1. Project Setup & Views

| Commit                | Topic                                                  |
| --------------------- | ------------------------------------------------------ |
| `f6abb0f`             | Initial Laravel project                                |
| `be7a2ee`             | First Blade view — `resources/views/welcome.blade.php` |
| `c078891` / `2332d65` | README cheatsheet (PHP symbols, routing, Blade, etc.)  |

### 2. Routing Basics — branch `Routes-handling`

| Commit    | Topic                                                                |
| --------- | -------------------------------------------------------------------- |
| `1272cf7` | Home and test routes — `Route::get()` vs `Route::view()`             |
| `777765f` | Pass data to views via route parameters                              |
| `50c99e9` | Navigate between routes using anchor tags in views                   |
| `3754410` | Redirect routes — `Route::redirect()`                                |
| `5bd60bb` | HTTP methods — GET, POST, PUT, DELETE, PATCH, match, any             |
| `23dc77e` | PHP file rules (`<?php` at line 1, no closing `?>`) + route comments |

### 3. Controllers — branch `controllers`

| Commit    | Topic                                                                  |
| --------- | ---------------------------------------------------------------------- |
| `872d3b6` | Create `UserController` — `php artisan make:controller UserController` |
| `83d1e9a` | Dynamic route parameters — `/dynamicUser-controller/{name}`            |
| `2174cd6` | Return views from controller with dynamic data                         |
| `c4ea075` | Nested view folders — `view('admin.login')` → `admin/login.blade.php`  |

### 4. Blade Templates — branch `views`

| Commit    | Topic                                                          |
| --------- | -------------------------------------------------------------- |
| `c64c447` | Blade basics, layout design, `::` static access operator       |
| `87188d8` | Variables, arrays, `@if`, `@foreach` directives                |
| `37b09c8` | Conditional rendering with `@if`                               |
| `c029ec0` | Sub-views with `@include`                                      |
| `ad45b27` | Pass data into included sub-views                              |
| `4c52cfb` | Conditional `@include` with `@if`                              |
| `4ab01fe` | Blade components — `<x-messagebanner>` with dynamic type/style |

**Learning routes:** `/learn/blade-basics`, `/learn/blade-loops-data`, `/learn/interview-checklist`

### 5. Forms & Request Handling — branches `postforminput-1`, `postforminput-2`

| Commit    | Topic                                                     |
| --------- | --------------------------------------------------------- |
| `096b857` | User form — GET form page + POST handler (`addUser`)      |
| `0888232` | CSRF token (`@csrf`) — causes of 419 errors, Postman tips |
| `dd0b541` | Checkbox, radio, select inputs — `storeUserAttributes`    |

### 6. Form Validation — branches `formValidation`, `validation_customErrors`, `ownCustom-rules`

| Commit    | Topic                                                           |
| --------- | --------------------------------------------------------------- |
| `47c7eea` | `$request->validate()` — rules for name, email, password        |
| `21434a5` | Display errors — `$errors->any()`, `@error`, `@enderror`        |
| `112dce9` | Custom validation messages in controller                        |
| `98c2917` | Retain input on error — `old('field')`                          |
| `8a9d23e` | Dynamic error CSS class — `$errors->has('name')`                |
| `83dd2cb` | Global messages — `lang/en/validation.php`                      |
| `5fd9606` | Custom rule class — `app/Rules/ageLimit.php`                    |
| `ea32367` | Register rule via `Validator::extend()` in `AppServiceProvider` |
| `bfbc6d2` | Detailed comments on Service Provider, `extends`, `boot()`      |
| `a1d2a40` | README learning docs (project overview + cheatsheet append)     |

### 7. URL Generation — branch `getting-url`

| Commit    | Topic                                                     |
| --------- | --------------------------------------------------------- |
| `5d9f7e5` | Generate / inspect URLs in browser — `/url-check/*` pages |

### 8. Named Routes — branch `named-routed`

| Commit    | Topic                                                               |
| --------- | ------------------------------------------------------------------- |
| `4d42623` | Named route for user profile (`->name('user')`)                     |
| `b885d0e` | Short alias `pf` for long profile path                              |
| `5a3f2f8` | `route('pf')` helper usage in Blade                                 |
| `86156dc` | Product routes + `to_route()` helper after save                     |
| `5a4f438` | Find product by name — named routes `product.find` / `product.show` |

### 9. Route Grouping — branch `route-grouping`

| Commit    | Topic                                                                   |
| --------- | ----------------------------------------------------------------------- |
| `8457471` | Admin prefix group — `/admin/users`, `/admin/dashboard`                 |
| `eba3ba4` | Controller grouping — `StudentController` show/edit/delete/create/about |

### 10. Middleware — branch `Middleware`

| Commit    | Topic                                                       |
| --------- | ----------------------------------------------------------- |
| `c34b5e1` | Global middleware registered in `bootstrap/app.php`         |
| `f288c42` | Access-key middleware alias + conditional route demo        |
| `40657d7` | Group multiple middleware and apply as one stack on a route |
| `cd7e11e` | Extra comments clarifying middleware flow                   |

**Key middleware files:** `globalMid.php`, `EnsureAccessKey.php`, `countryCheck.php`  
**Demo routes:** `/middleware-demo/*`, `/middleware-group-check`, `/multiple-middleware-to-route`

### 11. Database & Eloquent — branch `db-start` (current `main`)

| Commit    | Topic                                                                         |
| --------- | ----------------------------------------------------------------------------- |
| `a5c115f` | DB + session setup in `.env.example`, migrate, seed user, `User_dbController` |
| `901f466` | Show users table in UI — `/user-list`                                         |
| `a5a5c12` | `Customer` Eloquent model + `Customer::all()` — `/customer-list`              |
| `01c8a08` | Explicit `$table` on Customer model                                           |
| `f8e828b` | Custom model method invoked from `Customer_dbController`                      |
| `7eb6315` | Inspect model — `php artisan model:show customers`                            |

### 12–22. Advanced topics (after `db-start`) — see **Detailed Code Lessons** above

| Branch | Tip | Topic |
|--------|-----|-------|
| `httpClient-api` | `81a381a` | HTTP Client & API |
| `db-queryBuilder` | `2e31b24` | Database Query Builder |
| `eloquent-queryBuilder` | `614bbd6` | Eloquent Query Builder (Student) |
| `route-methods` | `32dbb5d` | All HTTP route methods |
| `allRequestMethods` | `36c992e` | Request class methods |
| `Sessions` | `ea87a34` | Session & flash |
| `upload-files` | `10fb3a6` | File upload & Storage |
| `localization` | `a0c2e70` | Localization |
| `pagination` | `ffccd21` | Pagination |
| `LayoutCssJs` | `99962c1` | Layout + CSS/JS |
| `migrations` | `f0954fd` | Migrations create/alter/rollback |

---

## Routes Reference

### Core learning routes

| URL                              | Method | Handler                              | Purpose                       |
| -------------------------------- | ------ | ------------------------------------ | ----------------------------- |
| `/`                              | GET    | closure                              | Welcome page                  |
| `/home`                          | GET    | closure                              | Home view                     |
| `/test`                          | GET    | closure / `Route::view`              | Test view                     |
| `/user/{name}`                   | GET    | closure                              | Pass name to `user` view      |
| `/check/redirect`                | GET    | redirect                             | Redirect to `/`               |
| `/user-controller`               | GET    | `UserController@getUser`             | Return string from controller |
| `/dynamicUser-controller/{name}` | GET    | `UserController@getDynamicUser`      | Dynamic URL parameter         |
| `/viewbycontroller/{name}`       | GET    | `UserController@getViewByController` | View from controller          |
| `/viewArraydata`                 | GET    | `UserController@getViewArrayData`    | Blade `@foreach` demo         |
| `/admin/login`                   | GET    | `UserController@getAdminLogin`       | Nested folder view            |
| `/dashboard`                     | GET    | closure (named)                      | Named route `dashboard`       |
| `/search?q=&page=`               | GET    | closure                              | Query string demo             |
| `/hello/{name}`                  | GET    | closure                              | Path parameter demo           |
| `/learn/blade-basics`            | GET    | closure                              | Blade basics lesson           |
| `/learn/blade-loops-data`        | GET    | closure                              | Loops and data lesson         |
| `/learn/interview-checklist`     | GET    | closure                              | Interview checklist           |
| `/mainview`                      | GET    | closure                              | Layout + `@include` demo      |
| `/user-form`                     | GET    | closure                              | User registration form        |
| `/addUser`                       | POST   | `UserController@addUser`             | Process user form             |
| `/user-attributes-form`          | GET    | closure                              | Checkbox/radio form           |
| `/store-user-attributes`         | POST   | `UserController@storeUserAttributes` | Process attributes form       |
| `/form-validation`               | GET    | closure                              | Validation form               |
| `/validate-form`                 | POST   | `UserController@validateForm`        | Validate and show errors      |

### URL check, named routes, grouping, middleware, DB

| URL                                | Method   | Handler                              | Purpose                       |
| ---------------------------------- | -------- | ------------------------------------ | ----------------------------- |
| `/url-check/landing`               | GET      | closure                              | URL generation demo           |
| `/url-check/about`                 | GET      | closure                              | URL generation demo           |
| `/url-check/products`              | GET      | closure                              | URL generation demo           |
| `/named-route/profile/details/id`  | GET      | closure (`pf`)                       | Named route short alias       |
| `/named-route/product/details/id`  | GET      | `getProduct` (`product`)             | Product page                  |
| `/named-route/product/add`         | GET      | `showAddProductForm` (`product.add`) | Add product form              |
| `/named-route/product/save`        | POST     | `saveProduct` (`product.save`)       | Save + `to_route()`           |
| `/named-route/product/find`        | GET/POST | find methods                         | Find product by name          |
| `/named-route/product/show/{name}` | GET      | `showProductByName`                  | Show found product            |
| `/admin/users`                     | GET      | group closure                        | Prefix group demo             |
| `/admin/moderators`                | GET      | group closure                        | Prefix group demo             |
| `/admin/dashboard`                 | GET      | group closure                        | Prefix group demo             |
| `/student/show` etc.               | GET      | `StudentController`                  | Controller route group        |
| `/middleware-demo/*`               | GET      | middleware demos                     | Access-key / country checks   |
| `/middleware-group-check`          | GET      | grouped middleware                   | Multiple middleware as one    |
| `/multiple-middleware-to-route`    | GET      | multi middleware                     | Stack middleware on one route |
| `/user-db`                         | GET      | `User_dbController@user_db`          | DB user fetch demo            |
| `/user-list`                       | GET      | `User_dbController@userList`         | Users table UI                |
| `/customer-list`                   | GET      | `Customer_dbController@customerList` | Customers via Eloquent        |

---

## Key Files

| File                                                   | What it teaches                                      |
| ------------------------------------------------------ | ---------------------------------------------------- |
| `routes/web.php`                                       | All routes with inline learning comments             |
| `app/Http/Controllers/UserController.php`              | Controllers, forms, validation, named-route products |
| `app/Http/Controllers/StudentController.php`           | Controller-based route grouping                      |
| `app/Http/Controllers/User_dbController.php`           | Fetch users from database                            |
| `app/Http/Controllers/Customer_dbController.php`       | Eloquent customers list                              |
| `app/Models/User.php`                                  | Default Eloquent user model                          |
| `app/Models/Customer.php`                              | Custom model, `$table`, custom `show()` method       |
| `app/Http/Middleware/globalMid.php`                    | Global middleware                                    |
| `app/Http/Middleware/EnsureAccessKey.php`              | Access-key / alias middleware                        |
| `app/Http/Middleware/countryCheck.php`                 | Country check middleware                             |
| `bootstrap/app.php`                                    | Register global + middleware aliases                 |
| `app/Providers/AppServiceProvider.php`                 | `Validator::extend()` for custom rules               |
| `app/Rules/ageLimit.php`                               | Custom `ValidationRule` class (age 18–100)           |
| `lang/en/validation.php`                               | Customize default Laravel error messages             |
| `resources/views/validation/form-validation.blade.php` | `old()`, `@error`, `$errors->has()`                  |
| `resources/views/user-form.blade.php`                  | CSRF, form fields, POST submission                   |
| `resources/views/user-attributesForm.blade.php`        | Checkbox, radio, select inputs                       |
| `resources/views/database/users.blade.php`             | Users list from DB                                   |
| `resources/views/database/customers.blade.php`         | Customers list from Eloquent                         |
| `resources/views/learn/*.blade.php`                    | Structured Blade lessons                             |
| `resources/views/components/messagebanner.blade.php`   | Reusable Blade component                             |

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
php artisan serve
php artisan route:list
php artisan make:controller Name
php artisan make:model Name -m
php artisan make:middleware Name
php artisan make:rule ageLimit
php artisan make:migration create_test_table
php artisan make:migration add_username_to_test_table --table=test
php artisan migrate
php artisan migrate:rollback
php artisan migrate:status
php artisan migrate:fresh
php artisan model:show User
php artisan model:show customers
php artisan storage:link
```

---

## Full Commit Log (newest → oldest)

| Hash      | Date       | Message (short)                               |
| --------- | ---------- | --------------------------------------------- |
| `7eb6315` | 2026-07-14 | `model:show` explanation in Customer model    |
| `f8e828b` | 2026-07-14 | Model method invoked from Customer controller |
| `01c8a08` | 2026-07-14 | Explicit `$table` on Customer model           |
| `a5a5c12` | 2026-07-14 | Customer Eloquent model + `/customer-list`    |
| `901f466` | 2026-07-14 | User list UI — `/user-list`                   |
| `a5c115f` | 2026-07-13 | DB setup, migrate, User_dbController          |
| `cd7e11e` | 2026-07-13 | Middleware comments                           |
| `40657d7` | 2026-07-13 | Grouped middleware on route                   |
| `f288c42` | 2026-07-13 | Access-key middleware + demos                 |
| `c34b5e1` | 2026-07-13 | Global middleware in app.php                  |
| `eba3ba4` | 2026-07-13 | StudentController route grouping              |
| `8457471` | 2026-07-13 | Admin route prefix grouping                   |
| `5a4f438` | 2026-07-13 | Product find by name (named routes)           |
| `86156dc` | 2026-07-13 | Product CRUD routes + `to_route()`            |
| `5a3f2f8` | 2026-07-13 | `route('pf')` comment clarity                 |
| `b885d0e` | 2026-07-11 | Short named route `pf`                        |
| `4d42623` | 2026-07-11 | Named profile route                           |
| `5d9f7e5` | 2026-07-11 | URL generation / URL check pages              |
| `a1d2a40` | 2026-07-09 | README project learning docs                  |
| `bfbc6d2` | 2026-07-09 | AppServiceProvider custom-rule comments       |
| `ea32367` | 2026-07-09 | Fix ageLimit Validator::extend errors         |
| `5fd9606` | 2026-07-09 | ageLimit rule + form age field                |
| `83dd2cb` | 2026-07-09 | Custom lang validation messages               |
| `8a9d23e` | 2026-07-09 | `old()` + error CSS class comments            |
| `98c2917` | 2026-07-09 | Retain form input with `old()`                |
| `112dce9` | 2026-07-09 | Custom controller validation messages         |
| `21434a5` | 2026-07-07 | Display validation errors in Blade            |
| `47c7eea` | 2026-07-07 | Form validation routes + method               |
| `dd0b541` | 2026-07-07 | Checkbox / radio / select attributes          |
| `0888232` | 2026-07-07 | CSRF / 419 explanations                       |
| `096b857` | 2026-07-07 | User form POST (`addUser`)                    |
| `4ab01fe` | 2026-04-14 | Blade components (message banner)             |
| `4c52cfb` | 2026-04-14 | Conditional footer `@include`                 |
| `ad45b27` | 2026-04-14 | Pass data into `@include`                     |
| `c029ec0` | 2026-04-14 | Sub-views with `@include`                     |
| `37b09c8` | 2026-04-14 | Blade `@if` condition                         |
| `87188d8` | 2026-04-14 | Blade loops / variables / directives          |
| `c64c447` | 2026-04-14 | Blade keywords + learning routes              |
| `c4ea075` | 2026-04-07 | Nested folder views via controller            |
| `2174cd6` | 2026-04-07 | Return view from controller                   |
| `83d1e9a` | 2026-04-07 | Dynamic route param in controller             |
| `23dc77e` | 2026-04-07 | PHP file `<?php` rules + route comments       |
| `872d3b6` | 2026-04-07 | Create UserController                         |
| `5bd60bb` | 2026-04-06 | Routing methods explained                     |
| `3754410` | 2026-04-06 | Redirect route                                |
| `50c99e9` | 2026-04-06 | Anchor navigation between routes              |
| `777765f` | 2026-04-06 | Pass data via route parameter                 |
| `1272cf7` | 2026-04-06 | Home / test routes + comments                 |
| `2332d65` | 2026-04-06 | README Laravel cheatsheet                     |
| `be7a2ee` | 2026-04-06 | Welcome Blade HTML                            |
| `c078891` | 2026-04-04 | Early README update                           |
| `f6abb0f` | 2026-04-04 | First commit                                  |
