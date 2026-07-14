<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 0.35rem 0.5rem; text-align: left; }
    </style>
</head>
<body>
    <p><span class="tag">Route::post · {{ $method }}</span></p>
    <h1>Order placed</h1>
    <p>POST created a new order (saved in session). Refreshing the browser alone does not create another order unless you submit the form again.</p>

    <div class="box">
        <p><strong>Order #{{ $order['id'] }}</strong></p>
        <p>Customer: {{ $order['customer'] }}</p>
        <p>Product: {{ $order['product'] }} × {{ $order['qty'] }}</p>
        <p>Time: {{ $order['placed_at'] }}</p>
    </div>

    <h2>All orders in this session</h2>
    <table>
        <tr><th>#</th><th>Customer</th><th>Product</th><th>Qty</th><th>Time</th></tr>
        @foreach($orders as $row)
            <tr>
                <td>{{ $row['id'] }}</td>
                <td>{{ $row['customer'] }}</td>
                <td>{{ $row['product'] }}</td>
                <td>{{ $row['qty'] }}</td>
                <td>{{ $row['placed_at'] }}</td>
            </tr>
        @endforeach
    </table>

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
