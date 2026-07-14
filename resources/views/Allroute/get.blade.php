<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Catalog</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
        th, td { border: 1px solid #ccc; padding: 0.4rem 0.55rem; text-align: left; }
    </style>
</head>
<body>
    <p><span class="tag">Route::get · {{ $method }}</span></p>
    <h1>Product catalog</h1>
    <p>Opened with a normal link / address bar. GET is for reading data — nothing was changed on the server.</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Price (Rs)</th>
            <th>Stock</th>
        </tr>
        @foreach($products as $product)
            <tr>
                <td>{{ $product['id'] }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ number_format($product['price']) }}</td>
                <td>{{ $product['stock'] }}</td>
            </tr>
        @endforeach
    </table>

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
