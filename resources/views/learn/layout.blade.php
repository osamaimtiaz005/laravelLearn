{{--
    =============================================================================
    HOW A LAYOUT IS RENDERED (Laravel Blade) — read this top-to-bottom
    =============================================================================
    1) A route or controller runs: return view('learn.blade-basics', $data);
       — view() is a Laravel helper: it resolves the Blade file under resources/views/
         (dots = folders, so learn.layout → learn/layout.blade.php).

    2) The RETURNED template is the *child* (e.g. learn/blade-basics.blade.php).
       That file starts with @extends('learn.layout').
       — @extends means: “do not output HTML alone; wrap me inside this parent layout.”

    3) Laravel builds the layout first conceptually, then *injects* child pieces:
       — Where the layout has @yield('content'), the compiler replaces that with the
         HTML from the child’s @section('content') ... @endsection block.
       — @yield('title', 'default') prints the child’s @section('title') value; if the
         child did not define that section, the second argument is used.

    4) Optional: @push('head') ... @endpush in a child stacks fragments; @stack('head')
       in the layout prints them in order (good for page-specific CSS/JS).

    5) The final string is one merged HTML document → sent to the browser.

    Interview tip: @extends must point to the *outer* shell; each child page repeats
    @extends + @section blocks; the layout stays DRY (nav, html, head once).
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--
        @yield('title', 'Laravel learning views')
        — yield: “print whatever the child defined for this section name.”
        — Second argument: default string if child has no @section('title').
    --}}
    <title>@yield('title', 'Laravel learning views')</title>
    <style>
        :root { color-scheme: light dark; font-family: system-ui, sans-serif; }
        body { margin: 0 auto; max-width: 52rem; padding: 1.25rem 1rem 3rem; line-height: 1.55; }
        nav.learn-nav { display: flex; flex-wrap: wrap; gap: .5rem 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #ccc; }
        nav.learn-nav a { color: #2563eb; }
        code, pre { font-size: .9em; }
        .card { border: 1px solid #ccc; border-radius: .5rem; padding: 1rem; margin: 1rem 0; background: rgba(127,127,127,.06); }
        kbd { background: #eee; padding: .1rem .35rem; border-radius: .25rem; }
        details.learn-glossary { margin-bottom: 1.25rem; border: 1px solid #999; border-radius: .5rem; padding: .75rem 1rem; background: rgba(0,80,180,.06); }
        details.learn-glossary summary { cursor: pointer; font-weight: 600; }
        details.learn-glossary dl { margin: .75rem 0 0; display: grid; gap: .5rem 1rem; }
        details.learn-glossary dt { font-weight: 600; }
        details.learn-glossary dd { margin: 0 0 0 1rem; }
    </style>
    {{--
        @stack('name') — outputs all @push('name') fragments from child views, in order.
        If nothing was pushed, this outputs nothing. Used for extra head tags per page.
    --}}
    @stack('head')
</head>
<body>
    <nav class="learn-nav" aria-label="Learning views">
        {{--
            {{ url('/path') }} — Laravel url() helper: builds an absolute URL to the given path
            using your app URL from config (APP_URL). Safer than hard-coding domains.
            Output is passed through e() inside {{ }} so it is HTML-safe.
        --}}
        <a href="{{ url('/learn/blade-basics') }}">Blade basics</a>
        <a href="{{ url('/learn/blade-loops-data') }}">Loops &amp; data</a>
        <a href="{{ url('/learn/interview-checklist') }}">Interview checklist</a>
    </nav>


    {{-- 
        Explaining <summary>, <details>, <dl>, and <dt> HTML tags for beginners:

        <details> ... </details>
            - This tag creates an expandable/collapsible section.
            - By default, the content inside <details> is hidden, and the user can click to reveal it.
            - Commonly used when you want to hide additional information unless the user requests it (like FAQs).

        <summary> ... </summary>
            - The first child inside <details>.
            - Acts as the clickable heading for the collapsible section.
            - When clicked, it toggles the visibility of the rest of the <details> content.

            Example:
                <details>
                    <summary>More info</summary>
                    This is hidden until you click!
                </details>

        <dl> ... </dl>
            - "Definition List": Used to group a set of terms and their definitions, like a glossary or variable descriptions.
            - Inside <dl>, use <dt> for the term and <dd> for the description.

            Example:
                <dl>
                    <dt>HTML</dt>
                    <dd>Standard language for documents designed to be displayed in a web browser.</dd>
                    <dt>CSS</dt>
                    <dd>Language used to style the layout of web pages.</dd>
                </dl>

        <dt> ... </dt>
            - "Definition Term": Used inside <dl> to indicate the item being defined.
            - Usually followed by <dd>, which provides the explanation or value of that term.

        In summary:
            - <details> + <summary> = Expandable/collapsible sections for optional info.
            - <dl>, <dt>, <dd> = Semantic lists for terms and their meanings or explanations.
            - Using these tags helps make your HTML more accessible and organized, and they provide built-in browser behavior (like expand/collapse) with no JavaScript needed.
    --}}
    <details class="learn-glossary">
        <summary>Layout rendering &amp; Blade / Laravel keywords (click to expand)</summary>
        <dl>
            <dt>view(&apos;dot.name&apos;, $data)</dt>
            <dd>PHP helper: loads Blade from resources/views; dot is folder (learn/layout → learn/layout.blade.php). Second arg passes variables into all templates in that render.</dd>
            <dt>@@extends(&apos;learn.layout&apos;)</dt>
            <dd>Child declares its parent layout. In Blade source you write <code>&#64;extends('learn.layout')</code> (we show @@ in visible copy so this page does not re-parse a fake directive).</dd>
            <dt>@@section / @@endsection</dt>
            <dd>Child defines named chunks. Layout prints them with @@yield of the same name.</dd>
            <dt>@@yield</dt>
            <dd>In the layout: placeholder where a child section is inserted when the response is composed.</dd>
            <dt>@@stack / @@push</dt>
            <dd>Stack is a multi-slot append list in the layout; child pages @@push named fragments (often scripts or styles) that appear at the stack location.</dd>
            <dt><code>@{{ $variable }}</code> (in real Blade: one <code>@</code> only when you need a literal brace in output; normally write two curly braces around the expression.)</dt>
            <dd>Blade echo: runs the PHP expression and escapes HTML via e() for XSS safety. The leading <code>@</code> here only escapes the next <code>{{</code> so this glossary can display the syntax.</dd>
            <dt>url(), route()</dt>
            <dd>url(&apos;/path&apos;) builds a URL from a path; route(&apos;name&apos;) uses a named route — better when paths change.</dd>
        </dl>
    </details>

    <main>
        {{-- Main page body from the child view’s @section('content') --}}
        @yield('content')
    </main>
</body>
</html>
