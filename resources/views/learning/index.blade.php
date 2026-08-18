<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>firstLearning — Laravel beginner index</title>
    <!-- Learning home: GET /  view learning/index.blade.php  LearningIndexController@index -->
    <style>
        body { max-width: 1080px; margin: 28px auto; padding: 0 18px 60px; font: 16px/1.55 Arial, sans-serif; color: #1f2937; }
        h1, h2, h3 { color: #111827; }
        h1 { margin-bottom: 8px; }
        nav.toc { position: sticky; top: 0; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px 16px; margin: 18px 0 28px; z-index: 2; }
        nav.toc a { margin-right: 10px; white-space: nowrap; }
        section { margin: 22px 0; padding: 18px; border: 1px solid #d1d5db; border-radius: 8px; }
        code, pre { background: #f3f4f6; border-radius: 5px; }
        code { padding: 2px 5px; }
        pre { padding: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #d1d5db; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
        a { color: #0369a1; }
        .links a { display: inline-block; margin: 0 10px 8px 0; }
        .note { color: #92400e; background: #fef3c7; padding: 10px; border-radius: 6px; }
        .ok { color: #065f46; background: #d1fae5; padding: 10px; border-radius: 6px; }
        .muted { color: #6b7280; }
        .num { display: inline-block; min-width: 1.6em; color: #0369a1; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laravel beginner learning index</h1>
    <p>
        This project is one Laravel 12 app. Each topic has a demo URL.
        Start here, click a topic, then come back with the browser Back button or this page at
        <a href="{{ url('/') }}">/</a>.
    </p>
    <p class="ok">
        Request path (beginner map):
        URL → <code>routes/web.php</code> or <code>routes/api.php</code>
        → middleware (optional)
        → controller
        → validation (if input)
        → model / database (if data)
        → Blade HTML <strong>or</strong> JSON.
    </p>
    <p class="muted">
        Original Laravel welcome (unchanged): <a href="{{ url('/welcome') }}">/welcome</a>
        · tiny home: <a href="{{ url('/home') }}">/home</a>
        · README file in the project root has the long written lessons.
    </p>

    <nav class="toc">
        <strong>Jump:</strong>
        <a href="#branches">Branches</a>
        <a href="#t-http">HTTP Client</a>
        <a href="#t-maint">Maintenance</a>
        <a href="#t1">1 Setup</a>
        <a href="#t2">2 Routes</a>
        <a href="#t3">3 Controllers</a>
        <a href="#t4">4 Blade</a>
        <a href="#t5">5 Forms</a>
        <a href="#t6">6 Validation</a>
        <a href="#t7">7 URLs</a>
        <a href="#t8">8 Named</a>
        <a href="#t9">9 Groups</a>
        <a href="#t10">10 Middleware</a>
        <a href="#t11">11 DB</a>
        <a href="#t12">12 Seeders</a>
        <a href="#t13">13 Query Builder</a>
        <a href="#t14">14 Eloquent</a>
        <a href="#t15">15 HTTP methods</a>
        <a href="#t16">16 Request</a>
        <a href="#t17">17 Sessions</a>
        <a href="#t18">18 Uploads</a>
        <a href="#t19">19 Locale</a>
        <a href="#t20">20 Pagination</a>
        <a href="#t21">21 Layout</a>
        <a href="#t22">22 Migrations</a>
        <a href="#t23">23 Accessors</a>
        <a href="#t24">24 Relations</a>
        <a href="#t25">25 Mail</a>
        <a href="#t26">26 Fluent</a>
        <a href="#t27">27 Binding</a>
        <a href="#t28">28 API</a>
        <a href="#t29">29 Resource</a>
        <a href="#t30">30 Sanctum</a>
    </nav>

    {{-- ================================================================= --}}
    <section id="flow">
        <h2>How a page is built (read this first)</h2>
        <table>
            <thead>
                <tr><th>Piece</th><th>What it is</th><th>This project</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Route</td>
                    <td>URL + HTTP method → which code runs</td>
                    <td><code>routes/web.php</code> (HTML) · <code>routes/api.php</code> (JSON)</td>
                </tr>
                <tr>
                    <td>Controller</td>
                    <td>PHP class with methods (keep routes short)</td>
                    <td><code>app/Http/Controllers/</code></td>
                </tr>
                <tr>
                    <td>View (Blade)</td>
                    <td>HTML the browser shows</td>
                    <td><code>resources/views/</code> · this file is <code>learning/index.blade.php</code></td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td>One PHP class ≈ one database table</td>
                    <td><code>User</code>, <code>Student</code>, … in <code>app/Models/</code></td>
                </tr>
                <tr>
                    <td>Migration</td>
                    <td>Creates/changes tables</td>
                    <td><code>database/migrations/</code></td>
                </tr>
            </tbody>
        </table>
@verbatim
<pre>// Route example (web.php)
Route::get('/', [LearningIndexController::class, 'index']);

// Controller example
public function index() {
    return view('learning.index'); // → resources/views/learning/index.blade.php
}

// Blade example (this page)
&lt;a href="{{ url('/rmb') }}"&gt;Route model binding&lt;/a&gt;</pre>
@endverbatim
        <p class="note">
            <code>php artisan serve</code> then open <code>http://127.0.0.1:8000/</code>.
            With XAMPP the folder URL may be <code>http://localhost/firstLearning/public</code>.
        </p>
    </section>

    <section id="branches">
        <h2>Git branches (every topic in this repo)</h2>
        <p class="muted">Each topic was built on a branch, then merged into <code>main</code>.</p>
        <table>
            <thead>
                <tr><th>Branch</th><th>What you learn</th><th>Open</th></tr>
            </thead>
            <tbody>
                <tr><td><code>Routes-handling</code></td><td>GET/POST/view, parameters, redirect</td><td><a href="#t2">#2</a> · <a href="{{ url('/check/redirect') }}">/check/redirect</a></td></tr>
                <tr><td><code>controllers</code></td><td>UserController, nested views</td><td><a href="#t3">#3</a> · <a href="{{ url('/user-controller') }}">/user-controller</a></td></tr>
                <tr><td><code>views</code></td><td>Blade loops, include, layouts</td><td><a href="#t4">#4</a> · <a href="{{ url('/learn/blade-basics') }}">/learn/blade-basics</a></td></tr>
                <tr><td><code>postforminput-1</code></td><td>POST + CSRF / 419</td><td><a href="#t5">#5</a> · <a href="{{ url('/user-form') }}">/user-form</a></td></tr>
                <tr><td><code>postforminput-2</code></td><td>Checkbox, radio, select</td><td><a href="#t5">#5</a> · <a href="{{ url('/user-attributes-form') }}">/user-attributes-form</a></td></tr>
                <tr><td><code>formValidation</code></td><td><code>validate()</code> + errors</td><td><a href="#t6">#6</a> · <a href="{{ url('/form-validation') }}">/form-validation</a></td></tr>
                <tr><td><code>validation_customErrors</code></td><td>Custom messages + lang files</td><td><a href="#t6">#6</a></td></tr>
                <tr><td><code>ownCustom-rules</code></td><td><code>ageLimit</code> + AppServiceProvider</td><td><a href="#t6">#6</a></td></tr>
                <tr><td><code>getting-url</code></td><td>url() helpers</td><td><a href="#t7">#7</a> · <a href="{{ url('/url-check/landing') }}">/url-check/landing</a></td></tr>
                <tr><td><code>named-routed</code></td><td>route() / to_route()</td><td><a href="#t8">#8</a> · <a href="{{ url('/named-route/welcome') }}">/named-route/welcome</a></td></tr>
                <tr><td><code>route-grouping</code></td><td>prefix + controller groups</td><td><a href="#t9">#9</a> · <a href="{{ url('/admin/users') }}">/admin/users</a></td></tr>
                <tr><td><code>Middleware</code></td><td>alias, group, access key</td><td><a href="#t10">#10</a> · <a href="{{ url('/middleware-demo') }}">/middleware-demo</a></td></tr>
                <tr><td><code>db-start</code></td><td>User / Customer models</td><td><a href="#t11">#11</a> · <a href="{{ url('/user-list') }}">/user-list</a></td></tr>
                <tr><td><code>httpClient-api</code></td><td>Call an external API (Http::get)</td><td><a href="#t-http">HTTP Client</a> · <a href="{{ url('/http-controller') }}">/http-controller</a></td></tr>
                <tr><td><code>db-queryBuilder</code></td><td>DB::table() CRUD</td><td><a href="#t13">#13</a> · <a href="{{ url('/db-query-builder/all') }}">/db-query-builder/all</a></td></tr>
                <tr><td><code>eloquent-queryBuilder</code></td><td>Student Eloquent CRUD</td><td><a href="#t14">#14</a> · <a href="{{ url('/elqQueryBuilder/studentList') }}">studentList</a></td></tr>
                <tr><td><code>route-methods</code></td><td>GET POST PUT PATCH DELETE</td><td><a href="#t15">#15</a> · <a href="{{ url('/allroute') }}">/allroute</a></td></tr>
                <tr><td><code>allRequestMethods</code></td><td>Request input/headers/files</td><td><a href="#t16">#16</a> · <a href="{{ url('/request-methods') }}">/request-methods</a></td></tr>
                <tr><td><code>Sessions</code></td><td>session put/get/flash (cookies)</td><td><a href="#t17">#17</a> · <a href="{{ url('/sessions') }}">/sessions</a></td></tr>
                <tr><td><code>upload-files</code></td><td>upload / download / delete</td><td><a href="#t18">#18</a> · <a href="{{ url('/upload-files') }}">/upload-files</a></td></tr>
                <tr><td><code>localization</code></td><td>locale + SetLocale</td><td><a href="#t19">#19</a> · <a href="{{ url('/localization/welcome/en') }}">en</a></td></tr>
                <tr><td><code>pagination</code></td><td>paginate() + ?page=</td><td><a href="#t20">#20</a> · <a href="{{ url('/paginate/list') }}">/paginate/list</a></td></tr>
                <tr><td><code>LayoutCssJs</code></td><td>layout + CSS/JS</td><td><a href="#t21">#21</a> · <a href="{{ url('/layout-demo') }}">/layout-demo</a></td></tr>
                <tr><td><code>migrations</code></td><td>up / down / rollback</td><td><a href="#t22">#22</a> · <a href="{{ url('/migration') }}">/migration</a></td></tr>
                <tr><td><code>seeder</code></td><td>sample data after migrate</td><td><a href="#t12">#12</a></td></tr>
                <tr><td><code>Accessor_Mutators</code></td><td>accessor / mutator</td><td><a href="#t23">#23</a> · <a href="{{ url('/accessors') }}">/accessors</a></td></tr>
                <tr><td><code>Relation_1-1</code></td><td>hasOne / belongsTo</td><td><a href="#t24">#24</a> · <a href="{{ url('/one-to-one') }}">/one-to-one</a></td></tr>
                <tr><td><code>Relation_1-M</code></td><td>hasMany</td><td><a href="#t24">#24</a> · <a href="{{ url('/one-to-many') }}">/one-to-many</a></td></tr>
                <tr><td><code>Relation_M-1</code></td><td>belongsTo from child</td><td><a href="#t24">#24</a> · <a href="{{ url('/many-to-one') }}">/many-to-one</a></td></tr>
                <tr><td><code>Relation_M-M</code></td><td>belongsToMany + pivot</td><td><a href="#t24">#24</a> · <a href="{{ url('/many-to-many') }}">/many-to-many</a></td></tr>
                <tr><td><code>send-email</code></td><td>Mailable</td><td><a href="#t25">#25</a> · <a href="{{ url('/email') }}">/email</a></td></tr>
                <tr><td><code>fluent-string</code></td><td>Str::of() chains</td><td><a href="#t26">#26</a> · <a href="{{ url('/fluent-string') }}">/fluent-string</a></td></tr>
                <tr><td><code>route-model-binding</code></td><td>User $user from URL</td><td><a href="#t27">#27</a> · <a href="{{ url('/rmb') }}">/rmb</a></td></tr>
                <tr><td><code>Api-crud</code></td><td>JSON APIs in this app</td><td><a href="#t28">#28</a> · <a href="{{ url('/api-learning') }}">/api-learning</a></td></tr>
                <tr><td><code>resource-controller</code></td><td>apiResource CRUD</td><td><a href="#t29">#29</a> · <a href="{{ url('/resource-controller') }}">/resource-controller</a></td></tr>
                <tr><td><code>sanctum</code></td><td>User Bearer tokens</td><td><a href="#t30">#30</a> · <a href="{{ url('/sanctum') }}">/sanctum</a></td></tr>
            </tbody>
        </table>
        <p class="note">Maintenance mode is Artisan only — <a href="#t-maint">see below</a>. <code>CollectionMethodsController.php</code> exists but has no route yet.</p>
    </section>

    {{-- 1 --}}
    <section id="t1">
        <h2><span class="num">1.</span> Project setup &amp; first view</h2>
        <p><strong>What:</strong> Laravel starts at <code>public/index.php</code>. HTML lives in Blade files under <code>resources/views</code>.</p>
        <p><strong>Why:</strong> The browser never runs <code>routes/web.php</code> directly — it hits <code>public/</code>.</p>
        <p class="links">
            <a href="{{ url('/welcome') }}">/welcome</a>
            <a href="{{ url('/home') }}">/home</a>
            <a href="{{ url('/test') }}">/test</a>
        </p>
    </section>

    {{-- 2 --}}
    <section id="t2">
        <h2><span class="num">2.</span> Routing basics</h2>
        <p class="muted">Branch: <code>Routes-handling</code></p>
        <p><strong>What:</strong> A route maps <em>method + URL</em> to code. No route = 404.</p>
        <p><strong>Key ideas:</strong> <code>Route::get</code> (visit/link) · <code>Route::post</code> (form) · <code>Route::view</code> (Blade only, no logic) · <code>{name}</code> from the URL · <code>Route::redirect</code></p>
@verbatim
<pre>Route::get('/user/{name}', function (string $name) {
    return view('user', ['name' => $name]);
});
Route::view('/test', 'test');           // no closure
Route::post('/addUser', [UserController::class, 'addUser']);</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/user/Ali') }}">/user/Ali</a>
            <a href="{{ url('/test') }}">/test</a>
            <a href="{{ url('/check/redirect') }}">/check/redirect</a> (sends you to /)
        </p>
    </section>

    {{-- 3 --}}
    <section id="t3">
        <h2><span class="num">3.</span> Controllers</h2>
        <p><strong>What:</strong> A class that holds methods so <code>web.php</code> stays a map, not a novel.</p>
        <p><strong>Why:</strong> Related actions (show form, save, list) sit in one file.</p>
@verbatim
<pre>php artisan make:controller UserController

Route::get('/user-controller', [UserController::class, 'getUser']);
// view('admin.login') → resources/views/admin/login.blade.php</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/user-controller') }}">/user-controller</a>
            <a href="{{ url('/dynamicUser-controller/Osama') }}">/dynamicUser-controller/Osama</a>
            <a href="{{ url('/admin/login') }}">/admin/login</a>
        </p>
    </section>

    {{-- 4 --}}
    <section id="t4">
        <h2><span class="num">4.</span> Blade templates</h2>
        <p><strong>What:</strong> HTML plus <code>@</code> directives. Laravel compiles them to PHP.</p>
        <p><strong>Key ideas:</strong> <code>@{{ $var }}</code> escaped print · <code>@@if</code> / <code>@@foreach</code> · <code>@@include</code> · <code>@@extends</code> / <code>@@yield</code> · components <code>&lt;x-...&gt;</code></p>
@verbatim
<pre>{{ $name }}          {{-- safe print --}}
@if ($users)
    @foreach ($users as $user)
        &lt;li&gt;{{ $user-&gt;name }}&lt;/li&gt;
    @endforeach
@endif</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/learn/blade-basics') }}">/learn/blade-basics</a>
            <a href="{{ url('/learn/blade-loops-data') }}">/learn/blade-loops-data</a>
            <a href="{{ url('/learn/interview-checklist') }}">/learn/interview-checklist</a>
            <a href="{{ url('/mainview') }}">/mainview</a>
        </p>
    </section>

    {{-- 5 --}}
    <section id="t5">
        <h2><span class="num">5.</span> Forms &amp; request input</h2>
        <p><strong>What:</strong> GET shows a form. POST sends fields. Laravel reads them with <code>Request</code>.</p>
        <p><strong>Must know:</strong> every POST form needs <code>@@csrf</code> or you get <strong>419 Page Expired</strong>.</p>
@verbatim
<pre>&lt;form method="POST" action="{{ url('/addUser') }}"&gt;
    @csrf
    &lt;input name="name"&gt;
    &lt;button&gt;Save&lt;/button&gt;
&lt;/form&gt;

$request-&gt;input('name');   // or $request-&gt;name</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/user-form') }}">/user-form</a>
            <a href="{{ url('/user-attributes-form') }}">/user-attributes-form</a>
        </p>
    </section>

    {{-- 6 --}}
    <section id="t6">
        <h2><span class="num">6.</span> Validation</h2>
        <p class="muted">Branches: <code>formValidation</code> · <code>validation_customErrors</code> · <code>ownCustom-rules</code></p>
        <p><strong>What:</strong> Reject bad input <em>before</em> save. <code>$request-&gt;validate([...])</code>.</p>
        <p><strong>Key ideas:</strong> rules like <code>required|email</code> · <code>@@error('field')</code> in Blade · <code>old('field')</code> keeps typed values · custom rule <code>ageLimit</code> · messages in <code>lang/en/validation.php</code></p>
        <p class="note">On APIs, failed validation is <strong>422 JSON</strong> if you send <code>Accept: application/json</code>. On web forms it redirects back with errors.</p>
        <p>Files: <code>app/Rules/ageLimit.php</code> · <code>AppServiceProvider</code> (<code>Validator::extend('ageLimit')</code>) · <code>lang/en/validation.php</code></p>
        <p class="links">
            <a href="{{ url('/form-validation') }}">/form-validation</a>
        </p>
    </section>

    {{-- 7 --}}
    <section id="t7">
        <h2><span class="num">7.</span> URL helpers</h2>
        <p><strong>What:</strong> Build links without hardcoding the domain. <code>url('/path')</code>, <code>asset('css/app.css')</code>.</p>
        <p class="links">
            <a href="{{ url('/url-check/landing') }}">/url-check/landing</a>
            <a href="{{ url('/url-check/about') }}">/url-check/about</a>
            <a href="{{ url('/url-check/products') }}">/url-check/products</a>
        </p>
    </section>

    {{-- 8 --}}
    <section id="t8">
        <h2><span class="num">8.</span> Named routes</h2>
        <p><strong>What:</strong> Give a route a short name. Change the path later without editing every Blade link.</p>
@verbatim
<pre>Route::get('/named-route/product/details/id', ...)->name('product');

route('product');           // builds the URL
to_route('product');        // redirect by name</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/named-route/welcome') }}">/named-route/welcome</a>
            <a href="{{ url('/named-route/product/add') }}">/named-route/product/add</a>
            <a href="{{ url('/named-route/product/find') }}">/named-route/product/find</a>
        </p>
    </section>

    {{-- 9 --}}
    <section id="t9">
        <h2><span class="num">9.</span> Route grouping</h2>
        <p><strong>What:</strong> Share a prefix / controller / middleware across many routes.</p>
@verbatim
<pre>Route::prefix('admin')->group(function () {
    Route::get('/users', ...);      // /admin/users
    Route::get('/dashboard', ...);  // /admin/dashboard
});</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/admin/users') }}">/admin/users</a>
            <a href="{{ url('/admin/dashboard') }}">/admin/dashboard</a>
            <a href="{{ url('/student/show') }}">/student/show</a>
        </p>
    </section>

    {{-- 10 --}}
    <section id="t10">
        <h2><span class="num">10.</span> Middleware</h2>
        <p class="muted">Branch: <code>Middleware</code> · register aliases in <code>bootstrap/app.php</code></p>
        <p><strong>What:</strong> Code that runs before (or after) the controller — login check, API key, locale, country.</p>
        <p><strong>Types here:</strong> alias (<code>access.key</code>) · grouped (<code>groupCheck</code>) · <code>SetLocale</code> on the web group. Aliases live in <code>bootstrap/app.php</code>.</p>
        <p class="links">
            <a href="{{ url('/middleware-demo') }}">/middleware-demo</a>
            <a href="{{ url('/middleware-demo/public') }}">/middleware-demo/public</a>
            <a href="{{ url('/middleware-demo/protected') }}">/middleware-demo/protected</a>
            <a href="{{ url('/middleware-group-check') }}">/middleware-group-check</a>
            <a href="{{ url('/multiple-middleware-to-route') }}">/multiple-middleware-to-route</a>
        </p>
    </section>

    {{-- 11 --}}
    <section id="t11">
        <h2><span class="num">11.</span> Database &amp; Eloquent models</h2>
        <p><strong>What:</strong> A <strong>model</strong> is a PHP class for a table. <code>User::all()</code> reads rows. Configure MySQL in <code>.env</code>.</p>
        <p class="links">
            <a href="{{ url('/user-db') }}">/user-db</a>
            <a href="{{ url('/user-list') }}">/user-list</a>
            <a href="{{ url('/customer-list') }}">/customer-list</a>
        </p>
        <p class="muted">Branch: <code>db-start</code> · files: <code>User_dbController</code>, <code>Customer_dbController</code></p>
    </section>

    <section id="t-http">
        <h2>HTTP Client — call an external API</h2>
        <p class="muted">Branch: <code>httpClient-api</code> · file: <code>httpController.php</code></p>
        <p><strong>What:</strong> Your Laravel app is the <strong>client</strong>. It sends HTTP requests to another server and reads JSON.</p>
        <p><strong>Why it is not the same as</strong> <a href="#t28">#28 APIs</a>: here you <em>consume</em> jsonplaceholder. There you <em>provide</em> <code>/api/*</code>.</p>
@verbatim
<pre>use Illuminate\Support\Facades\Http;

$response = Http::get('https://jsonplaceholder.typicode.com/users/1');
$data = $response->json();
return view('httpRes.users', ['data' => $data]);</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/http-controller') }}">/http-controller</a>
        </p>
    </section>

    {{-- 12 --}}
    <section id="t12">
        <h2><span class="num">12.</span> Seeders</h2>
        <p><strong>What:</strong> Migrations create <em>empty</em> tables. Seeders insert sample rows so lists have data.</p>
@verbatim
<pre>php artisan db:seed
php artisan db:seed --class=studentSeeder
php artisan migrate:fresh --seed</pre>
@endverbatim
        <p>Class: <code>database/seeders/studentSeeder.php</code> called from <code>DatabaseSeeder</code>. Branch: <code>seeder</code></p>
    </section>

    <section id="t-maint">
        <h2>Maintenance mode</h2>
        <p class="muted">README topic 13 · no demo URL — Artisan only</p>
        <p><strong>What:</strong> Temporarily close the site while you migrate or deploy. Visitors get HTTP 503.</p>
@verbatim
<pre>php artisan down
php artisan down --secret="learn123"   // you can still open /learn123
php artisan up                         // open the site again</pre>
@endverbatim
        <p>Optional flags: <code>--refresh</code>, <code>--retry</code>, <code>--redirect</code>, <code>--render</code>.</p>
    </section>

    {{-- 13 --}}
    <section id="t13">
        <h2><span class="num">13.</span> Query Builder (<code>DB::table</code>)</h2>
        <p><strong>What:</strong> Write queries without a model. Fast joins/reports. Skips accessors, mutators, and <code>$fillable</code>.</p>
@verbatim
<pre>DB::table('students')->where('id', 1)->first();
DB::table('students')->insert([...]);
DB::table('students')->where('id', 1)->update([...]);</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/db-query-builder/all') }}">/db-query-builder/all</a>
        </p>
    </section>

    {{-- 14 --}}
    <section id="t14">
        <h2><span class="num">14.</span> Eloquent CRUD (Student)</h2>
        <p><strong>What:</strong> Same jobs as Query Builder, but through the <code>Student</code> model: <code>all</code>, <code>find</code>, <code>create</code>, <code>update</code>, <code>delete</code>.</p>
        <p class="links">
            <a href="{{ url('/elqQueryBuilder/studentList') }}">/elqQueryBuilder/studentList</a>
        </p>
    </section>

    {{-- 15 --}}
    <section id="t15">
        <h2><span class="num">15.</span> HTTP methods (GET POST PUT PATCH DELETE)</h2>
        <p><strong>What:</strong> The verb says intent. Browsers only send GET/POST on forms — Blade uses <code>@@method('PUT')</code> to fake others. APIs/Postman send the real verb.</p>
        <p class="links">
            <a href="{{ url('/allroute') }}">/allroute</a>
        </p>
    </section>

    {{-- 16 --}}
    <section id="t16">
        <h2><span class="num">16.</span> Request class</h2>
        <p><strong>What:</strong> <code>Illuminate\Http\Request</code> is the incoming HTTP call: body, query, headers, files, IP, method, path.</p>
@verbatim
<pre>$request->all();
$request->input('email');
$request->query('page');
$request->header('Accept');
$request->method();
$request->ip();</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/request-methods') }}">/request-methods</a>
        </p>
    </section>

    {{-- 17 --}}
    <section id="t17">
        <h2><span class="num">17.</span> Sessions (web login, not API tokens)</h2>
        <p><strong>What:</strong> Server-side data stored per browser cookie. <code>Session::put</code> / <code>get</code> / <code>flash</code> / <code>flush</code>.</p>
        <p class="note">Different from Sanctum: sessions = cookies for browsers. Sanctum tokens = <code>Authorization: Bearer</code> for APIs.</p>
        <p class="links">
            <a href="{{ url('/sessions') }}">/sessions</a>
            <a href="{{ url('/sessions/login') }}">/sessions/login</a>
            <a href="{{ url('/sessions/theory') }}">/sessions/theory</a>
        </p>
    </section>

    {{-- 18 --}}
    <section id="t18">
        <h2><span class="num">18.</span> File uploads</h2>
        <p><strong>What:</strong> <code>$request-&gt;file('photo')-&gt;store('folder')</code>. Need <code>php artisan storage:link</code> for public URLs.</p>
        <p class="links">
            <a href="{{ url('/upload-files') }}">/upload-files</a>
            <a href="{{ url('/upload-files/display') }}">/upload-files/display</a>
        </p>
    </section>

    {{-- 19 --}}
    <section id="t19">
        <h2><span class="num">19.</span> Localization</h2>
        <p><strong>What:</strong> Same page, different language files in <code>lang/</code>. <code>SetLocale</code> middleware + session remembers the locale.</p>
        <p class="links">
            <a href="{{ url('/localization/welcome/en') }}">/localization/welcome/en</a>
            <a href="{{ url('/localization/welcome/ur') }}">/localization/welcome/ur</a>
        </p>
    </section>

    {{-- 20 --}}
    <section id="t20">
        <h2><span class="num">20.</span> Pagination</h2>
        <p><strong>What:</strong> <code>Student::paginate(10)</code> splits rows. Laravel reads <code>?page=2</code> automatically. Blade: <code>@{{ $students-&gt;links() }}</code>.</p>
        <p class="links">
            <a href="{{ url('/paginate/list') }}">/paginate/list</a>
            <a href="{{ url('/paginate/list?page=2') }}">/paginate/list?page=2</a>
        </p>
    </section>

    {{-- 21 --}}
    <section id="t21">
        <h2><span class="num">21.</span> Layout, CSS, JS</h2>
        <p><strong>What:</strong> One master Blade (<code>@@yield</code>) plus shared CSS/JS in <code>public/</code>. Child pages fill sections.</p>
        <p class="links">
            <a href="{{ url('/layout-demo') }}">/layout-demo</a>
            <a href="{{ url('/layout-demo/about') }}">/layout-demo/about</a>
            <a href="{{ url('/layout-demo/dashboard') }}">/layout-demo/dashboard</a>
            <a href="{{ url('/layout-demo/flash') }}">/layout-demo/flash</a>
        </p>
    </section>

    {{-- 22 --}}
    <section id="t22">
        <h2><span class="num">22.</span> Migrations</h2>
        <p><strong>What:</strong> Versioned PHP that creates/alters tables. <code>up()</code> applies, <code>down()</code> rolls back.</p>
@verbatim
<pre>php artisan make:migration create_students_table
php artisan migrate
php artisan migrate:rollback</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/migration') }}">/migration</a>
        </p>
    </section>

    {{-- 23 --}}
    <section id="t23">
        <h2><span class="num">23.</span> Accessors &amp; mutators</h2>
        <p><strong>What:</strong> Accessor formats on <strong>read</strong> (<code>$student-&gt;name</code> → uppercase). Mutator formats on <strong>write</strong> (store <code>ucfirst</code>). Live on <code>Student</code>.</p>
        <p class="links">
            <a href="{{ url('/accessors') }}">/accessors</a>
        </p>
    </section>

    {{-- 24 --}}
    <section id="t24">
        <h2><span class="num">24.</span> Eloquent relations</h2>
        <p><strong>What:</strong> Link tables as objects instead of writing SQL joins.</p>
        <table>
            <thead>
                <tr><th>Type</th><th>Idea</th><th>Demo</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>One-to-one</td>
                    <td>User <code>hasOne</code> Profile · Profile <code>belongsTo</code> User</td>
                    <td><a href="{{ url('/one-to-one') }}">/one-to-one</a></td>
                </tr>
                <tr>
                    <td>One-to-many</td>
                    <td>User <code>hasMany</code> Order</td>
                    <td><a href="{{ url('/one-to-many') }}">/one-to-many</a></td>
                </tr>
                <tr>
                    <td>Many-to-one</td>
                    <td>Same link from the child: Order <code>belongsTo</code> User</td>
                    <td><a href="{{ url('/many-to-one') }}">/many-to-one</a></td>
                </tr>
                <tr>
                    <td>Many-to-many</td>
                    <td>User ↔ Role via pivot · <code>attach</code> / <code>detach</code> / <code>sync</code></td>
                    <td><a href="{{ url('/many-to-many') }}">/many-to-many</a></td>
                </tr>
            </tbody>
        </table>
    </section>

    {{-- 25 --}}
    <section id="t25">
        <h2><span class="num">25.</span> Mail</h2>
        <p><strong>What:</strong> A <strong>Mailable</strong> class builds the email. <code>Mail::to($email)-&gt;send(new WelcomeMail(...))</code>. Set <code>MAIL_*</code> in <code>.env</code> (or <code>MAIL_MAILER=log</code> to write to the log file).</p>
        <p class="links">
            <a href="{{ url('/email') }}">/email</a>
        </p>
    </section>

    {{-- 26 --}}
    <section id="t26">
        <h2><span class="num">26.</span> Fluent strings</h2>
        <p><strong>What:</strong> Chain string helpers left-to-right: <code>Str::of($x)-&gt;trim()-&gt;lower()-&gt;slug()</code>.</p>
        <p class="links">
            <a href="{{ url('/fluent-string') }}">/fluent-string</a>
        </p>
    </section>

    {{-- 27 --}}
    <section id="t27">
        <h2><span class="num">27.</span> Route model binding</h2>
        <p><strong>What:</strong> URL <code>{user}</code> + type-hint <code>User $user</code> → Laravel loads the row (or 404). No manual <code>findOrFail</code>.</p>
@verbatim
<pre>Route::get('/rmb/implicit/{user}', [Controller::class, 'show']);
public function show(User $user) { return $user; }  // already loaded
// {user:name} searches users.name instead of id</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/rmb') }}">/rmb</a> (start here)
            <a href="{{ url('/user-route-model-binding-inline/Ali') }}">inline Blade demo</a>
        </p>
    </section>

    {{-- 28 --}}
    <section id="t28">
        <h2><span class="num">28.</span> APIs in the same project</h2>
        <p><strong>What:</strong> Same models/DB, but return JSON. Routes live in <code>routes/api.php</code> and get prefix <code>/api</code>.</p>
        <p><strong>Not the same as</strong> <a href="{{ url('/http-controller') }}">/http-controller</a> which <em>calls</em> an external API with <code>Http::get</code>.</p>
@verbatim
<pre>return response()->json(['data' => $users]);

// Postman: Accept: application/json
// PUT body: raw JSON (not form-data)</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/api-learning') }}">/api-learning</a> (hub)
            <a href="{{ url('/api/hello') }}">/api/hello</a>
            <a href="{{ url('/api/users') }}">/api/users</a>
            <a href="{{ url('/api/students') }}">/api/students</a>
        </p>
    </section>

    {{-- 29 --}}
    <section id="t29">
        <h2><span class="num">29.</span> Resource controller</h2>
        <p><strong>What:</strong> Standard CRUD method names. One line registers REST routes.</p>
@verbatim
<pre>php artisan make:controller X --resource   // 7 methods (Blade forms)
php artisan make:controller X --api        // 5 methods (JSON)

Route::apiResource('rsc-students', StudentResourceController::class);</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/resource-controller') }}">/resource-controller</a>
            <a href="{{ url('/api/rsc-students') }}">/api/rsc-students</a>
        </p>
    </section>

    {{-- 30 --}}
    <section id="t30">
        <h2><span class="num">30.</span> Sanctum (API login / signup for User)</h2>
        <p><strong>What:</strong> Token auth. Register or login returns a token. Later requests send <code>Authorization: Bearer {token}</code>. Middleware: <code>auth:sanctum</code>.</p>
        <p><strong>User model</strong> must <code>use HasApiTokens</code>. Controller: <code>SanctumController</code>.</p>
@verbatim
<pre>POST /api/auth/register   { "name":"Ali", "email":"a@b.c", "password":"secret123" }
POST /api/auth/login      { "email":"a@b.c", "password":"secret123" }
GET  /api/auth/me         Header: Authorization: Bearer {token}
POST /api/auth/logout     deletes this token only</pre>
@endverbatim
        <p class="links">
            <a href="{{ url('/sanctum') }}">/sanctum</a> (hub — Postman steps)
        </p>
        <p class="note">Do not open <code>/api/auth/me</code> in the address bar without a token — that is a 401 by design.</p>
    </section>

    <section>
        <h2>Suggested order (beginner path)</h2>
        <p>
            Routes → Controllers → Blade → Forms → Validation → Middleware →
            Database → HTTP Client (consume) → Query Builder → Eloquent →
            Relations → Sessions → API (provide JSON) → Resource controller → Sanctum.
        </p>
        <p>
            Hubs:
            <a href="{{ url('/') }}">Home index</a> ·
            <a href="{{ url('/api-learning') }}">API</a> ·
            <a href="{{ url('/resource-controller') }}">Resource</a> ·
            <a href="{{ url('/sanctum') }}">Sanctum</a> ·
            <a href="{{ url('/rmb') }}">Binding</a>
        </p>
        <p class="muted">This page: <code>LearningIndexController</code> · <code>resources/views/learning/index.blade.php</code> · <code>/</code> and <code>/learn</code>.</p>
    </section>
</body>
</html>
