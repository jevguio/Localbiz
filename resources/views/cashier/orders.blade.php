<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">

            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Orders</h2>
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
            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">

                <form class="max-w-md ml-0 mb-4">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class='bx bx-search text-gray-500 text-2xl'></i>
                        </div>
                        <input type="search" id="table-search"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search for order....">
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
                                Total Amount
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody id="product-table-body">
                        @foreach ($orders as $order)
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $order->order_number }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->user->fname . ' ' . $order->user->lname }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->created_at->format('d M Y') }}

                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($order->orderItems->sum('price'), 2) }}
                                </td>


                                <td class="px-6 py-4">
                                    {{ $order->status === 'processing' ? 'Payment Verified' : $order->status }}

                                </td>
                                <td class="px-6 py-4">
                                    <button data-modal-target="editModal{{ $order->id }}"
                                        class="font-medium text-green-600 hover:underline" type="button">
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
                                        <form action="{{ route('cashier.orders.update', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-2 gap-4 mb-4 p-4">

                                                <div class="col-span-1">
                                                    <label for="customer_id"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                        Name</label>
                                                    <input type="text" name="customer_id" id="customer_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        value="{{ $order->user->fname . ' ' . $order->user->lname }}"
                                                        readonly>
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
                                                        value="{{ $order->orderItems->first()->product->name ?? 'N/A' }}"
                                                        readonly>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="product_description"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Product
                                                        Description</label>
                                                    <input type="text" name="product_description"
                                                        id="product_description"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        placeholder="Type product description"
                                                        value="{{ $order->orderItems->first()->product->description ?? 'N/A' }}"
                                                        readonly>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="category"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Category
                                                        Name</label>
                                                    <div id="category" name="category_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                        disabled>
                                                        @foreach ($categories as $category)
                                                            @if ($order->orderItems->first()->product->category_id == $category->id)
                                                                <div value="{{ $category->id }}">
                                                                    {{ $category->name }}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="col-span-1">
                                                    <label for="price"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                                                    <input type="number" name="price" id="price"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        placeholder="$2999"
                                                        value="{{ $order->orderItems->first()->price / $order->orderItems->first()->quantity ?? 'N/A' }}"
                                                        readonly>
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
                                                    <label for="payment_method"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Total
                                                        Amount</label>
                                                    <input type="text" name="payment_method" id="payment_method"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        value="{{ $order->orderItems->first()->price }}" readonly>
                                                </div>

                                                <div class="col-span-1">
                                                    <label for="payment_method"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Mode
                                                        of Payment</label>
                                                    <input type="text" name="payment_method" id="payment_method"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        value="{{ $order->payments->first() ? $order->payments->first()->payment_method : 'N/A' }}"
                                                        readonly>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="payment_status"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Payment
                                                        Status</label>

                                            <input type="text" name="payment_status" id="payment_status"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                            value="{{ $order->payments->status === 'partial' ? 'Partial' : ($order->payments->status === 'fully_paid' ? 'Fully Paid' : 'N/A') }}"

                                            readonly disabled>
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
                                                <div id="paymentAmountContainer_{{ $order->id }}"
                                                    class="col-span-1">
                                                    <label for="payment_amount_{{ $order->id }}"
                                                        class="block mb-2 text-sm font-medium text-gray-900">
                                                        Amount Paid</label>
                                                    <input type="number" name="payment_amount"
                                                        id="payment_amount_{{ $order->id }}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-50 p-2.5"
                                                        placeholder="Enter amount" min="0"
                                                        step="0.01"
                                                        disabled
                                                        value="{{  $order->payments->payment_amount}}">
                                                </div>

                                                @if (!is_null($order->payments->pickup_date))
                                                    <div class="col-span-1">
                                                        <label for="pickup_date"
                                                            class="block mb-2 text-sm font-medium text-gray-900">Pickup
                                                            Date</label>
                                                        <input type="text" name="pickup_date" id="pickup_date"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            value="{{ $order->payments->pickup_date ? \Carbon\Carbon::parse($order->payments->pickup_date)->format('F d, Y') : 'N/A' }}"
                                                            readonly>
                                                    </div>
                                                @endif
                                                <div class="col-span-1">
                                                    <label for="status"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                    <div id="status" name="status" disabled
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                                        <div>

                                                            {{ $order->status === 'processing' ? 'Payment Verified' : $order->status }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="receipt_file"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Receipt
                                                        File</label>
                                                    @if ($order->payments->first() && $order->payments->first()->receipt_file)
                                                        <img src="{{ asset('receipt_file/' . $order->payments->first()->receipt_file) }}"
                                                            alt="Receipt File"
                                                            class="w-60 object-cover cursor-pointer"
                                                            onclick="openModal(this.src)">
                                                    @else
                                                        <p>No receipt file available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                            {{-- <hr class="my-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="button"
                                                        data-modal-toggle="editModal{{ $order->id }}"
                                                        class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Close
                                                    </button>
                                                    <button type="submit"
                                                        class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Save Changes
                                                    </button>
                                                </div> --}}
                                    </div>
                                    </form>
                                </div>
                            </div>
            </div>
            @endforeach

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
    <style>
        .myModalthumbnail {
            width: 150px;
            height: 100px;
            cursor: pointer;
            object-fit: cover;
        }

        .myModalmyModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
        }

        .myModal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1);
            max-width: 90%;
            max-height: 90%;
            transition: transform 0.3s;
        }

        .myModalclose {
            position: absolute;
            top: 15px;
            right: 25px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
    <div id="myModalmyModal" class="myModalmyModal">
        <span class="myModalclose" onclick="closeModal()">&times;</span>
        <img id="modalImg" class="myModal-content" onwheel="zoom(event)">
    </div>


    <script>
        let modalImg = document.getElementById("modalImg");
        let scale = 1; // Initial zoom scale
        let posX = 0,
            posY = 0; // Initial position
        let isDragging = false;
        let startX, startY;

        function openModal(src) {
            let modal = document.getElementById("myModalmyModal");
            modalImg = document.getElementById("modalImg");
            modalImg.src = src;
            modalImg.style.zIndex = "99";
            modalImg.style.display = "block";

            modal.style.zIndex = "99";
            modal.style.display = "block";
            console.log(modal);

            modalImg.addEventListener("wheel", zoom);
            modalImg.addEventListener("mousedown", startDrag);
            window.addEventListener("mousemove", drag);
            window.addEventListener("mouseup", stopDrag);
            modalImg.addEventListener("mouseup", stopDrag);
        }

        function closeModal() {
            document.getElementById("myModalmyModal").style.display = "none";
        }

        function zoom(event) {
            event.preventDefault();

            let zoomFactor = 0.1;
            let newScale = scale + (event.deltaY > 0 ? -zoomFactor : zoomFactor);

            // Clamp zoom scale between 1 and 3
            scale = Math.min(Math.max(1, newScale), 3);

            // Apply transform
            updateTransform();
        }

        // Start dragging
        function startDrag(event) {
            if (scale === 1) return; // Disable dragging when not zoomed
            isDragging = true;
            startX = event.clientX - posX;
            startY = event.clientY - posY;
            modalImg.style.cursor = "grabbing";
        }

        // Drag image
        function drag(event) {
            if (!isDragging) return;
            posX = event.clientX - startX;
            posY = event.clientY - startY;
            updateTransform();
        }

        // Stop dragging
        function stopDrag() {
            isDragging = false;
            modalImg.style.cursor = "grab";
        }

        // Apply zoom and panning transformations
        function updateTransform() {
            modalImg.style.transformOrigin = "center center";
            modalImg.style.transform = `translate(-50%, -50%) translate(${posX}px, ${posY}px) scale(${scale})`;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#seller-table-body tr').filter(function() {
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
        });
    </script>
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
            $('[data-modal-toggle]').on('click', function() {
                const modalId = $(this).data('modal-toggle');
                $(`#${modalId}`).addClass('hidden');
            });
        });
    </script>
</x-app-layout>
