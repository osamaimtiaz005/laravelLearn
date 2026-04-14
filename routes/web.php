<?php
/*<?php  this is php code file where we can write php code for backend in Laravel Framework we call this php code file*/

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use League\Flysystem\UrlGeneration\PrefixPublicUrlGenerator;

/*Import Laravel’s core classes to handle requests and define routes easily
🔹 1. What is Route?

👉 Route is a class

A class = blueprint / template for something

Think like this:
class Route {
   // functions inside
}

👉 Laravel already created this class for you

🔹 2. What is :: (double colon)?

👉 This is called:

Scope Resolution Operator

(Simple meaning 👇)

“Call something directly from a class (without creating object)”

Example:
Route::get()

👉 Means:

Call get() method from Route class

*/

/*
What is this function () {}?

👉 This is called:

Anonymous Function (Closure)

Meaning:

A function without a name

function () {
    return "Hello";
}

👉 No name, just logic
*/

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
| Route::get      — register a route for HTTP GET (browser address bar, links).
| '/'            — path: site root (nothing after the domain).
| function ()    — closure = code that runs when this URL + method match.
| return view()  — response: render a Blade template from resources/views.
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
| Route::get      — register a route for HTTP GET (browser address bar, links).
| '/home'        — path: home page (nothing after the domain).
| function ()    — closure = code that runs when this URL + method match.
| return view()  — response: render a Blade template from resources/views.
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('home');
});
Route::get('/test', function () {
    return view('test');
});



Route::view('/test', 'test');

/* we can return the view file without using function and return view() by using Route::view()
Route::view($uri, $view, $data = []) 
$uri is the path of the view file
$view is the name of the view file
$data is the data that we want to pass to the view file

but Use Route::get()  when we need logic and to fetch data from the database or
we need to use the controller to handle the request

Route::get($uri, $callback);
$uri is the path of the view file
$callback is the function that we want to execute when the user visits the URL
this callback can be a function or a closure or a controller method
Option 1: Closure (function)
Route::get('/users', function () {
    return "Hello";
});
✅ Option 2: Controller
Route::get('/users', [UserController::class, 'index']);

👉 Calls:

UserController -> index()


Example where get is needed

Route::get('/users', function () {
    $users = ['Ali', 'John'];
    return view('users', compact('users'));
});

👉 You cannot do this with Route::view() ❌
    */


/*--------------------------------
Route can have multiple methods
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::delete($uri, $callback);
Route::patch($uri, $callback); //Used to update PART of data
Route::options($uri, $callback);  Browser asks: “What methods are allowed here?” Used for: CORS (Cross-Origin requests) APIs
Route::match(['get', 'post'], $uri, $callback); Same route works for: GET (show form) and POST (submit form)
Route::any($uri, $callback); Same route works for all methods
*/



/*Route with Middleware
Route::get('/dashboard', function () {
    return "Dashboard";
})->middleware('auth');
*/



/*✅ Route Prefix
Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        return "Admin Users";
    });
});

👉 URL becomes:

/admin/users
*/




//  Pass Data with Routing

/*
|--------------------------------------------------------------------------
| Pass Data to the view file with Routing
|--------------------------------------------------------------------------
| '/hello/Lina' — dynamic segment in the URL, not ?query=
| {name}        — placeholder: whatever is in the URL becomes $name in the closure.
| view('user', ['name' => $name]) — pass data to the view file
| ['name' => $name] — array of data to pass to the view file
|[key => value] — key is the name of the variable and value is the value of the variable
|=> is the assignment operator
| $name — variable name to pass to the view file
| string $name — type hinting the variable name to pass to the view file
 this name is mandatory to pass to the view file 
 for optional we can use {name?} and then we can pass the value of the name in the URL
 compact() function is used to pass multiple variables to the view file it is auto packer function that packs the variables into an array
  just like ['name' => $name]
|--------------------------------------------------------------------------
*/
Route::get('/user/{name}', function (string $name) {
    return view('user', ['name' => $name]);
});

/*
|--------------------------------------------------------------------------
| Redirect Route
|--------------------------------------------------------------------------
| Route::redirect($uri, $destination);
| $uri is the path of the view file
| $destination is the path of the destination file
|--------------------------------------------------------------------------
*/
Route::redirect('/user/ali', '/');

/*
|--------------------------------------------------------------------------
| Controller Method in Route
|--------------------------------------------------------------------------
| [Class, Method] is the callable format in PHP.
This is a callable format in PHP. we can use the controller method in the route by using the class name and the method name in the array
we can use the controller method in the route by using the class name and the method name in the array
[UserController::class, 'getUser']
UserController::class is the class name
getUser is the method name
|--------------------------------------------------------------------------
*/


Route::get('/user-controller', [UserController::class, 'getUser']);

/*
|--------------------------------------------------------------------------
| Dynamic User Controller Method in Route
|--------------------------------------------------------------------------
| {name} is the dynamic user name that we pass in the URL with the help of {name} in url path and then we pass the value of the name in the method parameter
|--------------------------------------------------------------------------
*/
Route::get('/dynamicUser-controller/{name}', [UserController::class, 'getDynamicUser']);

/*
|--------------------------------------------------------------------------
| View by Controller Method in Route
|--------------------------------------------------------------------------
| {name} is the dynamic user name that we pass in the URL with the help of {name} in url path and then we pass the value of the name in the method parameter
|--------------------------------------------------------------------------
*/
Route::get('/viewbycontroller/{name}', [UserController::class, 'getViewByController']);

/*
|--------------------------------------------------------------------------
| Added myloop variable  which is a key value pair array  to fruits array to the view file to test the loop in the view file
|--------------------------------------------------------------------------
*/
Route::get('/viewArraydata', [UserController::class, 'getViewArrayData']);

/*
|--------------------------------------------------------------------------
| Admin Folder Login Page in Route
|--------------------------------------------------------------------------
| This method is used to return the admin login page
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [UserController::class, 'getAdminLogin']);
/*
|--------------------------------------------------------------------------
| Named route (custom path + name)
|--------------------------------------------------------------------------
| '/dashboard'   — the URL path users visit (e.g. http://127.0.0.1:8000/dashboard).
| ->name(...)   — "->" calls a method on the Route object Laravel just built.
| 'dashboard'    — route name: use route('dashboard') or redirect()->route('dashboard')
|                  in PHP/Blade so you can rename the URL later without hunting strings.
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return <<<'HTML'
        <h1>Dashboard</h1>
        <p>This route is <strong>named</strong> <code>dashboard</code>.</p>
        <p>In Blade you could link: <code>{{ route('dashboard') }}</code></p>
        <p><a href="/">Back home</a></p>
    HTML;
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Query string: /search?q=your+text&page=2
|--------------------------------------------------------------------------
| The path is only '/search'. Everything after "?" is the query string (not in Route::get).
|
| Symbols in URLs:
|   ?   starts query parameters
|   =   separates name and value (q=laravel)
|   &   separates pairs (q=laravel&page=2)
|
| Request $request — Laravel injects the current HTTP request into the closure.
| $request->query('q')     — read parameter "q"; null if missing.
| $request->query('page', 1) — read "page", or default 1 if absent.
| rawurlencode($q) — safe for putting search text inside a URL (href), unlike e() which is for HTML body.
|--------------------------------------------------------------------------
*/
Route::get('/search', function (Request $request) {
    $q = $request->query('q');
    $page = (int) $request->query('page', 1);

    if ($q === null || $q === '') {
        return <<<'HTML'
            <h1>Search</h1>
            <p>Add a query, e.g. <code>/search?q=laravel&amp;page=1</code></p>
            <p><a href="/">Back home</a> · <a href="/dashboard">Dashboard</a></p>
        HTML;
    }

    $safeQ = e($q);
    $qForUrl = rawurlencode($q);
    $prevPage = max(1, $page - 1);
    $nextPage = $page + 1;

    return <<<HTML
        <h1>Search results</h1>
        <p>Query (<code>q</code>): <strong>{$safeQ}</strong></p>
        <p>Page (<code>page</code>): <strong>{$page}</strong></p>
        <p><a href="/search?q={$qForUrl}&page={$prevPage}">Previous page</a>
           · <a href="/search?q={$qForUrl}&page={$nextPage}">Next page</a></p>
        <p><a href="/">Back home</a> · <a href="/dashboard">Dashboard</a></p>
    HTML;
});

/*
|--------------------------------------------------------------------------
| Path parameter: /hello/Lina  (dynamic segment in the URL, not ?query=)
|--------------------------------------------------------------------------
| {name}     — placeholder: whatever is in the URL becomes $name in the closure.
| e($name)   — escape output for HTML (security when showing user-controlled text).
|--------------------------------------------------------------------------
*/
Route::get('/hello/{name}', function (string $name) {
    return '<h1>Hello, ' . e($name) . '</h1><p><a href="/">Back home</a></p>';
})->name('hello');

/*
|--------------------------------------------------------------------------
| Learning views (Blade demos + comments in resources/views/learn/*.blade.php)
|--------------------------------------------------------------------------
| Route::prefix('learn')
|   — Registers a URI prefix: every route inside the group gets /learn/ in front
|     (e.g. GET /learn/blade-basics).
|
| ->group(function () { ... })
|   — Closes over several routes so shared attributes (prefix, middleware) stay DRY.
|
| Route::get($path, $callback)
|   — Binds HTTP GET: browser visit or link; $callback runs when path + method match.
|
| return view('learn.blade-basics', [ ... ])
|   — view() helper: first argument is the view name (dot = directory under resources/views).
|   — Second argument: associative array; keys become $variable names inside the Blade file.
|
| Flow tied to layouts: view() renders the named Blade template; if that template
| uses @extends('learn.layout'), Laravel composes child sections into the layout
| before returning the final HTML response.
|--------------------------------------------------------------------------
*/
Route::prefix('learn')->group(function () {
    Route::get('/blade-basics', function () {
        return view('learn.blade-basics', [
            'user' => ['name' => 'Amina', 'role' => 'student'],
        ]);
    });

    Route::get('/blade-loops-data', function () {
        return view('learn.blade-loops-data', [
            'tags' => ['Laravel', 'PHP', 'Blade'],
            'orders' => [],
        ]);
    });

    Route::get('/interview-checklist', function () {
        return view('learn.interview-checklist');
    });
});

/*
|--------------------------------------------------------------------------
| Main View
|--------------------------------------------------------------------------
| This method is used to return the main view
| @include is used to include the sub view file in the main view file
| @include('partial.headerSubView')
|--------------------------------------------------------------------------
*/
Route::get('/mainview', function () {
    return view('mainview');
});
