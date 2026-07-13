<h2>Product details page</h2>

{{-- Flash message set by to_route('product')->with('success', ...) in saveProduct() --}}
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if (session('product_name'))
    <p>Last saved product: <strong>{{ session('product_name') }}</strong></p>
@endif

<p><a href="{{ route('product.add') }}">Add a new product (form demo)</a></p>
