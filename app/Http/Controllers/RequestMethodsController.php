<?php

/*
|--------------------------------------------------------------------------
| WORD BY WORD — top of the file
|--------------------------------------------------------------------------
|
| <?php
|   Starts a PHP file. Everything after this is PHP code.
|
| namespace App\Http\Controllers;
|   "namespace" = folder address of this class in PHP.
|   App\Http\Controllers maps to  app/Http/Controllers/
|   So other files can find this class as:
|       App\Http\Controllers\RequestMethodsController
|
| use Illuminate\Http\Request;
|   "use" = import a class so we can write Request instead of the long name.
|   Illuminate = Laravel's core code brand/namespace.
|   Http = HTTP package (web requests/responses).
|   Request = the class that holds EVERYTHING the browser sent:
|       URL, query string (?q=mouse), form fields, files, headers, IP, etc.
|
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| RequestMethodsController — Laravel Request class demo (detailed)
|--------------------------------------------------------------------------
|
| WHAT IS A CONTROLLER?
|   A PHP class with public methods. Routes call these methods.
|   Flow:  Browser → routes/web.php → Controller method → return view
|
| WHAT IS "extends Controller"?
|   This class inherits (gets features) from Laravel's base Controller.
|   "extends" = child class uses parent class as a starting point.
|
| WHAT IS Illuminate\Http\Request?
|   One object Laravel builds for EVERY incoming HTTP call.
|   You type-hint it:  function name(Request $request)
|   Laravel injects (automatically fills) the current request into $request.
|
| INJECTION (dependency injection):
|   You ask for Request $request in the method signature.
|   Laravel creates/gives you that object — you do not "new Request()" yourself.
|
|--------------------------------------------------------------------------
*/
class RequestMethodsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | index()
    |--------------------------------------------------------------------------
    |
    | public  = Laravel (and routes) can call this method from outside.
    | function = defines a named block of code.
    | index   = common name for the "main / landing" action of a controller.
    |
    | return view('RequestMethods.index');
    |   view()  = Laravel helper: find a Blade template and render HTML.
    |   'RequestMethods.index'
    |       Dot (.) means folders:
    |       resources/views/RequestMethods/index.blade.php
    |
    | No Request parameter here — this page only shows forms/links.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('RequestMethods.index');
    }

    /*
    |--------------------------------------------------------------------------
    | inspect() — private helper used by EVERY example method below
    |--------------------------------------------------------------------------
    |
    | private
    |   Only THIS class can call inspect(). Routes cannot call it directly.
    |   Why? It is shared internal logic, not a public URL action.
    |
    | Request $request
    |   Type hint: parameter MUST be a Request object.
    |   $request is the variable name we chose (could be $req).
    |
    | string $routeLabel
    |   Type hint: parameter MUST be a string (text).
    |   We pass a label like "Route::get + Request" for the result page title.
    |
    | $this->inspect(...)
    |   "$this" = the current object (this controller instance).
    |   "->"    = call a method / use a property on an object.
    |   So $this->inspect() means: run inspect() on this same controller.
    |
    |--------------------------------------------------------------------------
    */
    private function inspect(Request $request, string $routeLabel)
    {
        /*
        |----------------------------------------------------------------------
        | FILE UPLOAD SECTION
        |----------------------------------------------------------------------
        |
        | $uploaded = null;
        |   Start with "no file". null means "empty / nothing".
        |
        | hasFile('photo')
        |   true if the form sent a file field named "photo".
        |   Form needs:  <input type="file" name="photo">
        |   AND form must have:  enctype="multipart/form-data"
        |
        | file('photo')
        |   Returns an UploadedFile object for that field.
        |
        | isValid()
        |   true if PHP received the file without upload errors.
        |
        | &&
        |   Logical AND — both left and right must be true.
        |
        |----------------------------------------------------------------------
        */
        $uploaded = null;

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // $file holds the uploaded file object (not the raw image bytes alone)
            $file = $request->file('photo');

            /*
            | getClientOriginalName() — file name from the user's computer
            | getMimeType()           — type like image/png
            | getSize()               — size in bytes
            | getClientOriginalExtension() — extension like jpg / png
            |
            | round(x / 1024, 2)
            |   Convert bytes → kilobytes, keep 2 decimal places.
            |
            | [ 'key' => value ]  = associative array (named keys).
            */
            $uploaded = [
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size_kb' => round($file->getSize() / 1024, 2),
                'extension' => $file->getClientOriginalExtension(),
            ];
        }

        /*
        |----------------------------------------------------------------------
        | $demo ARRAY — each key is a label shown in the Blade table
        |----------------------------------------------------------------------
        |
        | Format:
        |   'label shown to student' => actual result from $request->something()
        |
        | Ternary?:
        |   condition ? 'true' : 'false'
        |   If condition is true → use first value, else second.
        |   We convert boolean true/false into readable words for the table.
        |
        |----------------------------------------------------------------------
        */
        $demo = [

            /*
            |------------------------------------------------------------------
            | HTTP METHOD helpers
            |------------------------------------------------------------------
            | HTTP method = verb of the request: GET, POST, PUT, PATCH, DELETE
            |
            | method()
            |   Returns the verb as a string, e.g. "GET" or "POST".
            |
            | isMethod('GET')
            |   Returns true/false: "Is this request a GET?"
            |   Useful in Match/Any routes to branch logic.
            |
            |------------------------------------------------------------------
            */
            'method()' => $request->method(),
            'isMethod(\'GET\')' => $request->isMethod('GET') ? 'true' : 'false',
            'isMethod(\'POST\')' => $request->isMethod('POST') ? 'true' : 'false',
            'isMethod(\'PUT\')' => $request->isMethod('PUT') ? 'true' : 'false',

            /*
            |------------------------------------------------------------------
            | INPUT / BODY / QUERY helpers
            |------------------------------------------------------------------
            |
            | all()
            |   EVERY input field from query string + form body (as one array).
            |   Also includes _token and _method if present.
            |
            | input('name')
            |   Get one value by field name from query OR body.
            |   Returns null if that key does not exist.
            |
            | input('city', 'N/A')
            |   Same as input(), but if city is missing → return default 'N/A'.
            |   Second argument = default value.
            |
            | get('name')
            |   Similar to input('name') — another way to read a parameter.
            |
            | query('q')
            |   ONLY from the query string in the URL.
            |   Example URL: /request-methods/get?q=mouse
            |   → query('q') returns "mouse"
            |   Form POST body fields are NOT in query().
            |
            | post('name')
            |   ONLY from the POST body (form fields), not from ?query=.
            |
            | only(['name', 'email'])
            |   Return a small array with ONLY these keys (if present).
            |   Good when you want to pass just safe fields to the DB.
            |
            | except(['_token', '_method'])
            |   Return all inputs EXCEPT these keys.
            |   _token  = CSRF token Laravel adds with @csrf
            |   _method = spoofed method from @method('PUT') etc.
            |
            | keys()
            |   List of all input field names (the keys only, not values).
            |
            |------------------------------------------------------------------
            */
            'all()' => $request->all(),
            'input(\'name\')' => $request->input('name'),
            'input(\'city\', \'N/A\')' => $request->input('city', 'N/A'),
            'get(\'name\')' => $request->get('name'),
            'query(\'q\')' => $request->query('q'),
            'post(\'name\')' => $request->post('name'),
            'only([\'name\',\'email\'])' => $request->only(['name', 'email']),
            'except([\'_token\',\'_method\'])' => $request->except(['_token', '_method']),
            'keys()' => $request->keys(),

            /*
            |------------------------------------------------------------------
            | CHECK helpers (true / false questions about inputs)
            |------------------------------------------------------------------
            |
            | has('name')
            |   true if the key "name" exists in the request
            |   (even if the value is empty string "").
            |
            | filled('name')
            |   true if key exists AND is not empty.
            |   "" (blank) → has=true, filled=false
            |
            | missing('age')
            |   true if key "age" is NOT in the request at all.
            |
            | boolean('subscribe')
            |   Reads checkboxes / "1"/"0"/"true"/"false" as real true/false.
            |   Checkbox unchecked → usually false (field may be absent).
            |
            | integer('qty')
            |   Casts/reads the value as an integer (number without decimals).
            |   Missing → 0 by default.
            |
            |------------------------------------------------------------------
            */
            'has(\'name\')' => $request->has('name') ? 'true' : 'false',
            'filled(\'name\')' => $request->filled('name') ? 'true' : 'false',
            'missing(\'age\')' => $request->missing('age') ? 'true' : 'false',
            'boolean(\'subscribe\')' => $request->boolean('subscribe') ? 'true' : 'false',
            'integer(\'qty\')' => $request->integer('qty'),

            /*
            |------------------------------------------------------------------
            | URL / PATH helpers
            |------------------------------------------------------------------
            |
            | Example browser URL:
            |   http://localhost:8000/request-methods/get?q=mouse
            |
            | path()
            |   "request-methods/get"  (path after domain, no query string)
            |
            | url()
            |   "http://localhost:8000/request-methods/get"  (no ?query)
            |
            | fullUrl()
            |   Full URL including ?q=mouse
            |
            | root()
            |   "http://localhost:8000"  (scheme + host only)
            |
            | segment(1)
            |   Path pieces split by "/":
            |     1 = "request-methods"
            |     2 = "get"
            |   segment(1) is the FIRST piece (1-based index).
            |
            | segments()
            |   Array of all pieces: ["request-methods", "get"]
            |
            | is('request-methods*')
            |   Pattern match on the path.
            |   * = wildcard (anything after).
            |   true if path starts like request-methods...
            |
            | routeIs('requestMethods.*')
            |   Match against the ROUTE NAME (from ->name(...)), not the path.
            |   "requestMethods.*" means any name starting with requestMethods.
            |
            |------------------------------------------------------------------
            */
            'path()' => $request->path(),
            'url()' => $request->url(),
            'fullUrl()' => $request->fullUrl(),
            'root()' => $request->root(),
            'segment(1)' => $request->segment(1),
            'segments()' => $request->segments(),
            'is(\'request-methods*\')' => $request->is('request-methods*') ? 'true' : 'false',
            'routeIs(\'requestMethods.*\')' => $request->routeIs('requestMethods.*') ? 'true' : 'false',

            /*
            |------------------------------------------------------------------
            | CLIENT / HEADER helpers
            |------------------------------------------------------------------
            |
            | Header = extra info the browser/client sends with the request.
            |   Examples: Accept, User-Agent, Cookie, Authorization
            |
            | ip()
            |   Visitor IP address (or 127.0.0.1 on local machine).
            |
            | userAgent()
            |   Browser / device string (Chrome, Firefox, etc.).
            |
            | header('Accept')
            |   One header value by name. Accept tells what content types
            |   the browser likes (HTML, JSON, …).
            |
            | secure()
            |   true if request used HTTPS (encrypted). Local http → false.
            |
            | getScheme()
            |   "http" or "https"
            |
            | host()
            |   Host name, e.g. "localhost"
            |
            | ajax()
            |   true if request looks like AJAX (XMLHttpRequest header).
            |   Normal form submit / link click → usually false.
            |
            | expectsJson()
            |   true if client wants JSON back (API / fetch with Accept: json).
            |
            |------------------------------------------------------------------
            */
            'ip()' => $request->ip(),
            'userAgent()' => $request->userAgent(),
            'header(\'Accept\')' => $request->header('Accept'),
            'secure()' => $request->secure() ? 'true' : 'false',
            'scheme()' => $request->getScheme(),
            'host()' => $request->host(),
            'ajax()' => $request->ajax() ? 'true' : 'false',
            'expectsJson()' => $request->expectsJson() ? 'true' : 'false',

            /*
            |------------------------------------------------------------------
            | FILE check (boolean only — details are in $uploaded above)
            |------------------------------------------------------------------
            | hasFile('photo') → true if a file was uploaded under name "photo"
            |------------------------------------------------------------------
            */
            'hasFile(\'photo\')' => $request->hasFile('photo') ? 'true' : 'false',
        ];

        /*
        |----------------------------------------------------------------------
        | return view(..., [ data ])
        |----------------------------------------------------------------------
        |
        | Second argument is an associative array passed TO the Blade file.
        | In Blade you use:
        |   $routeLabel   $demo   $uploaded   $method
        |
        | Keys become variable names in the view.
        |----------------------------------------------------------------------
        */
        return view('RequestMethods.inspect', [
            'routeLabel' => $routeLabel,
            'demo' => $demo,
            'uploaded' => $uploaded,
            'method' => $request->method(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | getExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::get('/request-methods/get', ...)
    |
    | GET meaning:
    |   Safe "read" request. Browser address bar and <a href> use GET.
    |   Data often travels in the query string:  ?q=mouse&name=Ali
    |
    | Why Request here?
    |   So we can read that query string with query('q') / input('name').
    |
    | return $this->inspect(...)
    |   Reuse the shared dump page; pass a title label as second argument.
    |--------------------------------------------------------------------------
    */
    public function getExample(Request $request)
    {
        return $this->inspect($request, 'Route::get + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | postExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::post('/request-methods/post', ...)
    |
    | POST meaning:
    |   Send / create data (forms). Body holds fields, not usually the URL.
    |   HTML:  <form method="post"> ... @csrf ... </form>
    |
    | $request->validate([ rules ])
    |   Checks incoming fields against rules BEFORE you trust them.
    |   If invalid → Laravel redirects back with error messages.
    |
    | Rule words:
    |   nullable  = field may be missing or empty
    |   string    = must be text
    |   max:80    = maximum 80 characters
    |   email     = must look like an email address
    |   integer   = whole number
    |   min:0     = at least 0
    |   max:100   = at most 100
    |   |         = AND another rule (pipe separator)
    |
    | Array 'field' => 'rule|rule'
    |   Left  = input name from the form (name="email")
    |   Right = validation rules for that field
    |--------------------------------------------------------------------------
    */
    public function postExample(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:80',
            'email' => 'nullable|email',
            'qty' => 'nullable|integer|min:0|max:100',
        ]);

        return $this->inspect($request, 'Route::post + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | putExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::put('/request-methods/put', ...)
    |
    | PUT meaning:
    |   Replace a WHOLE resource (full profile update).
    |
    | Browser note:
    |   HTML forms cannot send real PUT. Laravel trick:
    |     method="post"  +  @method('PUT')
    |   That sends _method=PUT; Laravel treats it as PUT.
    |   $request->method() will still report "PUT".
    |--------------------------------------------------------------------------
    */
    public function putExample(Request $request)
    {
        return $this->inspect($request, 'Route::put + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | patchExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::patch('/request-methods/patch', ...)
    |
    | PATCH meaning:
    |   Update PART of a resource (one city field, one toggle, etc.).
    |   Also needs @method('PATCH') in HTML forms.
    |
    | In real apps you often do:
    |   $data = $request->only(['city']);
    |   then update only those columns in the database.
    |--------------------------------------------------------------------------
    */
    public function patchExample(Request $request)
    {
        return $this->inspect($request, 'Route::patch + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | deleteExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::delete('/request-methods/delete', ...)
    |
    | DELETE meaning:
    |   Remove a resource. Form uses @method('DELETE').
    |
    | Typical read:
    |   $id = $request->integer('id');
    |   then Model::find($id)?->delete();
    |--------------------------------------------------------------------------
    */
    public function deleteExample(Request $request)
    {
        return $this->inspect($request, 'Route::delete + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | matchExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::match(['get', 'post'], '/request-methods/match', ...)
    |
    | match meaning:
    |   ONE URL accepts ONLY the listed verbs (here GET and POST).
    |
    | Real pattern:
    |   if ($request->isMethod('get'))  { show form; }
    |   if ($request->isMethod('post')) { save form; }
    |
    | This demo always dumps Request so you can compare GET vs POST results.
    |--------------------------------------------------------------------------
    */
    public function matchExample(Request $request)
    {
        return $this->inspect($request, 'Route::match([get, post]) + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | anyExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::any('/request-methods/any', ...)
    |
    | any meaning:
    |   ONE URL accepts EVERY HTTP verb (GET, POST, PUT, PATCH, DELETE, …).
    |
    | Real life: payment/webhook URLs where the provider chooses the method.
    | Always check $request->method() and validate carefully.
    |--------------------------------------------------------------------------
    */
    public function anyExample(Request $request)
    {
        return $this->inspect($request, 'Route::any + Request');
    }

    /*
    |--------------------------------------------------------------------------
    | uploadExample(Request $request)
    |--------------------------------------------------------------------------
    |
    | Connected to:  Route::post('/request-methods/upload', ...)
    |
    | Still POST (files are almost always uploaded with POST).
    |
    | Validation rules for files:
    |   nullable = file can be omitted
    |   image    = must be an image type (jpeg, png, bmp, gif, svg, webp)
    |   max:2048 = max size in kilobytes (here 2 MB)
    |
    | After validate passes, inspect() uses hasFile() / file() to show details.
    |
    | IMPORTANT HTML:
    |   <form method="post" enctype="multipart/form-data">
    |   Without enctype, $request->hasFile() will be false.
    |--------------------------------------------------------------------------
    */
    public function uploadExample(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|max:2048',
            'name' => 'nullable|string|max:80',
        ]);

        return $this->inspect($request, 'POST upload + Request::hasFile / file()');
    }
}
