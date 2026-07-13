<h2>Welcome page</h2>
<!-- 
    This is an example of using Laravel's route() helper in a Blade template to generate a URL for a named route.
    In the <a> tag below, the href attribute is set dynamically by calling route('pf'), where 'pf' is the name
    assigned to the route in your routes/web.php file using ->name('pf').

    The {{ }} Blade syntax will evaluate the PHP expression inside and print the result.
    This means that if the URL for the route ever changes in your routes file, you only need to update it there;
    all links using route('pf') will automatically point to the new URL, making your code more maintainable.
-->
<a href="{{ route('pf') }}">user details page</a>