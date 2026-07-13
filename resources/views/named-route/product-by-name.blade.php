<h2>Product Result Page</h2>

{{--
    $name comes from the URL route parameter {name}
    Example URL: /named-route/product/show/iPhone%2015
    Set by: to_route('product.show', ['name' => 'iPhone 15'])
--}}
<p>Product name from route parameter: <strong>{{ $name }}</strong></p>

<p>Full current URL: {{ url()->full() }}</p>

<p><a href="{{ route('product.find') }}">Search another product</a></p>
