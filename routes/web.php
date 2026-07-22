<?php
/*<?php  this is php code file where we can write php code for backend in Laravel Framework we call this php code file*/

use App\Http\Controllers\AccessorController;
use App\Http\Controllers\AllrouteController;
use App\Http\Controllers\Customer_dbController;
use App\Http\Controllers\DBQueryController;
use App\Http\Controllers\ElqQueryBuilder;
use App\Http\Controllers\httpController;
use App\Http\Controllers\RequestMethodsController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LayoutDemoController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MutatorController;
use App\Http\Controllers\OnetoManyController;
use App\Http\Controllers\ManyToOneController;
use App\Http\Controllers\OnetoOneController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User_dbController;
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


// In Laravel, "shadowed" means that there is a more general or matching route defined *before* your redirect route,
// causing the framework to match the earlier route and ignore (or never reach) the redirect.
//
// In your case:
// - Route::get('/user/{name}', ...) matches *any* /user/something route, including /user/ali.
// - Route::redirect('/user/ali', '/') is defined *after* that, so it will never be reached.
// - The first route that matches the requested URL is picked, and the rest are ignored ("shadowed").
//
// So, Route::redirect('/user/ali', '/') is shadowed by the Route::get('/user/{name}', ...) route above it.
//
// "Shadowed" in this context means the route exists, but will never be used because a matching route above it
// matches all the requests it would otherwise receive.

/*
|--------------------------------------------------------------------------
| Redirect Route
|--------------------------------------------------------------------------
| Route::redirect($uri, $destination);
| $uri is the path of the view file
| $destination is the path of the destination file
|--------------------------------------------------------------------------
*/
Route::redirect('/check/redirect', '/');

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

/*
|--------------------------------------------------------------------------
| User Form Route
|--------------------------------------------------------------------------
| This method is used to return the user form view file
|--------------------------------------------------------------------------
*/
Route::get('/user-form', function () {
    return view('user-form');
});
/*
|--------------------------------------------------------------------------
| Add User Route
|--------------------------------------------------------------------------
| This method is used to add a user by using the post method
|--------------------------------------------------------------------------
*/
Route::post('/addUser', [UserController::class, 'addUser']);

Route::get('/user-attributes-form', function () {
    return view('user-attributesForm');
});
Route::post('/store-user-attributes', [UserController::class, 'storeUserAttributes']);

Route::get('/form-validation', function () {
    return view('validation.form-validation');
});
Route::post('/validate-form', [UserController::class, 'validateForm']);


Route::get('/url-check/landing', function () {
    return view('url-check.landing');
});
Route::get('/url-check/about', function () {
    return view('url-check.about');
});
Route::get('/url-check/products', function () {
    return view('url-check.products');
});
// Named Route
// We define a named route called 'user' that maps the URL '/named-route/profile/details/id'
// to a controller or closure that returns the 'named-route.profile' view.
// Using the 'name' method on the route assigns the alias 'user' to this route.
// In Blade templates, you can now generate URLs to this route using route('user').
// This improves maintainability: if the URL changes, you only update it here, not all view files!
// Example usage in Blade view: <a href="{{ route('user') }}">Profile Page</a>

Route::view('/named-route/welcome', 'named-route.welcome')->name('named-route.welcome');

Route::get('/named-route/profile/details/id', function () {
    return view('named-route.profile');
})->name('pf');

// Named Route with Controller Method
// URL:  /named-route/product/details/id
// Name: 'product'
//
// route('product')   → generates URL string (use in Blade links)
// to_route('product') → redirects browser to this URL (use from OTHER controllers/actions)
//
// Example Blade link (generates URL, does not redirect by itself):
//   <a href="{{ route('product') }}">Product page</a>
//
// Example controller redirect (after saving data on a different route):
//   return to_route('product');
//
// Do NOT use to_route('product') inside getProduct() — that method handles this
// route already; redirecting to itself causes an infinite loop. Use view() instead.
Route::get('/named-route/product/details/id', [UserController::class, 'getProduct'])->name('product');

// to_route() demo — form page (GET) + save action (POST) that redirects to 'product'
Route::get('/named-route/product/add', [UserController::class, 'showAddProductForm'])->name('product.add');
Route::post('/named-route/product/save', [UserController::class, 'saveProduct'])->name('product.save');

// to_route() WITH route parameters — pass values in URL like ['name' => 'iPhone 15']
// Example redirect: to_route('product.show', ['name' => $productName])
// Builds URL:       /named-route/product/show/iPhone%2015
Route::get('/named-route/product/find', [UserController::class, 'showFindProductForm'])->name('product.find');
Route::post('/named-route/product/find', [UserController::class, 'redirectToProductByName'])->name('product.find.submit');
Route::get('/named-route/product/show/{name}', [UserController::class, 'showProductByName'])->name('product.show');



/*
|--------------------------------------------------------------------------
| Admin Route Grouping (with prefix)
|--------------------------------------------------------------------------
| Laravel allows you to group related routes under a common URL prefix
| using Route::prefix() and Route::group().

| prefix is the route static method helper that is used to prefix the route with the given string
| group is the route group method helper that is used to group the routes with the given closure
| Route::prefix('admin') — All routes inside the group will have URLs starting with /admin.
| Route::group() — Accepts a closure where the grouped routes are defined.
|
| Example below:
|   - /admin/users       → handled by closure, returns "Admin Users"
|   - /admin/moderators  → handled by closure, returns "Admin Moderators"
|   - /admin/dashboard   → handled by closure, returns "Admin Dashboard"
|
| Why group routes?
|   - Keeps code organized for logical areas (e.g., admin panel)
|   - Makes it easy to apply middleware, namespace, or other shared settings
|   - Ensures all admin URLs stay consistent and only need the prefix set in one place
|
| How this works:
|   1. Route::prefix('admin') — sets a prefix for every route inside the group.
|   2. ->group(function() { ... }) — closure receives the grouped route definitions.
|   3. Each Route::get('/something', ...) inside the group will be available at /admin/something.
|   4. You can also use controllers for complex logic.
|
| Example Usage:
| Route::prefix('admin')->group(function () {
|     Route::get('/users', ...);         // /admin/users
|     Route::get('/dashboard', ...);     // /admin/dashboard
| });
|--------------------------------------------------------------------------
*/


Route::prefix("admin")->group(  function(){
        Route::get('/users', function () {
            return "Admin Users";
        });
        Route::get('/moderators', function () {
            return "Admin Moderators";
        });
     
        Route::get('/dashboard', function () {
            return "Admin Dashboard";
        });
    }
);


//Route Grouping having Same Controller Methods
/*
|--------------------------------------------------------------------------
| Route Grouping with Controllers — Example Explained
|--------------------------------------------------------------------------
| Laravel provides a convenient way to group routes that use the same controller.
| This helps avoid repeating the controller reference for every single route,
| making your route definitions cleaner and more maintainable.
|
| The syntax: Route::controller(ControllerClass::class)->group(function () { ... });
|
| How it works:
|   - All routes inside the group will use the specified controller.
|   - Instead of providing a closure or full [Controller, method] array,
|     you just specify the method name as a string for each route.
|   - This reduces repetition, especially if many routes are handled by the same controller.
|
| Example with StudentController:
|   Route::controller(StudentController::class)->group(function () {
|       Route::get('/show', 'show');
|       Route::get('/edit', 'edit');
|       Route::get('/delete', 'delete');
|       Route::get('/create', 'create');
|       Route::get('/about/{name}', 'about');
|   });
|
| Each call like Route::get('/show', 'show'); 
|  - Maps GET requests to /show to the show() method on StudentController.
|
| For routes with parameters (such as /about/{name}):
|  - The value in {name} will be passed as the argument to the about($name) method.
|
| Summary of This Pattern:
|   - Reduces code duplication when multiple routes belong to a single controller.
|   - Makes large route files easier to read and modify.
|   - You're free to use other route features (middleware, prefix, etc.) as needed.
|--------------------------------------------------------------------------
*/


Route::controller(StudentController::class)
    ->middleware('global.mid')  // globalMid runs ONLY on these student routes
    ->group(function () {
        Route::get('/show', 'show');
        Route::get('/edit', 'edit');
        Route::get('/delete', 'delete');
        Route::get('/create', 'create');
        Route::get('/about/{name}', 'about');
    });

/*
|--------------------------------------------------------------------------
| Conditional Middleware Demo
|--------------------------------------------------------------------------
| global.mid    → runs ONLY on student routes (see ->middleware('global.mid') below)
| access.key    → runs ONLY on routes that use ->middleware('access.key')
|
| Try:
|   /middleware-demo/public              → always works
|   /middleware-demo/protected         → 403 without key
|   /middleware-demo/protected?access_key=learn123 → works
|--------------------------------------------------------------------------
*/
Route::view('/middleware-demo', 'middleware.demo')->name('middleware.demo');

Route::prefix('middleware-demo')->group(function () {
    Route::view('/public', 'middleware.public');

    Route::view('/protected', 'middleware.protected')
        ->middleware('access.key');
});

Route::get('/middleware-group-check', function () {
    return "Middleware Group Check Protected";
})->middleware('groupCheck');
//groupCheck is a group alias that is defined in the bootstrap/app.php file

Route::get('/multiple-middleware-to-route', function () {
    return "Multiple Middleware to Route";
})->middleware('global.mid', 'access.key');
//global.mid is a middleware alias that is defined in the bootstrap/app.php file
//access.key is a middleware alias that is defined in the bootstrap/app.php file


/*
|--------------------------------------------------------------------------
| User Database Route
|--------------------------------------------------------------------------
| This method is used to return the user database
|--------------------------------------------------------------------------
*/
Route::get('/user-db', [User_dbController::class, 'user_db']);

Route::get('/user-list', [User_dbController::class, 'userList']);

Route::get('/customer-list', [Customer_dbController::class, 'customerList']);



Route::get('/http-controller', [httpController::class, 'index']);


/*
|--------------------------------------------------------------------------
| DATABASE QUERY BUILDER ROUTES  (beginner walkthrough)
|--------------------------------------------------------------------------
|
| WHAT IS A ROUTE?
|   A route = one URL + one HTTP method (GET or POST) + one controller action.
|
| FULL FLOW (every click follows this path):
|
|   1. User opens a URL in the browser
|   2. Laravel looks through routes/web.php
|   3. It finds a matching Route::get(...) or Route::post(...)
|   4. It calls the controller method written after the arrow
|   5. That method talks to the database with Query Builder (DB::table...)
|   6. The method returns a Blade view OR a redirect
|
| WORD BY WORD ON ONE LINE:
|
|   Route::get('/db-query-builder/all', [DBQueryController::class, 'getAllData'])
|        ->name('dbQueryBuilder.all');
|
|   Route          = Laravel's routing class
|   ::get          = this URL answers GET requests (open / read / show)
|   '/db-query...' = the URL path after your site domain
|   [Class, 'method'] = which controller method should run
|   ->name('...')  = give the route a short nickname
|                    so Blade can use route('dbQueryBuilder.all')
|                    instead of hardcoding the full URL
|
| GET vs POST:
|   GET  = show / search / sort / filter (safe to open in browser address bar)
|   POST = insert / update / delete (changes data; comes from a form)
|
| {id} {name} {email} {phone}:
|   Curly braces mean a variable piece of the URL.
|   Example: /db-query-builder/byid/5  →  $id becomes 5 in the controller
|
|--------------------------------------------------------------------------
*/

// SHOW ALL ROWS
// URL example:  http://localhost/firstLearning/public/db-query-builder/all
Route::get('/db-query-builder/all', [DBQueryController::class, 'getAllData'])
    ->name('dbQueryBuilder.all');

// SHOW ONE ROW BY ID  (URL variable {id})
// URL example:  /db-query-builder/byid/3
Route::get('/db-query-builder/byid/{id}', [DBQueryController::class, 'getDataById'])
    ->name('dbQueryBuilder.byid');

// SHOW ROW(S) BY NAME
// URL example:  /db-query-builder/byname/Ali
Route::get('/db-query-builder/byname/{name}', [DBQueryController::class, 'getDataByName'])
    ->name('dbQueryBuilder.byname');

// SHOW ROW(S) BY EMAIL
// URL example:  /db-query-builder/byemail/ali@mail.com
Route::get('/db-query-builder/byemail/{email}', [DBQueryController::class, 'getDataByEmail'])
    ->name('dbQueryBuilder.byemail');

// SHOW ROW(S) BY PHONE
// URL example:  /db-query-builder/byphone/03001234567
Route::get('/db-query-builder/byphone/{phone}', [DBQueryController::class, 'getDataByPhone'])
    ->name('dbQueryBuilder.byphone');

// INSERT a new row (comes from the Insert form, method="post")
Route::post('/db-query-builder/insert', [DBQueryController::class, 'insertData'])
    ->name('dbQueryBuilder.insert');

// UPDATE an existing row (URL needs which id to update)
Route::post('/db-query-builder/update/{id}', [DBQueryController::class, 'updateData'])
    ->name('dbQueryBuilder.update');

// DELETE an existing row
Route::post('/db-query-builder/delete/{id}', [DBQueryController::class, 'deleteData'])
    ->name('dbQueryBuilder.delete');

// SEARCH using query string: /db-query-builder/search?keyword=ali
// (Form fields with method="get" become ?keyword=... in the URL)
Route::get('/db-query-builder/search', [DBQueryController::class, 'searchData'])
    ->name('dbQueryBuilder.search');

// SORT using query string: /db-query-builder/sort?column=name&order=asc
Route::get('/db-query-builder/sort', [DBQueryController::class, 'sortData'])
    ->name('dbQueryBuilder.sort');

    /*
|--------------------------------------------------------------------------
| ELOQUENT QUERY BUILDER ROUTES
|--------------------------------------------------------------------------
|
| WHAT IS ELOQUENT?
|   Eloquent is Laravel's ORM (Object-Relational Mapping) system.
|   It makes it easy to interact with the database using objects and methods.
|
| FULL FLOW (every click follows this path):
|
|   1. User opens a URL in the browser
|   2. Laravel looks through routes/web.php
|   3. It finds a matching Route::get(...) or Route::post(...)
|   4. It calls the controller method written after the arrow
|   5. That method talks to the database with Eloquent (Student::all())
|   6. The method returns a Blade view OR a redirect
|
| WORD BY WORD ON ONE LINE:
|
|   Route::get('/elqQueryBuilder/studentList', [ElqQueryBuilder::class, 'studentList'])
|        ->name('elqQueryBuilder.studentList');
|
|   Route          = Laravel's routing class
|   ::get          = this URL answers GET requests (open / read / show)
|   '/elqQueryBuilder/studentList' = the URL path after your site domain
|   [Class, 'method'] = which controller method should run
|   ->name('...')  = give the route a short nickname
|                    so Blade can use route('elqQueryBuilder.studentList')
|                    instead of hardcoding the full URL
|
| GET vs POST:
|   GET  = show / search / sort / filter (safe to open in browser address bar)
|   POST = insert / update / delete (changes data; comes from a form)
|
| {id} {name} {email} {phone}:
|   Curly braces mean a variable piece of the URL.
|   Example: /elqQueryBuilder/studentById/5  →  $id becomes 5 in the controller
|--------------------------------------------------------------------------
*/


Route::prefix('elqQueryBuilder')->controller(ElqQueryBuilder::class)->group(function () {
    Route::get('/studentList', 'studentList')->name('elqQueryBuilder.studentList');
    Route::get('/studentById/{id}', 'studentById')->name('elqQueryBuilder.studentById');
    Route::get('/studentByName/{name}', 'studentByName')->name('elqQueryBuilder.studentByName');
    Route::get('/studentByBatch/{batch}', 'studentByBatch')->name('elqQueryBuilder.studentByBatch');
    Route::get('/studentByEmail/{email}', 'studentByEmail')->name('elqQueryBuilder.studentByEmail');
    Route::get('/studentByPhone/{phone}', 'studentByPhone')->name('elqQueryBuilder.studentByPhone');
    Route::post('/addStudent', 'addStudent')->name('elqQueryBuilder.addStudent');
    Route::post('/updateStudent/{id}', 'updateStudent')->name('elqQueryBuilder.updateStudent');
    Route::post('/deleteStudent/{id}', 'deleteStudent')->name('elqQueryBuilder.deleteStudent');
    Route::get('/searchStudent', 'searchStudent')->name('elqQueryBuilder.searchStudent');
});

/*
|--------------------------------------------------------------------------
| ALL ROUTE METHODS — real-life mini shop examples
|--------------------------------------------------------------------------
|
| Start here:  /allroute
|
| Helper           | Real-life example in this demo
| -----------------|-----------------------------------------------
| Route::view      | About Us page (static, no controller)
| Route::get       | Browse product catalog
| Route::post      | Place a new order
| Route::put       | Replace full user profile
| Route::patch     | Toggle email-notification setting only
| Route::delete    | Remove an item from the shopping cart
| Route::match     | Contact form: GET shows, POST submits (same URL)
| Route::any       | Payment webhook (accepts any HTTP method)
|
| Data is stored in session (no database).
| HTML forms only send GET/POST — use @csrf + @method('PUT') etc.
|
|--------------------------------------------------------------------------
*/

Route::get('/allroute', [AllrouteController::class, 'index'])
    ->name('allroute.index');

Route::post('/allroute/reset-cart', [AllrouteController::class, 'resetCart'])
    ->name('allroute.resetCart');

// About Us (static page)
Route::view('/allroute/view', 'Allroute.view')
    ->name('allroute.view');

// Product catalog
Route::get('/allroute/get', [AllrouteController::class, 'getExample'])
    ->name('allroute.get');

// Place order
Route::post('/allroute/post', [AllrouteController::class, 'postExample'])
    ->name('allroute.post');

// Replace full profile
Route::put('/allroute/put', [AllrouteController::class, 'putExample'])
    ->name('allroute.put');

// Update one setting
Route::patch('/allroute/patch', [AllrouteController::class, 'patchExample'])
    ->name('allroute.patch');

// Remove cart item
Route::delete('/allroute/delete', [AllrouteController::class, 'deleteExample'])
    ->name('allroute.delete');

// Contact form (show + submit)
Route::match(['get', 'post'], '/allroute/match', [AllrouteController::class, 'matchExample'])
    ->name('allroute.match');

// Payment webhook
Route::any('/allroute/any', [AllrouteController::class, 'anyExample'])
    ->name('allroute.any');

/*
|--------------------------------------------------------------------------
| REQUEST CLASS METHODS — same verbs + Request inspection
|--------------------------------------------------------------------------
|
| Start here:  /request-methods
|
| Same HTTP helpers as /allroute (get, post, put, patch, delete, match, any),
| but each controller action receives Illuminate\Http\Request and dumps:
|   input(), all(), query(), has(), method(), path(), ip(), hasFile(), …
|
|--------------------------------------------------------------------------
*/

Route::get('/request-methods', [RequestMethodsController::class, 'index'])
    ->name('requestMethods.index');

Route::get('/request-methods/get', [RequestMethodsController::class, 'getExample'])
    ->name('requestMethods.get');

Route::post('/request-methods/post', [RequestMethodsController::class, 'postExample'])
    ->name('requestMethods.post');

Route::put('/request-methods/put', [RequestMethodsController::class, 'putExample'])
    ->name('requestMethods.put');

Route::patch('/request-methods/patch', [RequestMethodsController::class, 'patchExample'])
    ->name('requestMethods.patch');

Route::delete('/request-methods/delete', [RequestMethodsController::class, 'deleteExample'])
    ->name('requestMethods.delete');

Route::match(['get', 'post'], '/request-methods/match', [RequestMethodsController::class, 'matchExample'])
    ->name('requestMethods.match');

Route::any('/request-methods/any', [RequestMethodsController::class, 'anyExample'])
    ->name('requestMethods.any');

Route::post('/request-methods/upload', [RequestMethodsController::class, 'uploadExample'])
    ->name('requestMethods.upload');


/*
|--------------------------------------------------------------------------
| SESSIONS — your login flow + real-world extras
|--------------------------------------------------------------------------
|
| Start:  /sessions
|
| Your original routes:
|   GET  /sessions/login   → show form
|   POST /sessions/store   → Session::put(...) then redirect profile
|   GET  /sessions/profile → Session::get('email') ...
|   GET  /sessions/logout  → Session::flush()
|
| Extra demos on /sessions:
|   cart, theme, flash + keep/reflash, OTP auto-expire, theory page
|
| Theory (flash / API tokens / why DB):  /sessions/theory
|
|--------------------------------------------------------------------------
*/

Route::get('/sessions', [SessionsController::class, 'index'])
    ->name('sessions.index');

Route::get('/sessions/theory', [SessionsController::class, 'theory'])
    ->name('sessions.theory');

Route::get('/sessions/login', [SessionsController::class, 'login'])
    ->name('sessions.login');

Route::post('/sessions/store', [SessionsController::class, 'store'])
    ->name('sessions.store');

Route::get('/sessions/profile', [SessionsController::class, 'profile'])
    ->name('sessions.profile');

Route::get('/sessions/logout', [SessionsController::class, 'logout'])
    ->name('sessions.logout');

Route::post('/sessions/cart/add', [SessionsController::class, 'addToCart'])
    ->name('sessions.addToCart');

Route::post('/sessions/cart/clear', [SessionsController::class, 'clearCart'])
    ->name('sessions.clearCart');

Route::post('/sessions/theme', [SessionsController::class, 'saveTheme'])
    ->name('sessions.saveTheme');

Route::get('/sessions/flash-demo', [SessionsController::class, 'flashDemo'])
    ->name('sessions.flashDemo');

Route::get('/sessions/flash-now', [SessionsController::class, 'flashNowDemo'])
    ->name('sessions.flashNowDemo');

Route::get('/sessions/flash-start', [SessionsController::class, 'flashStart'])
    ->name('sessions.flashStart');

Route::get('/sessions/flash-workshop', [SessionsController::class, 'flashWorkshop'])
    ->name('sessions.flashWorkshop');

Route::post('/sessions/flash-keep', [SessionsController::class, 'flashKeep'])
    ->name('sessions.flashKeep');

Route::post('/sessions/flash-reflash', [SessionsController::class, 'flashReflash'])
    ->name('sessions.flashReflash');

Route::post('/sessions/flash-skip', [SessionsController::class, 'flashSkipKeep'])
    ->name('sessions.flashSkipKeep');

Route::get('/sessions/flash-after', [SessionsController::class, 'flashAfter'])
    ->name('sessions.flashAfter');

Route::get('/sessions/otp/start', [SessionsController::class, 'startOtp'])
    ->name('sessions.startOtp');

Route::get('/sessions/forget-last-product', [SessionsController::class, 'forgetLastProduct'])
    ->name('sessions.forgetLastProduct');

/*
|--------------------------------------------------------------------------
| FILE UPLOADS
|--------------------------------------------------------------------------
| Form:     GET  /upload-files
| Upload:   POST /upload-files/store
| List:     GET  /upload-files/display
| Preview:  GET  /upload-files/preview/{fileName}
| Download: GET  /upload-files/download/{fileName}
| Delete:   DELETE /upload-files/{fileName}
|
| Need public URLs:  php artisan storage:link
|--------------------------------------------------------------------------
*/
Route::view('/upload-imageform', 'uploadFiles.imageStore');
Route::post('/upload-image', [UploadFileController::class, 'imageStore']);
Route::post('/upload-image-fixed-name', [UploadFileController::class, 'withoutRandomName']);

Route::get('/upload-files', [UploadFileController::class, 'showForm'])
    ->name('uploadFiles.form');

Route::post('/upload-files/store', [UploadFileController::class, 'store'])
    ->name('uploadFiles.store');

Route::get('/upload-files/display', [UploadFileController::class, 'display'])
    ->name('uploadFiles.display');

Route::get('/upload-files/download/{fileName}', [UploadFileController::class, 'download'])
    ->name('uploadFiles.download');

Route::get('/upload-files/preview/{fileName}', [UploadFileController::class, 'preview'])
    ->name('uploadFiles.preview');

Route::delete('/upload-files/{fileName}', [UploadFileController::class, 'destroy'])
    ->name('uploadFiles.destroy');

Route::get('/localization/welcome/{locale}', [LocalizationController::class, 'welcome'])
    ->middleware('locale')
    ->name('localization.welcome');

/*
|--------------------------------------------------------------------------
| PAGINATION ROUTE
|--------------------------------------------------------------------------
| URL:  /paginate/list
| Page: /paginate/list?page=2
|
| Laravel reads ?page= automatically when you call paginate() / simplePaginate().
|
| Examples you can also define:
|   Route::get('/paginate/list', [PaginationController::class, 'list'])->name('paginate.list');
|   // Then in Blade: route('paginate.list', ['page' => 2])
*/
Route::get('/paginate/list', [PaginationController::class, 'list'])->name('paginate.list');

/*
|--------------------------------------------------------------------------
| LAYOUT DEMO (separate folder) — CSS / JS / dynamic content
|--------------------------------------------------------------------------
| Folder: resources/views/layout-demo/
| Assets: public/css/layout-demo/  +  public/js/layout-demo/
|
|   /layout-demo            → home (layout + shared CSS/JS + page CSS/JS)
|   /layout-demo/about      → another page, same layout
|   /layout-demo/dashboard  → dynamic data from controller
|   /layout-demo/flash      → session flash message demo
*/
Route::prefix('layout-demo')->name('layout-demo.')->group(function () {
    Route::get('/', [LayoutDemoController::class, 'home'])->name('home');
    Route::get('/about', [LayoutDemoController::class, 'about'])->name('about');
    Route::get('/dashboard', [LayoutDemoController::class, 'dashboard'])->name('dashboard');
    Route::get('/flash', [LayoutDemoController::class, 'flash'])->name('flash');
});


Route::view('/migration', 'migration.explain');

/*



|--------------------------------------------------------------------------
| Accessors & Mutators demo
|--------------------------------------------------------------------------
| GET  /accessors  → list students (accessors format name/email on read)
| POST /save       → create student (mutator formats name on write)
|
| Model logic lives in App\Models\Student:
|   getNameAttribute / getEmailAttribute  = accessors
|   setNameAttribute                      = mutator
*/
Route::get('/accessors', [AccessorController::class, 'list'])->name('accessor_mutator.index');
Route::post('/save', [MutatorController::class, 'save'])->name('accessor_mutator.save');

/*
|--------------------------------------------------------------------------
| ONE TO ONE RELATIONSHIP � line by line
|--------------------------------------------------------------------------
|
| Route::prefix('one-to-one')
|   Route     = Laravel routing class
|   ::prefix  = add "/one-to-one" in front of every URL inside the group
|   Example:  '/' becomes '/one-to-one'
|             '/users' becomes '/one-to-one/users'
|
| ->name('one-to-one.')
|   name prefix for route() helper
|   Example: name('index') becomes route name "one-to-one.index"
|
| ->controller(OnetoOneController::class)
|   all methods below live in OnetoOneController
|   so we write 'index' instead of [OnetoOneController::class, 'index']
|
| ->group(function () { ... })
|   group = put many routes together that share prefix/name/controller
|
| Route::get('/', 'index')->name('index');
|   get     = HTTP GET (browser visit / link click)
|   '/'     = URL path (full URL = /one-to-one)
|   'index' = method name in OnetoOneController
|   name()  = route name for route('one-to-one.index')
|
| {id} in URL = dynamic value from browser
|   /one-to-one/user/5  ?  $id = 5 inside the controller method
*/
Route::prefix('one-to-one')->name('one-to-one.')->controller(OnetoOneController::class)->group(function () {
    Route::get('/', 'index')->name('index');                 // HTML home page
    Route::get('/users', 'users')->name('users');            // JSON: all users + profile
    Route::get('/profiles', 'profiles')->name('profiles');   // JSON: all profiles + user
    Route::get('/user/{id}', 'userProfile')->name('user');   // JSON: one user + profile
    Route::get('/profile/{id}', 'profileUser')->name('profile'); // JSON: one profile + user
    Route::get('/create/{id}', 'createProfile')->name('create'); // JSON: create profile
    Route::get('/subscription', 'showSubscription')->name('subscription'); // JSON: show subscription
});


/*
|--------------------------------------------------------------------------
| ONE TO MANY — User → Orders + Two-Way Test
|--------------------------------------------------------------------------
|
| Forward:  one user can place many orders          → /users , /user/{id}
| Backward: one order belongs to only one user      → /orders , /order/{id}
| Both True = strict 1-to-many (see Blade at /one-to-many)
*/
Route::prefix('one-to-many')->name('one-to-many.')->controller(OnetoManyController::class)->group(function () {
    Route::get('/', 'index')->name('index');                 // HTML: Two-Way Test
    Route::get('/users', 'oneToMany')->name('users');          // FORWARD JSON
    Route::get('/orders', 'manyToOne')->name('orders');        // BACKWARD JSON
    Route::get('/user/{id}', 'userOrders')->name('user');      // FORWARD one user
    Route::get('/order/{id}', 'orderUser')->name('order');     // BACKWARD one order
    Route::get('/create/{id}', 'createOrder')->name('create'); // prove Forward (add more)
    Route::get('/count/{id}', 'orderCount')->name('count');    // helpers
});

/*
|--------------------------------------------------------------------------
| MANY TO ONE — Order belongsTo User
|--------------------------------------------------------------------------
|
| Same DB as one-to-many; start from the child (Order).
| Many orders → one user. Laravel: belongsTo.
|
| GET /many-to-one                         HTML lesson
| GET /many-to-one/orders                  all orders + user
| GET /many-to-one/order/{id}              one order → one user
| GET /many-to-one/for-user/{userId}       many orders of one user
| GET /many-to-one/associate/{orderId}/{userId}  change owner
*/
Route::prefix('many-to-one')->name('many-to-one.')->controller(ManyToOneController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/orders', 'orders')->name('orders');
    Route::get('/order/{id}', 'order')->name('order');
    Route::get('/for-user/{userId}', 'forUser')->name('for-user');
    Route::get('/associate/{orderId}/{userId}', 'associate')->name('associate');
});
