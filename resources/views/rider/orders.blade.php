<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 items-center ml-3 gap-4">
                <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">Order Management</h2>
            </div>

            <!-- Navigation Menu -->
            <ul
                class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center mx-auto font-[sans-serif] mt-4">


                @php
                    $status = request('status');

                @endphp


                <li
                    class="{{ Route::currentRouteName() == 'rider.orders' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 hover:text-orange-900' }} px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a href="{{ route('rider.orders') }}">For Delivery</a>
                </li>

                <li
                    class=" {{ Route::currentRouteName() == 'rider.tracking.walkin' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }} ' border-orange-900 text-orange-900' : text-gray-400 hover:text-orange-900  px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
                    <a href="{{ route('rider.tracking.walkin') }}">Walk-in Delivery</a>
                </li>

            </ul>

            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">
                <form class="max-w-md ml-0 mb-4">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <!-- <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class='bx bx-search text-gray-500 text-2xl'></i>
                        </div> -->
                    <!-- <input type="search" id="table-search"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search for products...."> -->

                    <!-- <button type="button" id="filter-btn"
                            class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-600 hover:text-gray-900">
                            <i class='bx bx-filter text-2xl'></i>
                        </button> -->

                    <!-- {{-- <div id="filter-dropdown"
                            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                            <ul class="py-2 text-sm text-gray-700">
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="All">All</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="pending">Pending</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="processing">Processing</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="receiving">Receiving</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="delivered">Delivered</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="cancelled">Cancelled</li>
                            </ul>
                        </div> --}} -->
            </div>
            </form>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Order Number
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Customer Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Order Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="order-table-body">
                    @forelse ($orders as $order)

                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 m-5 "
                            data-category="{{ $order->status }}">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $order->order_number }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->user->fname . ' ' . $order->user->lname }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->created_at->format('F d, Y h:i:s A') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->status == 'receiving' ? 'For Delivery' : $order->status }}
                            </td>
                            <td class="px-6 py-4">
                                <button data-modal-target="editModal{{ $order->id }}"
                                    class="font-medium text-blue-600 hover:underline" type="button">
                                    View
                                </button>

                            </td>
                        </tr>

                        <div id="editModal{{ $order->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                            <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                <div class="relative bg-white rounded-lg shadow-sm">
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Order Details
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                            data-modal-toggle="editModal{{ $order->id }}">
                                            <i class='bx bx-x text-gray-500 text-2xl'></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('rider.orders.update', $order->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-2 gap-4 mb-4 p-4">

                                            <div class="col-span-1">
                                                <label for="customer_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                    Name</label>
                                                <input type="text" name="customer_id" id="customer_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type customer name"
                                                    value="{{ $order->user->fname . ' ' . $order->user->lname }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="customer_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                    Address</label>
                                                <input type="text" name="customer_id" id="customer_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type customer address"
                                                    value="{{ $order->user->address }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="customer_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                    Contact Number</label>
                                                <input type="text" name="customer_id" id="customer_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type customer contact number"
                                                    value="{{ $order->user->phone }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="product_name"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Product
                                                    Name</label>
                                                <input type="text" name="name" id="name"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type product name"
                                                    value="{{ $order->orderItems->first()->product->name }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="quantity"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Quantity</label>
                                                <input type="number" name="quantity" id="quantity"
                                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Type quantity" name="quantity"
                                                    value="{{ $order->orderItems->first()->quantity }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="price"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                                                <input type="number" name="price" id="price"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="$2999"
                                                    value="{{ $order->orderItems->first()->product->price ?? 'N/A' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="price"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Total
                                                    Amount</label>
                                                <input type="number" name="price" id="price"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="$2999"
                                                    value="{{ $order->orderItems->first()->quantity * $order->orderItems->first()->product->price ?? 'N/A' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="category"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                                <div id="category" name="category_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                    disabled>
                                                    @foreach ($categories as $category)
                                                        @if ($order->orderItems->first()->product->category_id == $category->id)
                                                            <div>
                                                                {{ $category->name }}</div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>


                                            <div class="col-span-1">
                                                <label for="payment_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Payment
                                                    Date</label>
                                                <input type="text" name="payment_date" id="payment_date"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ $order->payments->first() ? \Carbon\Carbon::parse($order->payments->first()->payment_date)->format('F d, Y') : 'N/A' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="payment_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Order
                                                    Date</label>
                                                <input type="text" name="payment_date" id="payment_date"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="status{{ $order->id }}"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                <select name="status" id="status{{ $order->id }}"
                                                    onchange="onChangeStatus(event)"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                                    {{-- <option value="pending"
                                                            {{ $order->orderItems->first()->product->status == 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>
                                                        <option value="processing"
                                                            {{ $order->status == 'processing' ? 'selected' : '' }}>
                                                            Processing
                                                        </option> --}}
                                                    <option value="receiving"
                                                        {{ $order->status == 'receiving' ? 'selected' : '' }} disabled>
                                                        For Delivery
                                                    </option>
                                                    <option value="delivered"
                                                        {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                                        Delivered
                                                    </option>
                                                    <option value="cancelled"
                                                        {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled
                                                    </option>
                                                </select>
                                            </div>
                                            <script>
                                                document.getElementById('status{{ $order->id }}').addEventListener('change', function() {
                                                    const proofOfDelivery = document.getElementById('proof_of_delivery{{ $order->id }}');

                                                    if (this.value === "cancelled") {
                                                        proofOfDelivery.classList.add('hidden');
                                                        proofOfDelivery.removeAttribute('required');
                                                    } else {
                                                        proofOfDelivery.classList.remove('hidden');
                                                        proofOfDelivery.setAttribute('required', 'true');
                                                    }

                                                });
                                            </script>
                                            <div class="col-span-1">
                                                <label for="proof_of_delivery{{ $order->id }}"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Proof of
                                                    Delivery</label>
                                                <input type="file" name="proof_of_delivery{{ $order->id }}"
                                                    id="proof_of_delivery{{ $order->id }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5"
                                                    value="{{ $order->proof_of_delivery }}" required>
                                            </div>

                                            <div class="col-span-2 flex justify-around gap-2">
                                                <div class="col-span-1">
                                                    <label for="proof_of_delivery{{ $order->id }}"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Proof
                                                        of Delivery</label>
                                                    <img src="{{ asset('delivery_receipt/' . $order->proof_of_delivery) }}"
                                                        alt="Proof of Delivery" class="w-60 object-cover">
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="receipt_file"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Receipt
                                                        File</label>
                                                    @if ($order->payments->first() && $order->payments->first()->receipt_file)
                                                        <img src="{{ asset('receipt_file/' . $order->payments->first()->receipt_file) }}"
                                                            alt="Receipt File" class="w-60 object-cover">
                                                    @else
                                                        <p>No receipt file available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                            <div class="flex justify-end gap-2">
                                                <button type="submit"
                                                    class="btn btn-prumary bg-blue-700 hover:bg-blue-800 text-white inline-flex items-center focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Update
                                                </button>
                                                <button type="button"
                                                    data-modal-toggle="editModal{{ $order->id }}"
                                                    class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty

                        <tr>

                            <td colspan="8" class="text-center text-gray-500 py-4">No orders found</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4"
                aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
                    <span
                        class="font-semibold text-gray-900">{{ $orders->firstItem() }}-{{ $orders->lastItem() }}</span>
                    of <span class="font-semibold text-gray-900">{{ $orders->total() }}</span></span>
                <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                    {{ $orders->links() }}
                </ul>
            </nav>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // $("#filter-btn").click(function (event) {
            //     event.preventDefault();
            //     $("#filter-dropdown").toggleClass("hidden");
            // });

            // Search and filter function
            function filterTable() {
                const searchInput = $("#table-search").val().toLowerCase();

                $("#order-table-body tr").each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const rowCategory = $(this).data("category");

                    const matchesSearch = rowText.indexOf(searchInput) > -1;
                    const matchesFilter = selectedFilter === "All" || rowCategory === selectedFilter;

                    $(this).toggle(matchesSearch && matchesFilter);
                });
            }

            // Apply filter
            // $(".filter-option").click(function () {
            //     selectedFilter = $(this).data("filter");
            //     $("#filter-dropdown").addClass("hidden"); // Hide dropdown after selection
            //     filterTable();
            // });

            // Close dropdown when clicking outside
            $(document).click(function(event) {
                if (!$(event.target).closest("#filter-btn, #filter-dropdown").length) {
                    $("#filter-dropdown").addClass("hidden");
                }
            });
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#order-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });

            $('[data-modal-target]').on('click', function() {
                const modalId = $(this).data('modal-target');
                $(`#${modalId}`).removeClass('hidden');
            });

            $('[data-modal-toggle]').on('click', function() {
                const modalId = $(this).data('modal-toggle');
                $(`#${modalId}`).addClass('hidden');
            });

            function onChangeStatus(event) {
                // const proofOfDeliveryContainer = document.getElementById('proof-of-delivery-container');
                const proofOfDelivery = document.getElementById('proof_of_delivery');

                if (this.value === "cancelled") {
                    proofOfDelivery.classList.add('hidden');
                    proofOfDelivery.removeAttribute('required');
                } else {
                    proofOfDelivery.classList.remove('hidden');
                    proofOfDelivery.setAttribute('required', 'true');
                }

                // proofOfDeliveryContainer.style.display = (this.value == 'delivered') ? 'block' : 'none';
            };

        });
    </script>
</x-app-layout>
