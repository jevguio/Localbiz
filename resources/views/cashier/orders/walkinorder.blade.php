<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Walk-in Orders</h2>
            </div>

            @include('cashier.orders.breadcrumbs')
            <table class="table table-bordered mt-10">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Delivery Method</th>
                        <th>Status</th>
                        <th>Amount Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>
                                <ul>
                                    @foreach ($order->items as $item)
                                        <li>{{ $item['name'] }} x {{ $item['quantity'] }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td class="pl-12">{{ $order->payment_method }}</td>
                            <td class="pl-12">{{ $order->delivery_method }}</td>
                            <td>{{ $order->status }}</td>
                           
                            <td>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

</x-app-layout>
