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
