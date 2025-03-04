<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Shopping Cart</h2>
            </div>
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                <div class="mx-auto w-full flex-none">
                    <div class="space-y-6">
                        @foreach ($cartItems as $item)
                            <div class="rounded-lg border border-gray-300 bg-white p-6 shadow-md md:p-8">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                                    <div class="flex items-center space-x-4 md:order-1">
                                        <a href="#" class="shrink-0">
                                            <img class="h-24 w-24 rounded-lg border border-gray-300 object-cover"
                                                src="{{ asset('assets/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}" />
                                        </a>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-900">{{ $item->product->name }}
                                            </p>
                                            <p class="text-sm text-gray-700">{{ $item->product->description }}</p>
                                            <p class="text-sm text-gray-700">Seller:
                                                {{ $item->product->seller->user->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-700">Gcash Number:
                                                {{ $item->product->seller->user->gcash_number ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-700">Bank Name:
                                                {{ $item->product->seller->user->bank_name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-700">Account No.:
                                                {{ $item->product->seller->user->bank_account_number ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-row items-center md:order-3 space-x-4">
                                        <form action="{{ route('customer.updateCart', $item->product_id) }}"
                                            method="POST" class="inline-flex items-center space-x-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $item->id }}">

                                            <button type="submit" name="decrement" value="decrement"
                                                class="h-8 w-8 flex items-center justify-center rounded-md border border-gray-400 bg-gray-100 hover:bg-gray-200 transition">
                                                <i class='bx bx-minus text-lg'></i>
                                            </button>

                                            <span class="text-base font-medium text-gray-900 text-center w-6">
                                                {{ $item->quantity }}
                                            </span>

                                            <button type="submit" name="increment" value="increment"
                                                class="h-8 w-8 flex items-center justify-center rounded-md border border-gray-400 bg-gray-100 hover:bg-gray-200 transition">
                                                <i class='bx bx-plus text-lg'></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex flex-col md:order-2 md:w-32 space-y-2 md:space-y-0 md:space-x-4">
                                        <p class="text-lg font-bold text-gray-900">₱
                                            {{ $item->product->price * $item->quantity }}</p>
                                        <form action="{{ route('customer.removeCart', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <button type="submit"
                                                class="text-red-600 text-sm font-medium hover:underline flex items-center">
                                                <i class='bx bx-trash text-lg me-1.5'></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="flex flex-col md:order-4 md:w-32 space-y-2 md:space-y-0 md:space-x-1">
                                                <input type="checkbox" />
                                            </div>  
                                </div>
                            </div>
                        @endforeach
                        @if ($cartItems->isNotEmpty())
                            <div class="mx-auto mt- flex-1 space-y-6 lg:mt-0 lg:w-full">
                                <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                                    <p class="text-xl font-semibold text-gray-900">Order Summary</p>

                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <dl class="flex items-center justify-between gap-4">
                                                <dt class="text-base font-normal text-gray-500">Original Price</dt>
                                                <dd class="text-base font-medium text-gray-900">
                                                    ₱
                                                    {{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}
                                                </dd>
                                            </dl>
                                            <div>
                                                <dl
                                                    class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2">
                                                    <dt class="text-base font-bold text-gray-900">Total</dt>
                                                    <dd class="text-base font-bold text-gray-900">
                                                        ₱
                                                        {{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div x-data="{ paymentMethod: '' }">
                                    <button id="proceedToCheckout"
                                        class="flex w-full items-center justify-center rounded-lg btn btn-primary px-5 py-2.5 text-sm font-medium text-white">
                                        Proceed to Checkout
                                    </button>
                                    <h2 id="paymentDetails" class="mt-4 text-xl font-bold text-gray-900 sm:text-2xl">
                                        Payment Details
                                    </h2>
                                    <form action="{{ route('customer.checkout') }}" method="POST"
                                        class="mt-4 space-y-4" id="paymentForm" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <div class="fieldset">
                                                <legend class="fieldset-legend">Payment Method</legend>
                                                <select class="select select-bordered w-full" name="payment_method"
                                                    id="paymentMethod" required>
                                                    <option disabled selected>Pick a payment method</option>
                                                    <option value="Credit Card">Credit Card</option>
                                                    <option value="Gcash">Gcash</option>
                                                    <option value="COD">Cash on Delivery</option>
                                                    <option value="Pick Up">Pick Up</option>
                                                </select>
                                            </div>
                                        </div>
                                        @foreach ($seller as $seller)
                                            <div class="gcash-details" style="display: none;">
                                                <p class="text-base font-medium text-gray-900">Seller Name:
                                                    {{ $seller->user->name }}</p>
                                                <p class="text-base font-medium text-gray-900">Gcash Number:
                                                    {{ $seller->user->gcash_number }}</p>
                                            </div>
                                            <div class="credit-card-details" style="display: none;">
                                                <p class="text-base font-medium text-gray-900">Bank Name:
                                                    {{ $seller->user->bank_name }}</p>
                                                <p class="text-base font-medium text-gray-900">Bank Account Number:
                                                    {{ $seller->user->bank_account_number }}</p>
                                            </div>
                                        @endforeach
                                        <div class="courier-selection">
                                            <div class="fieldset">
                                                <legend class="fieldset-legend">Courier</legend>
                                                <select class="select select-bordered w-full" name="courier_id">
                                                    <option disabled selected>Pick a courier</option>
                                                    @foreach ($couriers as $courier)
                                                        <option value="{{ $courier->id }}">{{ $courier->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="fieldset">
                                            <legend class="fieldset-legend">Upload Receipt</legend>
                                            <input type="file" class="file-input file-input-bordered w-full"
                                                accept="image/*" name="receipt_file" required />
                                            <label class="fieldset-label">Max size 2MB</label>
                                        </div>
                                        <button type="submit"
                                            class="flex w-full items-center justify-center rounded-lg btn btn-secondary px-5 py-2.5 text-sm font-medium text-white">Submit</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-sm font-normal text-gray-500"></span>
                        <a onclick="window.location.href = '{{ route('customer.products') }}'"
                            title="Continue Shopping"
                            class="inline-flex items-center gap-2 font-medium text-primary-700 cursor-pointer">
                            Continue Shopping
                            <i class='bx bx-right-arrow-alt text-lg'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        $(document).ready(function() {
            let showPaymentDetails = false;

            $('#paymentDetails, #paymentForm').hide();

            $('#proceedToCheckout').click(function() {
                showPaymentDetails = !showPaymentDetails;
                $('#paymentDetails, #paymentForm').toggle(showPaymentDetails);
            });

            $('#paymentMethod').change(function() {
                var selectedMethod = $(this).val();
                if (selectedMethod === 'Pick Up') {
                    $('.courier-selection').hide();
                } else {
                    $('.courier-selection').show();
                }

                if (selectedMethod === 'Gcash') {
                    $('.gcash-details').show();
                    $('.credit-card-details').hide();
                } else if (selectedMethod === 'Credit Card') {
                    $('.credit-card-details').show();
                    $('.gcash-details').hide();
                } else {
                    $('.gcash-details, .credit-card-details').hide();
                }
            });
        });
    </script>
</x-app-layout>
