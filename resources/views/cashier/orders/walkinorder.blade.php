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
                    <a onclick="window.location.href = '{{ route('cashier.walkin.orders.history') }}'">Walk-in Order</a>
                </li>
            </ul>
            <table class="table table-bordered mt-10">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Customer Name</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Delivery Method</th>
                        <th>Status</th>
                        <th>Amount Paid</th>
                        <th class="text-center pr-8">Action</th>
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
                            <td>₱{{ number_format($order->total, 2) }}</td>

                            <td class="text-center">
                                {{ match ($order->payment_method) {
                                    'bank_transfer' => 'Bank Transfer',
                                    'e_wallet' => 'E-Wallet',
                                    default => ucwords(str_replace('_', ' ', $order->payment_method)),
                                } }}
                            </td>

                            <td class="pl-12">{{ $order->delivery_method }}</td>
                            <td>
                                {{ $order->status == 'paid' ? 'Fully Paid' : 'Partial' }}</td>

                            <td class="text-right pr-12">
                                ₱{{ number_format($order->amount_paid, 2) }}
                            </td>
                            <td>
                                @if ($order->status != 'paid')
                                    <button type="button"
                                        class="btn text-white bg-orange-900 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5"
                                        data-modal-toggle="viewModal{{ $order->id }}">
                                        Completed
                                    </button>
                                @else
                                    <span class="text-green-700">Completed</span>
                                @endif
                            </td>
                        </tr>

                        <div id="viewModal{{ $order->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                            <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                <div class="relative bg-white rounded-lg shadow-sm">
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Confirm?
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                            data-modal-toggle="viewModal{{ $order->id }}">
                                            <i class='bx bx-x text-gray-500 text-2xl'></i>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4 p-4">
                                        <div class="col-span-1">

                                            <button type="button"
                                                class="btn text-white bg-green-900 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5"
                                                onclick="window.location.href='{{ route('cashier.walkin.orders.complete', $order->id) }}'">
                                                Confirm
                                            </button>
                                            <button type="button" data-modal-toggle="viewModal{{ $order->id }}"
                                                class="btn text-white bg-red-900 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#table-search').on('keyup', function() {
                        const searchInput = $(this).val().toLowerCase();
                        $('#product-table-body tr').filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                        });
                    });

                    $('[data-modal-target]').on('click', function() {
                        const modalId = $(this).data('modal-target');
                        $(`#${modalId}`).removeClass('hidden');
                    });

                    $('[data-modal-toggle]').on('click', function(e) {
                        const tagName = e.target.tagName.toLowerCase();

                        // Ignore if the clicked element is an input or select
                        if (tagName === 'textarea' || tagName === 'label' || tagName === 'input') return;
                        const modalId = $(this).data('modal-toggle');
                        $(`#${modalId}`).toggle('hidden');
                    });
                });
            </script>
</x-app-layout>
