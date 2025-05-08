<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Walk-in Orders</h2>
            </div>

            <ul
                class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center rounded-lg mx-auto font-[sans-serif] mt-4">
                <li
                    class="text-gray-400
        {{ Route::currentRouteName() == 'cashier.orders.history' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}
        hover:text-red-900 px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a onclick="window.location.href = '{{ route('cashier.orders.history') }}'">Order
                        History</a>
                </li>
                <li
                    class="text-gray-400
    {{ Route::currentRouteName() == 'cashier.walkin.orders.history' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}
    hover:text-red-900 px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a onclick="window.location.href = '{{ route('cashier.walkin.orders.history') }}'">Walk-in Order
                        History</a>
                </li>
            </ul>
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
