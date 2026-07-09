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

A hands-on Laravel 12 learning repository. Each commit adds a small, commented example so you can follow routing, Blade, controllers, forms, and validation step by step.

**Stack:** PHP 8.2+, Laravel 12, Blade, XAMPP-friendly local setup

---

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Run migrations (optional — needed for email unique validation)
php artisan migrate

# Start the dev server
php artisan serve
```

Open `http://127.0.0.1:8000` in your browser. Always use the same host for browsing and form submissions (see CSRF notes below).

---

## Learning Path (Commit History)

Topics are listed in the order they were added to this repo.

### 1. Project Setup & Views
| Commit | Topic |
|--------|-------|
| `f6abb0f` | Initial Laravel project |
| `be7a2ee` | First Blade view — `resources/views/welcome.blade.php` |
| `c078891` / `2332d65` | README cheatsheet (PHP symbols, routing, Blade, etc.) |

### 2. Routing Basics
| Commit | Topic |
|--------|-------|
| `1272cf7` | Home and test routes — `Route::get()` vs `Route::view()` |
| `777765f` | Pass data to views via route parameters |
| `50c99e9` | Navigate between routes using anchor tags in views |
| `3754410` | Redirect routes — `Route::redirect()` |
| `5bd60bb` | HTTP methods — GET, POST, PUT, DELETE, PATCH, match, any |
| `23dc77e` | PHP file rules (`<?php` at line 1, no closing `?>`) + route comments |

### 3. Controllers
| Commit | Topic |
|--------|-------|
| `872d3b6` | Create `UserController` — `php artisan make:controller UserController` |
| `83d1e9a` | Dynamic route parameters — `/dynamicUser-controller/{name}` |
| `2174cd6` | Return views from controller with dynamic data |
| `c4ea075` | Nested view folders — `view('admin.login')` → `admin/login.blade.php` |

### 4. Blade Templates
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

### 5. Forms & Request Handling
| Commit | Topic |
|--------|-------|
| `096b857` | User form — GET form page + POST handler (`addUser`) |
| `0888232` | CSRF token (`@csrf`) — causes of 419 errors, Postman tips |
| `dd0b541` | Checkbox, radio, select inputs — `storeUserAttributes` |

### 6. Form Validation
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

---

## Routes Reference

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

---

## Key Files

| File | What it teaches |
|------|-----------------|
| `routes/web.php` | All routes with inline learning comments |
| `app/Http/Controllers/UserController.php` | Controller methods, request input, validation |
| `app/Providers/AppServiceProvider.php` | `Validator::extend()` for custom rules |
| `app/Rules/ageLimit.php` | Custom `ValidationRule` class (age 18–100) |
| `lang/en/validation.php` | Customize default Laravel error messages |
| `resources/views/validation/form-validation.blade.php` | `old()`, `@error`, `$errors->has()` |
| `resources/views/user-form.blade.php` | CSRF, form fields, POST submission |
| `resources/views/user-attributesForm.blade.php` | Checkbox, radio, select inputs |
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
php artisan serve          # Start dev server
php artisan route:list     # List all routes
php artisan make:controller UserController
php artisan make:model User
php artisan migrate
```
