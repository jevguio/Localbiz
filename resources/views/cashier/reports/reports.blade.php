<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Orders Report</h2>
    <table>
        <thead>
            <tr>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Location</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $orderItem)
                <tr>
                    <td>{{ $orderItem->order->order_number }}</td>
                    <td>{{ $orderItem->order->user->name }}</td>
                    <td>{{ $orderItem->product->name }}</td>
                    <td>{{ $orderItem->price }}</td>
                    <td>{{ $orderItem->product->location->name }}</td>
                    <td>{{ $orderItem->order->total_amount }}</td>
                    <td>{{ $orderItem->order->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
