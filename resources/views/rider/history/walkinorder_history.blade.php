<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Walk-in Orders</h2>
            </div>

            <!-- Navigation Menu -->
            <ul
                class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center mx-auto font-[sans-serif] mt-4">


                @php
                    $status = request('status');

                @endphp



                <li
                    class="{{ $status == 'delivered' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 hover:text-orange-900' }} px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a href="{{ route('rider.history.order', ['status' => 'delivered']) }}">Delivered</a>
                </li>

                <li
                    class="{{ $status == 'cancelled' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 hover:text-orange-900' }} px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a href="{{ route('rider.history.order', ['status' => 'cancelled']) }}">Cancelled</a>
                </li>
                <li
                    class=" {{ Route::currentRouteName() === 'rider.history.walkin' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}  px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a href="{{ route('rider.history.walkin') }}">Walk-in History</a>
                </li>

            </ul>

            <table class="table table-bordered mt-10">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Delivery Method</th>
                        <th>Payment</th>
                        <th>Amount Paid</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Delivery Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}</td>

                            <td>{{ $order->customer_name }}</td>
                            <td>
                                <ul>
                                    @foreach ($order->items as $item)
                                        <li>{{ $item['name'] }} x {{ $item['quantity'] }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $order->delivery_method }}</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->amount_paid }}</td>
                            <td>
                                {{ $order->status =="paid"?"Fully Paid":'Partial'}}
                            </td>
                            <td>{{ $order->delivery_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

</x-app-layout>
