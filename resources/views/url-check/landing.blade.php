<!-- Use url() when you know the literal path (e.g. /about).
Use route() for application pages because it uses named routes, making your code easier to maintain if URLs change.
Use asset() for static files (CSS, JavaScript, images).
Use url()->current(), url()->full(), and url()->previous() when you need information about the current or previous request rather than generating links.
-->
<h2>
    Landing Page
</h2>
<nav>
    <a href="{{ url('/url-check/landing') }}">Home</a>

    <a href="{{ url('/url-check/about') }}">About</a>
    <a href="{{ url('/url-check/products') }}">Products</a>
</nav>
<p>Current URL using url() helper: {{ url()->current() }}</p>
<p>Current URL using URL facade: {{ URL::current() }}</p>
<p>Full URL: {{ url()->full() }}</p>
<p>Full URL using URL facade: {{ URL::full() }}</p>
<p>Previous URL using url() helper: {{ url()->previous() }}</p>
<p>Previous URL using URL facade: {{ URL::previous() }}</p>

@if(request()->is('url-check/landing') && strtolower(request()->query('name', '')) === 'mobile')
    {{-- Redirect to the products page if ?name=mobile --}}

    <script>
        window.location.href = "{{ url('/url-check/products') }}?name={{ urlencode(request()->query('name')) }}";
    </script>
    {{-- Optionally, provide a fallback for non-JS users --}}
    <noscript>
        <meta http-equiv="refresh" content="0; url={{ url('/url-check/products') }}?name={{ urlencode(request()->query('name')) }}">
    </noscript>
@endif

    

{{-- 
    Difference between url()->current() and URL::current():

    1. Syntax/Usage:
       - url()->current() uses the global url() helper function, which returns an instance of the Illuminate\Routing\UrlGenerator, on which you call the current() method.
       - URL::current() uses Laravel's Facade class (URL), which statically accesses the same UrlGenerator's current() method.
    2. Dependency Injection and Testability:
       - url()->current() retrieves the current UrlGenerator from the service container, making it more flexible in some dependency injection and testing situations.
       - URL::current() is a facade, providing a static interface.
    3. Practical Result:
       - Both methods return the current URL as a string, so there's no difference in their output; they both provide the current URL for the request.
    4. Style/Preference:
       - Choosing between them is mostly a matter of preference, code style, or adherence to a project's conventions.
    5. Extensibility:
       - Using url() allows method chaining with other URL helper methods directly, e.g., url()->full() or url()->previous().
       - URL::current() remains statically scoped but offers the same methods.

    In summary: Both give the same result (current URL) but differ in syntax and style: url()->current() is helper-based (instance call); URL::current() is facade-based (static call).
--}}