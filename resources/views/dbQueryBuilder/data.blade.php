{{--
================================================================================
FILE: resources/views/dbQueryBuilder/data.blade.php
================================================================================

WHAT IS A BLADE FILE?
  Blade is Laravel's template language.
  A .blade.php file is mostly HTML, plus special Laravel tags that start with @
  or use {{ }} to print PHP values safely.

WHERE DOES THIS FILE FIT IN THE FLOW?
  Browser → Route (web.php) → Controller (DBQueryController) → THIS VIEW

  The controller always sends:
    $data       = list of user rows from Query Builder
    $pageTitle  = heading text for the page
    $info       = short explanation of which query ran

WORD BY WORD BLADE SYMBOLS YOU WILL SEE BELOW:
  {{ $var }}          = print the value of $var (HTML-escaped / safe)
  {!! $var !!}        = print raw HTML (we avoid this for user data)
  @foreach (...)      = loop over a list
  @endforeach         = end of that loop
  @if (...)           = run this block only if condition is true
  @endif              = end of if
  @csrf               = hidden security token required on POST forms
  route('name')       = build a URL from a named route in web.php
  old('field')        = keep the previous typed value after validation/redirect
  session('success')  = one-time flash message set by redirect()->with(...)

OVERALL PAGE FLOW (top to bottom):
  1) Header explains Query Builder
  2) Flash success message (after insert/update/delete)
  3) Toolbar: Search + Sort + Show All
  4) Insert form (CREATE)
  5) Data table with per-row Update + Delete (READ / UPDATE / DELETE)
================================================================================
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'DB Query Builder' }}</title>

    {{-- Google Fonts: purposeful typography (not a default system stack) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">

    <style>
        /*
        | Design tokens (CSS variables)
        | One clear visual direction: deep teal + warm paper — learning lab feel
        */
        :root {
            --ink: #1a2e2a;
            --muted: #5c736c;
            --paper: #f3f6f4;
            --surface: #ffffff;
            --line: #d5e0db;
            --accent: #0f6e56;
            --accent-soft: #d8f0e7;
            --warn: #9a3412;
            --warn-soft: #ffedd5;
            --danger: #b91c1c;
            --danger-soft: #fee2e2;
            --shadow: 0 18px 40px rgba(26, 46, 42, 0.08);
            --radius: 14px;
            --font-display: "Fraunces", Georgia, serif;
            --font-body: "DM Sans", sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            color: var(--ink);
            background:
                radial-gradient(ellipse 80% 50% at 10% -10%, #c8ebe0 0%, transparent 55%),
                radial-gradient(ellipse 60% 40% at 100% 0%, #e8f0ec 0%, transparent 45%),
                linear-gradient(180deg, #eef4f1 0%, var(--paper) 40%, #e7eeea 100%);
            min-height: 100vh;
            line-height: 1.5;
        }

        .wrap {
            width: min(1120px, calc(100% - 2rem));
            margin: 0 auto;
            padding: 2.5rem 0 4rem;
        }

        /* ---- Hero / brand first ---- */
        .hero {
            margin-bottom: 2rem;
            animation: rise 0.7s ease both;
        }

        .brand {
            font-family: var(--font-display);
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--accent);
            margin-bottom: 0.35rem;
        }

        .hero h1 {
            font-family: var(--font-display);
            font-size: clamp(1.25rem, 2.5vw, 1.6rem);
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.4rem;
        }

        .hero p {
            color: var(--muted);
            max-width: 42rem;
            font-size: 1.02rem;
        }

        .flow-strip {
            margin-top: 1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
            font-size: 0.85rem;
            color: var(--muted);
            animation: rise 0.7s ease 0.12s both;
        }

        .flow-strip span {
            background: var(--surface);
            border: 1px solid var(--line);
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
        }

        .flow-strip .arrow { border: none; background: transparent; padding: 0; color: var(--accent); }

        /* ---- Alerts ---- */
        .alert {
            margin: 1.25rem 0;
            padding: 0.9rem 1.1rem;
            border-radius: var(--radius);
            background: var(--accent-soft);
            color: var(--accent);
            border: 1px solid #a7d9c8;
            animation: rise 0.5s ease both;
        }

        .info-banner {
            margin-bottom: 1.5rem;
            padding: 0.85rem 1.1rem;
            background: var(--surface);
            border-left: 4px solid var(--accent);
            border-radius: 0 var(--radius) var(--radius) 0;
            box-shadow: var(--shadow);
            color: var(--muted);
            font-size: 0.95rem;
            animation: rise 0.6s ease 0.15s both;
        }

        /* ---- Panels / sections (one job each) ---- */
        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.35rem 1.5rem;
            margin-bottom: 1.35rem;
            animation: rise 0.7s ease both;
        }

        .panel:nth-of-type(1) { animation-delay: 0.18s; }
        .panel:nth-of-type(2) { animation-delay: 0.26s; }
        .panel:nth-of-type(3) { animation-delay: 0.34s; }

        .panel h2 {
            font-family: var(--font-display);
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        .panel .hint {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* ---- Forms ---- */
        .toolbar {
            display: grid;
            grid-template-columns: 1.4fr 1fr auto;
            gap: 0.85rem;
            align-items: end;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr) auto;
            gap: 0.75rem;
            align-items: end;
        }

        .field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        input, select {
            width: 100%;
            padding: 0.7rem 0.85rem;
            border: 1px solid var(--line);
            border-radius: 10px;
            font: inherit;
            color: var(--ink);
            background: #fbfcfb;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(15, 110, 86, 0.15);
            background: #fff;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.72rem 1.1rem;
            border: none;
            border-radius: 10px;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.15s ease, background 0.2s;
            white-space: nowrap;
        }

        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #0c5a46; }

        .btn-soft {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .btn-warn {
            background: var(--warn-soft);
            color: var(--warn);
            padding: 0.45rem 0.75rem;
            font-size: 0.85rem;
        }

        .btn-danger {
            background: var(--danger-soft);
            color: var(--danger);
            padding: 0.45rem 0.75rem;
            font-size: 0.85rem;
        }

        /* ---- Table ---- */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        th {
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            padding: 0.65rem 0.75rem;
            border-bottom: 2px solid var(--line);
        }

        td {
            padding: 0.85rem 0.75rem;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover { background: #f4faf7; }

        /* Clickable cells → filter routes */
        .link-cell {
            color: var(--accent);
            cursor: pointer;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-color: transparent;
            transition: text-decoration-color 0.2s;
        }

        .link-cell:hover { text-decoration-color: var(--accent); }

        .row-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            align-items: center;
        }

        .row-actions form {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            align-items: center;
        }

        .row-actions input {
            width: 7.5rem;
            padding: 0.4rem 0.55rem;
            font-size: 0.85rem;
        }

        .empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--muted);
        }

        .count {
            display: inline-block;
            margin-left: 0.5rem;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 900px) {
            .toolbar,
            .form-grid {
                grid-template-columns: 1fr;
            }
            .row-actions input { width: 100%; min-width: 0; }
        }
    </style>
</head>
<body>
<div class="wrap">

    {{-- ============================================================
         SECTION 1: HERO
         One job: introduce the brand + what this page teaches
         ============================================================ --}}
    <header class="hero">
        <div class="brand">Query Lab</div>
        <h1>{{ $pageTitle ?? 'Database Query Builder' }}</h1>
        <p>
            Learn Laravel Query Builder step by step: read rows, insert, update,
            delete, search with LIKE, and sort with orderBy — all without raw SQL.
        </p>

        {{-- Visual reminder of the request flow --}}
        <div class="flow-strip" aria-label="Request flow">
            <span>Browser</span>
            <span class="arrow">→</span>
            <span>Route (web.php)</span>
            <span class="arrow">→</span>
            <span>DBQueryController</span>
            <span class="arrow">→</span>
            <span>DB::table('users')</span>
            <span class="arrow">→</span>
            <span>This Blade view</span>
        </div>
    </header>

    {{-- Flash message after insert / update / delete --}}
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    {{-- Controller always sends $info explaining which query ran --}}
    @if(!empty($info))
        <div class="info-banner">
            <strong>Current query:</strong> {{ $info }}
        </div>
    @endif

    {{-- ============================================================
         SECTION 2: SEARCH + SORT + SHOW ALL
         One job: FILTER and ORDER the list (GET forms)
         GET forms do NOT need @csrf (only POST/PUT/DELETE/PATCH do)
         ============================================================ --}}
    <section class="panel">
        <h2>Search &amp; Sort</h2>
        <p class="hint">
            Search uses <code>where(... 'like', '%keyword%')</code>.
            Sort uses <code>orderBy($column, $order)</code>.
        </p>

        <div class="toolbar">
            {{-- SEARCH FORM
                 method="get"  → values appear in the URL as ?keyword=...
                 action=route  → opens /db-query-builder/search
            --}}
            <form action="{{ route('dbQueryBuilder.search') }}" method="get">
                <div class="field">
                    <label for="keyword">Keyword</label>
                    <input
                        id="keyword"
                        type="text"
                        name="keyword"
                        placeholder="Search name, email, phone"
                        value="{{ $keyword ?? request('keyword') }}"
                        required
                    >
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top:0.55rem;width:100%;">Search</button>
            </form>

            {{-- SORT FORM --}}
            <form action="{{ route('dbQueryBuilder.sort') }}" method="get">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.55rem;">
                    <div class="field">
                        <label for="column">Column</label>
                        <select id="column" name="column">
                            {{-- selected() helper marks the current choice --}}
                            <option value="id" {{ ($column ?? '') === 'id' ? 'selected' : '' }}>id</option>
                            <option value="name" {{ ($column ?? '') === 'name' ? 'selected' : '' }}>name</option>
                            <option value="email" {{ ($column ?? '') === 'email' ? 'selected' : '' }}>email</option>
                            <option value="phone" {{ ($column ?? '') === 'phone' ? 'selected' : '' }}>phone</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="order">Order</label>
                        <select id="order" name="order">
                            <option value="asc" {{ ($order ?? '') === 'asc' ? 'selected' : '' }}>asc (A→Z)</option>
                            <option value="desc" {{ ($order ?? '') === 'desc' ? 'selected' : '' }}>desc (Z→A)</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top:0.55rem;width:100%;">Sort</button>
            </form>

            {{-- Back to full list: just a link to the named route --}}
            <a class="btn btn-soft" href="{{ route('dbQueryBuilder.all') }}">Show All</a>
        </div>
    </section>

    {{-- ============================================================
         SECTION 3: INSERT (CREATE)
         One job: add a new row with DB::table(...)->insert([...])
         POST forms MUST include @csrf or Laravel blocks the request
         ============================================================ --}}
    <section class="panel">
        <h2>Insert New User</h2>
        <p class="hint">
            Submits POST to <code>dbQueryBuilder.insert</code>.
            Controller reads <code>$request-&gt;name</code> etc., then calls <code>insert()</code>.
        </p>

        <form action="{{ route('dbQueryBuilder.insert') }}" method="post" class="form-grid">
            {{-- @csrf prints a hidden _token field. Prevents CSRF attacks. --}}
            @csrf

            <div class="field">
                <label for="ins-name">Name</label>
                <input id="ins-name" type="text" name="name" placeholder="Ali Khan" required>
            </div>
            <div class="field">
                <label for="ins-email">Email</label>
                <input id="ins-email" type="email" name="email" placeholder="ali@mail.com" required>
            </div>
            <div class="field">
                <label for="ins-phone">Phone</label>
                <input id="ins-phone" type="text" name="phone" placeholder="03001234567" required>
            </div>
            <div class="field">
                <label for="ins-password">Password</label>
                <input id="ins-password" type="password" name="password" placeholder="optional" value="password123">
            </div>
            <button class="btn btn-primary" type="submit">Insert</button>
        </form>
    </section>

    {{-- ============================================================
         SECTION 4: DATA TABLE (READ + UPDATE + DELETE per row)
         One job: display $data and let you edit/delete each row
         ============================================================ --}}
    <section class="panel">
        <h2>
            Results
            {{-- count($data) = how many rows came back from Query Builder --}}
            <span class="count">{{ is_countable($data) ? count($data) : 0 }} rows</span>
        </h2>
        <p class="hint">
            Click ID / Name / Email / Phone to filter with <code>where()</code>.
            Use the row forms for <code>update()</code> and <code>delete()</code>.
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions (Update / Delete)</th>
                    </tr>
                </thead>
                <tbody>
                    {{--
                      @@forelse($data as $user)
                        forelse is a Blade directive that checks if the $data variable is not empty.
                        if it is not empty, it will loop through the $data variable and display the data in a table.
                        if it is empty, it will display a message saying "No rows found. Insert a user above, or click Show All."
                        $data is a list of many rows. @forelse takes them one by one. Each time through the loop, $user is only one row, and that one row becomes one <tr> table line.
                        @@forelse($data as $user)
                        @@empty
                        @@endforelse
                        $data  = collection/list from the controller
                        $user  = ONE row object for the current loop
                        $user->id means: read the "id" property of that row
                    --}}
                    @forelse($data as $user)
                        <tr>
                            {{--
                              onclick + route(...) = when you click the cell,
                              browser navigates to the filter URL for that value.
                              route('dbQueryBuilder.byid', $user->id)
                              builds: /db-query-builder/byid/3  (if id is 3)
                            --}}
                            <td>
                                <span
                                    class="link-cell"
                                    onclick="window.location.href='{{ route('dbQueryBuilder.byid', $user->id) }}'"
                                    title="where('id', {{ $user->id }})"
                                >{{ $user->id }}</span>
                            </td>
                            <td>
                                <span
                                    class="link-cell"
                                    onclick="window.location.href='{{ route('dbQueryBuilder.byname', $user->name) }}'"
                                    title="where('name', '{{ $user->name }}')"
                                >{{ $user->name }}</span>
                            </td>
                            <td>
                                <span
                                    class="link-cell"
                                    onclick="window.location.href='{{ route('dbQueryBuilder.byemail', $user->email) }}'"
                                    title="where('email', '{{ $user->email }}')"
                                >{{ $user->email }}</span>
                            </td>
                            <td>
                                <span
                                    class="link-cell"
                                    onclick="window.location.href='{{ route('dbQueryBuilder.byphone', $user->phone ?? '') }}'"
                                    title="where('phone', '{{ $user->phone ?? '' }}')"
                                >{{ $user->phone ?? '—' }}</span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    {{--
                                      UPDATE FORM (inside the loop so EACH row has its own id)
                                      Old bug: update/delete used $user->id OUTSIDE the loop,
                                      which always meant the LAST row only.
                                      Now each row has its own forms with the correct id.
                                    --}}
                                    <form action="{{ route('dbQueryBuilder.update', $user->id) }}" method="post">
                                        @csrf
                                        <input type="text" name="name" value="{{ $user->name }}" required>
                                        <input type="email" name="email" value="{{ $user->email }}" required>
                                        <input type="text" name="phone" value="{{ $user->phone ?? '' }}" required>
                                        <button class="btn btn-warn" type="submit">Update</button>
                                    </form>

                                    {{-- DELETE FORM — POST + confirm() so you don't delete by accident --}}
                                    <form
                                        action="{{ route('dbQueryBuilder.delete', $user->id) }}"
                                        method="post"
                                        onsubmit="return confirm('Delete user #{{ $user->id }}? This cannot be undone.');"
                                    >
                                        @csrf
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- @forelse ... @empty runs when $data has zero rows --}}
                        <tr>
                            <td colspan="5" class="empty">
                                No rows found. Insert a user above, or click Show All.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
</body>
</html>
