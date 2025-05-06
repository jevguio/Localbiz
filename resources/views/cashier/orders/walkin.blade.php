<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Walk-in Orders</h2>
            </div>

            @include('cashier.orders.breadcrumbs')
            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">
                <form class="w-full flex ml-0 mb-4">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative flex-1">

                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class='bx bx-search text-gray-500 text-2xl'></i>
                        </div>
                        <input type="search" id="table-search"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="All Categories">
                        <button type="button" id="filter-btn"
                            class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-600 hover:text-gray-900">
                            <i class='bx bx-filter text-2xl'></i>
                        </button>
                        <div id="filter-dropdown"
                            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                            <ul class="py-2 text-sm text-gray-700">
                                <a href="{{ route('seller.order-history') }}?filter=all"
                                    class="block w-full px-4 py-2 hover:bg-gray-100 cursor-pointer">All</a>
                                <a href="{{ route('seller.order-history') }}?filter=processing"
                                    class="block w-full px-4 py-2 hover:bg-gray-100 cursor-pointer">Processing</a>
                                <a href="{{ route('seller.order-history') }}?filter=receiving"
                                    class="block w-full px-4 py-2 hover:bg-gray-100 cursor-pointer">Receiving</a>
                                <a href="{{ route('seller.order-history') }}?filter=delivered"
                                    class="block w-full px-4 py-2 hover:bg-gray-100 cursor-pointer">Delivered</a>
                                <a href="{{ route('seller.order-history') }}?filter=cancelled"
                                    class="block w-full px-4 py-2 hover:bg-gray-100 cursor-pointer">Cancelled</a>
                            </ul>
                        </div>
                    </div>
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class='bx bx-search text-gray-500 text-2xl'></i>
                        </div>
                        <input type="search" id="table-search"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search for order....">
                    </div>
                </form>
                <div class="flex flex-wrap md:flex-nowrap gap-6 p-6">

                    {{-- LEFT: Products Grid --}}
                    <div class="w-full md:w-2/3 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($products as $product)
                            <div class="border rounded-lg p-4 text-center shadow-sm">
                                <h2 class="font-semibold">{{ $product->name }}</h2>
                                <p class="text-gray-600">₱{{ number_format($product->price, 2) }}</p>
                                <p class="text-sm text-gray-500">Stock: {{ $product->stock }}</p>

                                <div class="flex justify-center items-center mt-2">
                                    <form method="POST"
                                        action="{{ route('cart.update', ['productId' => $product->id]) }}"
                                        class="flex items-center space-x-2">
                                        @csrf
                                        <button type="submit" name="action" value="decrease"
                                            class="bg-gray-300 px-2 rounded">−</button>

                                        <span class="w-6 text-center">
                                            {{ $cart['product'][$product->id]['quantity'] ?? 0 }}
                                        </span>

                                        <button type="submit" name="action" value="increase"
                                            class="bg-red-900 px-2 rounded text-white">+</button>
                                    </form>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- RIGHT: Order Summary --}}
                    <div class="w-full md:w-1/3 bg-white shadow-lg p-4 rounded-lg">
                        <form action="{{ route('cashier.walkin.checkout') }}" method="post">

                            @csrf
                            <h2 class="font-bold text-lg mb-2">Order #{{ $cart['id'] }}</h2>
                            <div class="mb-4">
                                <label class="block mb-1 text-sm font-medium text-gray-900">Customer Name</label>
                                <input type="text" name="customer_name" class="w-full border p-2 text-sm rounded-lg"
                                    placeholder="Enter customer name" value="{{ $cart['customer_name'] ?? ' ' }}">
                            </div>
                            <ul class="mb-4">
                                @foreach ($cart['product'] as $item)
                                    @if (!empty($item['product']))
                                        <li class="flex justify-between text-sm border-b py-1">
                                            <span>{{ $item['product']['name'] }} ×{{ $item['quantity'] }}</span>
                                            <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>

                            <div class="text-sm space-y-1 mb-4">
                                <div class="flex justify-between">
                                    <span>Subtotal</span><span>₱{{ number_format($cart['subtotal'], 2) }}</span>
                                </div>
                                <div class="flex justify-between"><span>Delivery
                                        Fee</span><span>₱{{ number_format($cart['delivery_fee'], 2) }}</span></div>
                                <hr>
                                <div class="flex justify-between font-semibold text-lg">
                                    <span>Total</span><span>₱{{ number_format($cart['total'], 2) }}</span>
                                </div>
                            </div>

                            {{-- Delivery Method --}}
                            <label class="block mb-1 text-sm">Delivery Method</label>
                            <select class="w-full border p-2 mb-4 text-sm rounded" name="delivery_method">
                                <option selected disabled>Pick a Delivery Method</option>
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Delivery</option>
                            </select>

                            {{-- Payment Methods --}}

                            <input type="hidden" name="payment_method" id="paymentMethodInput">
                            <div class="flex justify-between mb-4">
                                <button id="cashButton" class="w-full mx-1 py-2 bg-gray-200 rounded" type="button"
                                    onclick="selectPaymentMethod('cash')">
                                    Cash
                                </button>
                                <button id="bankButton" class="w-full mx-1 py-2 bg-gray-200 rounded" type="button"
                                    onclick="selectPaymentMethod('bank_transfer')">
                                    Bank Transfer
                                </button>
                                <button id="ewalletButton" class="w-full mx-1 py-2 bg-red-900 text-white rounded"
                                    type="button" onclick="selectPaymentMethod('e_wallet')">
                                    E-Wallet
                                </button>
                            </div>
                            <script>
                                function selectPaymentMethod(method) {
                                    // Reset all buttons to their default state
                                    document.getElementById('cashButton').classList.remove('bg-red-900', 'text-white');
                                    document.getElementById('bankButton').classList.remove('bg-red-900', 'text-white');
                                    document.getElementById('ewalletButton').classList.remove('bg-red-900', 'text-white');

                                    document.getElementById('cashButton').classList.add('bg-gray-200');
                                    document.getElementById('bankButton').classList.add('bg-gray-200');
                                    document.getElementById('ewalletButton').classList.add('bg-gray-200');
                                    // Highlight the selected button
                                    if (method === 'cash') {
                                        document.getElementById('cashButton').classList.add('bg-red-900', 'text-white');
                                    } else if (method === 'bank_transfer') {
                                        document.getElementById('bankButton').classList.add('bg-red-900', 'text-white');
                                    } else if (method === 'e_wallet') {
                                        document.getElementById('ewalletButton').classList.add('bg-red-900', 'text-white');
                                    }

                                    // Update the hidden input with the selected method
                                    document.getElementById('paymentMethodInput').value = method;

                                }
                            </script>

                            {{-- Payment Status --}}
                            <label class="block mb-1 text-sm">Payment Status</label>
                            <select class="w-full border p-2 mb-4 text-sm rounded" name="payment_status">
                                <option selected disabled>Select Payment Status</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>

                            <button class="w-full py-3 bg-red-900 text-white rounded-lg">Add Order</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#product-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });

            $("#filter-btn").click(function(event) {
                event.preventDefault();
                $("#filter-dropdown").toggleClass("hidden");
            });

            // Apply filter
            $(".filter-option").click(function() {
                selectedFilter = $(this).data("filter");
                $("#filter-dropdown").addClass("hidden"); // Hide dropdown after selection
                filterTable();
            });

            // Close dropdown when clicking outside
            $(document).click(function(event) {
                if (!$(event.target).closest("#filter-btn, #filter-dropdown").length) {
                    $("#filter-dropdown").addClass("hidden");
                }
            });
            $('[data-modal-target]').on('click', function() {
                const modalId = $(this).data('modal-target');
                $(`#${modalId}`).removeClass('hidden');
            });
            $('[data-modal-toggle]').on('click', function() {
                const modalId = $(this).data('modal-toggle');
                $(`#${modalId}`).addClass('hidden');
            });
        });
    </script>
</x-app-layout>
