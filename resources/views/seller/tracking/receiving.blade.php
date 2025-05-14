<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-3xl">Order Tracking</h2>
            </div>
            @include('seller.tracking.breadcrumbs')
            <div class="flex flex-col bg-white p-4 rounded-lg mt-4">
                @if ($cartItems->isEmpty())
                    <p style="text-align: center;">No orders found</p>
                @else
                    @foreach ($cartItems as $item)
                        <div class="flex items-start gap-4 border border-gray-200 hover:bg-gray-50 p-5 my-5"
                            style="cursor:pointer" data-modal-target="viewModal{{ $item->id }}">
                            <div class="w-32 h-28 max-lg:w-24 max-lg:h-24 flex p-3 shrink-0 rounded-md">
                                <img src='{{ asset('assets/' . $item->product->image) }}'
                                    class="w-full object-contain" />
                            </div>
                            <div class="w-full">
                                <h3 class="text-sm lg:text-base text-gray-800 font-bold">{{ $item->product->name }}</h3>
                                <ul class="text-xs text-gray-800 space-y-1 mt-3">
                                    <li class="flex flex-wrap gap-4">Order Date <span
                                            class="ml-auto font-bold">{{ $item->order->created_at->format('F d, Y') }}</span>
                                    </li>
                                    <li class="flex flex-wrap gap-4">Order Number <span
                                            class="ml-auto font-bold">{{ $item->order->order_number }}</span>
                                    </li>
                                    <li class="flex flex-wrap gap-4">Quantity <span
                                            class="ml-auto font-bold">{{ $item->quantity }}</span></li>
                                    <li class="flex flex-wrap gap-4">Total Price <span class="ml-auto font-bold">₱
                                            {{ number_format($item->product->price * $item->quantity, 2, '.', ',') }}</span>
                                    </li>

                                    <li class="flex flex-wrap gap-4">

                                        <!-- <button
                                    data-modal-target="viewModal{{ $item->id }}"
                                        class="font-medium text-green-600 hover:underline" type="button">
                                        Open
                                    </button> -->
                                    </li>
                                </ul>
                            </div>
                        </div>


                        <div id="viewModal{{ $item->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                            <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">

                                <form action="{{ route('seller.order.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <input type="text" hidden name="id" value="{{ $item->order->id }}" />
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Order Details
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="viewModal{{ $item->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 mb-4 p-4">
                                            <div class="col-span-1">
                                                <label for="customer_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                    Name</label>
                                                <input type="text" name="customer_id" id="customer_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type customer name"
                                                    value="{{ $item->order->user->fname . ' ' . $item->order->user->lname }}"
                                                    readonly disabled>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="customer_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                    Address</label>
                                                <input type="text" name="customer_id" id="customer_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type customer address"
                                                    value="{{ $item->order->user->address }}" readonly disabled>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="customer_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                    Contact Number</label>
                                                <input type="text" name="customer_id" id="customer_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type customer contact number"
                                                    value="{{ $item->order->user->phone }}" readonly disabled>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="product_name"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Product
                                                    Name</label>
                                                <input type="text" name="name" id="name"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type product name"
                                                    value="{{ $item->order->orderItems->first() ? $item->order->orderItems->first()->product->name : 'N/A' }}"
                                                    readonly disabled>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="category"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                                <div id="category" name="category_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                    readonly disabled>
                                                    @foreach ($categories as $category)
                                                        @if ($item->order->orderItems->first()->product->category_id == $category->id)
                                                            <div value="{{ $category->id }}">
                                                                {{ $category->name }} </div>
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
                                                    value="{{ $item->order->orderItems->first()->product->price ?? 'N/A' }}"
                                                    readonly disabled>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="quantity"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Quantity</label>
                                                <input type="number" name="quantity" id="quantity"
                                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Type quantity" name="quantity"
                                                    value="{{ $item->order->orderItems->first()->quantity }}" readonly
                                                    disabled>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="payment_method"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Total
                                                    Amount</label>
                                                <input type="text" name="payment_method" id="payment_method"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="₱{{ number_format($item->product->price * $item->quantity, 2, '.', ',') }}"
                                                    readonly>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="payment_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Order Date
                                                </label>
                                                <input type="text" name="Order_date" id="Ordert_date"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ \Carbon\Carbon::parse($item->order->created_at)->format('F d, Y') }}"
                                                    readonly disabled>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="payment_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Payment
                                                    Method</label>
                                                <input type="text" name="payment_method" id="payment_method"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ $item->order->payments->first() ? $item->order->payments->first()->payment_method : 'N/A' }}"
                                                    readonly disabled>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="payment_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Payment
                                                    Date</label>
                                                <input type="text" name="payment_date" id="payment_date"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ $item->order->payments->first() ? \Carbon\Carbon::parse($item->order->payments->first()->payment_date)->format('F d, Y') : 'N/A' }}"
                                                    readonly disabled>
                                            </div>


                                            <div class="col-span-1">
                                                <label for="payment_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Delivery
                                                    Method</label>
                                                <div name="status" id="status"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                                                    @if (optional($item->order->payments)->courier_id != null)
                                                        Cash on Delivery
                                                    @else
                                                        Pick Up
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="status"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                <div name="status" id="status"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    readonly>
                                                    <div>

                                                        {{ ($item->order->status == 'processing' ? 'Processing' : $item->order->status == 'receiving') ? 'For Delivery' : 'N/A' }}

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="feedback"
                                                    class="block mb-2 text-sm font-bold text-gray-900">Feedback</label>
                                                <ul class="bg-gray-50 border border-gray-300 rounded-lg p-2">
                                                    <li class="mb-2">
                                                        <strong>{{ $item->order->user->name }}:</strong>
                                                        <span>{{ $item->order->orderItems->first()->feedback->comment ?? 'No feedback yet' }}</span>
                                                        <span class="text-gray-500"> (Rating:
                                                            {{ $item->order->orderItems->first()->feedback->rating ?? 'No rating yet' }})</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- <div class="col-span-2 flex justify-around gap-2">
                                            <div class="col-span-1">
                                                <label for="proof_of_delivery"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Proof of
                                                    Delivery</label>
                                                <img src="{{ asset('delivery_receipt/' . $item->order->proof_of_delivery) }}"
                                                    alt="Proof of Delivery" class="w-60 object-cover">
                                            </div> -->
                                            <div class="col-span-1">
                                                <label for="receipt_file"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Receipt
                                                    File</label>
                                                <img src="{{ asset('receipt_file/' . $item->order->payments->first()->receipt_file) }}"
                                                    alt="Receipt File" class="w-60 object-cover">
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <div class="flex justify-end gap-2">

                                            <button type="button" data-modal-toggle="viewModal{{ $item->id }}"
                                                class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                            </div>

                            </form>
                        </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    </div>
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
