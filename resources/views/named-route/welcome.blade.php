<h2>Welcome page</h2>
<!-- 
    This is an example of using Laravel's route() helper in a Blade template to generate a URL for a named route.
    In the <a> tag below, the href attribute is set dynamically by calling route('pf'), where 'pf' is the name
    assigned to the route in your routes/web.php file using ->name('pf').

    The @{{ }} Blade syntax will evaluate the PHP expression inside and print the result.
    This means that if the URL for the route ever changes in your routes file, you only need to update it there;
    all links using route('pf') will automatically point to the new URL, making your code more maintainable.
-->
<a href="{{ route('pf') }}"
    style="display: inline-block; margin: 8px 0; padding: 6px 14px; background: #f4f7fa; color: #2471a3; text-decoration: none; border-radius: 4px; border: 1px solid #cfe2ff;"

>user details page</a>

<a href="{{ route('product') }}" 
    style="display: inline-block; margin: 8px 0; padding: 6px 14px; background: #f4f7fa; color: #2471a3; text-decoration: none; border-radius: 4px; border: 1px solid #cfe2ff;"

>product page</a>

<a href="{{ route('product.add') }}"
    style="display: inline-block; margin: 8px 0; padding: 6px 14px; background: #f4f7fa; color: #2471a3; text-decoration: none; border-radius: 4px; border: 1px solid #cfe2ff;"

>add product (to_route demo)</a>

<a 
    href="{{ route('product.find') }}"
    style="display: inline-block; margin: 8px 0; padding: 6px 14px; background: #f4f7fa; color: #2471a3; text-decoration: none; border-radius: 4px; border: 1px solid #cfe2ff;"
>
    Find Product by Name
    <span style="font-size: 0.92em; color: #888;">(to_route with params)</span>
</a>