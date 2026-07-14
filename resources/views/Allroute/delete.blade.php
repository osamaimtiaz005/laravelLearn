<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart Item Removed</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 0.35rem 0.5rem; text-align: left; }
    </style>
</head>
<body>
    <p><span class="tag">Route::delete · {{ $method }}</span></p>
    <h1>Item removed from cart</h1>

    <div class="box">
        @if($removed)
            <p>Deleted: <strong>{{ $removed['name'] }}</strong> (ID {{ $removed['id'] }})</p>
        @else
            <p>No item matched that ID (maybe already removed).</p>
        @endif
    </div>

    <h2>Cart now</h2>
    @if(count($cart))
        <table>
            <tr><th>ID</th><th>Item</th><th>Price</th></tr>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>Rs {{ $item['price'] }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Cart is empty.</p>
    @endif

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
