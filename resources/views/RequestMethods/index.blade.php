<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Class Methods</title>
    <style>
        body { font-family: Georgia, "Times New Roman", serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; color: #222; }
        h1 { margin-bottom: 0.25rem; }
        .intro { color: #555; margin-bottom: 1.25rem; }
        section { margin-bottom: 1.6rem; padding-bottom: 1.1rem; border-bottom: 1px solid #ddd; }
        h2 { font-size: 1.1rem; margin: 0 0 0.35rem; }
        .tag { display: inline-block; font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase; background: #1a3a5c; color: #fff; padding: 0.15rem 0.45rem; margin-right: 0.35rem; }
        .life { color: #555; font-size: 0.95rem; margin: 0.25rem 0 0.7rem; }
        code { background: #f3f3f3; padding: 0.1rem 0.35rem; }
        form { display: flex; flex-wrap: wrap; gap: 0.45rem; align-items: center; margin-top: 0.35rem; }
        input, button { padding: 0.4rem 0.55rem; font: inherit; }
        a.btn, button { display: inline-block; padding: 0.4rem 0.8rem; background: #1a3a5c; color: #fff; text-decoration: none; border: 0; cursor: pointer; }
        .hint { font-size: 0.88rem; color: #666; margin-top: 0.4rem; }
        ul.methods { columns: 2; font-size: 0.9rem; color: #333; }
    </style>
</head>
<body>
    <h1>Request class — all common methods</h1>
    <p class="intro">
        Same HTTP verbs as <a href="{{ route('allroute.index') }}">/allroute</a>, but each action dumps
        <code>Illuminate\Http\Request</code> helpers (<code>input()</code>, <code>all()</code>, <code>method()</code>, …).
    </p>

    <section>
        <h2>Methods you will see on the result page</h2>
        <ul class="methods">
            <li><code>method()</code> / <code>isMethod()</code></li>
            <li><code>all()</code> / <code>input()</code> / <code>get()</code></li>
            <li><code>query()</code> / <code>post()</code></li>
            <li><code>only()</code> / <code>except()</code> / <code>keys()</code></li>
            <li><code>has()</code> / <code>filled()</code> / <code>missing()</code></li>
            <li><code>boolean()</code> / <code>integer()</code></li>
            <li><code>path()</code> / <code>url()</code> / <code>fullUrl()</code></li>
            <li><code>segment()</code> / <code>is()</code> / <code>routeIs()</code></li>
            <li><code>ip()</code> / <code>userAgent()</code> / <code>header()</code></li>
            <li><code>hasFile()</code> / <code>file()</code></li>
            <li><code>validate()</code></li>
        </ul>
    </section>

    {{-- GET with query string --}}
    <section>
        <h2><span class="tag">get</span> Search (query string)</h2>
        <p class="life">Real life: <code>/products?q=mouse</code> — read with <code>$request->query('q')</code>.</p>
        <form action="{{ route('requestMethods.get') }}" method="get">
            <input type="text" name="q" placeholder="Search keyword" value="mouse">
            <input type="text" name="name" placeholder="name (also in query)" value="Ali">
            <button type="submit">GET + inspect Request</button>
        </form>
        <p class="hint">Or open: <a href="{{ route('requestMethods.get', ['q' => 'keyboard', 'name' => 'Sara']) }}">ready-made link</a></p>
    </section>

    {{-- POST form --}}
    <section>
        <h2><span class="tag">post</span> Signup / create form</h2>
        <p class="life">Real life: register form — <code>$request->input('email')</code>, <code>validate()</code>.</p>
        <form action="{{ route('requestMethods.post') }}" method="post">
            @csrf
            <input type="text" name="name" placeholder="Name" value="Ali Khan" required>
            <input type="email" name="email" placeholder="Email" value="ali@example.com" required>
            <input type="text" name="city" placeholder="City" value="Lahore">
            <input type="number" name="qty" placeholder="Qty" value="2" min="0">
            <label><input type="checkbox" name="subscribe" value="1" checked> Subscribe</label>
            <button type="submit">POST + inspect Request</button>
        </form>
    </section>

    {{-- PUT --}}
    <section>
        <h2><span class="tag">put</span> Replace profile fields</h2>
        <p class="life">Real life: full profile save — still read body with <code>$request->all()</code>.</p>
        <form action="{{ route('requestMethods.put') }}" method="post">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="Ali Khan" required>
            <input type="email" name="email" value="ali@example.com" required>
            <input type="text" name="city" value="Karachi" required>
            <button type="submit">PUT + inspect Request</button>
        </form>
    </section>

    {{-- PATCH --}}
    <section>
        <h2><span class="tag">patch</span> Update one field</h2>
        <p class="life">Real life: change city only — <code>$request->only(['city'])</code>.</p>
        <form action="{{ route('requestMethods.patch') }}" method="post">
            @csrf
            @method('PATCH')
            <input type="text" name="city" value="Islamabad" required>
            <input type="hidden" name="name" value="(unchanged)">
            <button type="submit">PATCH + inspect Request</button>
        </form>
    </section>

    {{-- DELETE --}}
    <section>
        <h2><span class="tag">delete</span> Delete by id in body</h2>
        <p class="life">Real life: delete button — <code>$request->integer('id')</code>.</p>
        <form action="{{ route('requestMethods.delete') }}" method="post">
            @csrf
            @method('DELETE')
            <input type="number" name="id" value="5" required>
            <input type="text" name="name" value="item-to-delete">
            <button type="submit">DELETE + inspect Request</button>
        </form>
    </section>

    {{-- MATCH --}}
    <section>
        <h2><span class="tag">match</span> Same URL for GET and POST</h2>
        <p class="life">Use <code>$request->isMethod('post')</code> to branch logic.</p>
        <a class="btn" href="{{ route('requestMethods.match') }}">MATCH as GET</a>
        <form action="{{ route('requestMethods.match') }}" method="post">
            @csrf
            <input type="text" name="name" value="Contact Ali" required>
            <input type="email" name="email" value="ali@example.com" required>
            <button type="submit">MATCH as POST</button>
        </form>
    </section>

    {{-- ANY --}}
    <section>
        <h2><span class="tag">any</span> Webhook-style endpoint</h2>
        <p class="life">Inspect <code>$request->method()</code> + payload for any verb.</p>
        <a class="btn" href="{{ route('requestMethods.any') }}">ANY as GET</a>
        <form action="{{ route('requestMethods.any') }}" method="post">
            @csrf
            <input type="text" name="event" value="payment.success">
            <input type="text" name="name" value="webhook">
            <button type="submit">ANY as POST</button>
        </form>
        <form action="{{ route('requestMethods.any') }}" method="post">
            @csrf
            @method('PUT')
            <input type="text" name="event" value="payment.updated">
            <input type="text" name="name" value="webhook-put">
            <button type="submit">ANY as PUT</button>
        </form>
    </section>

    {{-- FILE --}}
    <section>
        <h2><span class="tag">file</span> Upload + hasFile / file()</h2>
        <p class="life">Real life: profile photo — <code>$request->hasFile('photo')</code>, <code>$request->file('photo')</code>.</p>
        <form action="{{ route('requestMethods.upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="Caption" value="My photo">
            <input type="file" name="photo" accept="image/*">
            <button type="submit">Upload + inspect Request</button>
        </form>
        <p class="hint">Form must use <code>enctype="multipart/form-data"</code> for files.</p>
    </section>
</body>
</html>
