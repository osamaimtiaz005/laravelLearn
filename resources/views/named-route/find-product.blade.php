<h2>Find Product by Name</h2>

{{--
    User types a product name → form POSTs to product.find.submit
    Controller will redirect using:
    to_route('product.show', ['name' => $productName])
--}}
<form method="POST" action="{{ route('product.find.submit') }}">
    @csrf
    <label>
        Type product name:
        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. iPhone 15" required>
    </label>
    <br><br>
    <button type="submit">Search with to_route()</button>
</form>

<p><a href="{{ route('named-route.welcome') }}">Back to welcome</a></p>
