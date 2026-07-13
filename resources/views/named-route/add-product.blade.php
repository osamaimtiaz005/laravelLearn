<h2>Add Product Form</h2>

{{--
    route('product.save') generates the form action URL from the named route in web.php.
    This is NOT a redirect — it only builds the URL string for the <form action="...">.
--}}
<form method="POST" action="{{ route('product.save') }}">
    @csrf
    <label>
        Product name:
        <input type="text" name="name" value="{{ old('name') }}" required>
    </label>
    <br><br>
    <button type="submit">Save Product</button>
</form>

<p><a href="{{ route('product') }}">Back to product page</a></p>
