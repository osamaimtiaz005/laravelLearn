<h2>Customer List from Database</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Orders Count</th>
    </tr>
    @foreach($customers as $customer)
    <tr>
        <td>{{ $customer->id }}</td>    
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->email }}</td>
        <td>{{ $customer->order_count }}</td>
    </tr>
    @endforeach
</table>  