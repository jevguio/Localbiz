<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Orders</h2>
            </div>
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
                                    {{ $order->user->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->created_at->format('d M Y') }}

                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->total_amount }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->status }}
                                </td>
                                <td class="px-6 py-4">
                                    <button data-modal-target="editModal{{ $order->id }}"
                                        class="font-medium text-green-600 hover:underline" type="button">
                                        Edit
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
                                                Edit Order Details
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
                                                    <label for="status"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                    <select id="status" name="status"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                                        <option value="processing"
                                                            {{ $order->status == 'processing' ? 'selected' : '' }}>
                                                            Processing
                                                        </option>
                                                        <!-- <option value="receiving"
                                                            {{ $order->status == 'receiving' ? 'selected' : '' }}>
                                                            Receiving
                                                        </option>
                                                        <option value="delivered"
                                                            {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                                            Completed/Delivered
                                                        </option>
                                                        <option value="canceled"
                                                            {{ $order->status == 'canceled' ? 'selected' : '' }}>
                                                            Canceled
                                                        </option> -->
                                                    </select>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="customer_id"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Customer
                                                        Name</label>
                                                    <input type="text" name="customer_id" id="customer_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        value="{{ $order->user->name }}" readonly>
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
                                                    <label for="category"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                                    <select id="category" name="category_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                        disabled>
                                                        <option selected="">Select category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $order->orderItems->first()->product->category_id == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="courier"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Courier</label>
                                                    <input type="text" name="courier" id="courier"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        value="{{ $order->payments->first()->courier->name ?? 'N/A' }}"
                                                        readonly>
                                                </div>
                                                <div class="col-span-1">
                                                    <label for="payment_method"
                                                        class="block mb-2 text-sm font-medium text-gray-900">Mode of
                                                        Payment</label>
                                                    <input type="text" name="payment_method" id="payment_method"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                        value="{{ $order->payments->first() ? $order->payments->first()->payment_method : 'N/A' }}"
                                                        readonly>
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
                                                <div class="col-span-2">
                                                    <label for="feedback"
                                                        class="block mb-2 text-sm font-bold text-gray-900">Feedback</label>
                                                    <ul class="bg-gray-50 border border-gray-300 rounded-lg p-2">
                                                        <li class="mb-2">
                                                            <strong>{{ $order->user->name }}:</strong>
                                                            <span>{{ $order->orderItems->first()->feedback->comment ?? 'No feedback yet' }}</span>
                                                            <span class="text-gray-500"> (Rating:
                                                                {{ $order->orderItems->first()->feedback->rating ?? 'No rating yet' }})</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-span-2 flex justify-around gap-2">
                                                    <div class="col-span-1">
                                                        <label for="proof_of_delivery"
                                                            class="block mb-2 text-sm font-medium text-gray-900">Proof
                                                            of
                                                            Delivery</label>
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
                                                    <button type="button"
                                                        data-modal-toggle="editModal{{ $order->id }}"
                                                        class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Close
                                                    </button>
                                                    <button type="submit"
                                                        class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Save Changes
                                                    </button>
                                                </div>
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
                    <span
                        class="text-sm font-normal text-gray-500 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
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
