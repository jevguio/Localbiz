<x-app-layout>
    <div class="p-2 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Shopping Cart</h2>
            </div>
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-4">
                <div class="mx-auto w-full flex-none">
                    <div class="space-y-2">
                        <div class="border border-gray-300 bg-white p-4 shadow-md md:p-8" style="overflow-x: hidden;">
                            <table class="w-full border-collapse  text-center " style="min-width: 800px;">
                                <thead>
                                    <tr class="bg-gray-100 border border-gray-300">
                                        <th class="p-2  text-left" style="max-width: 5%; ">
                                            <form action="{{ route('customer.updateSelectAllCart') }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_checked" value="0" />
                                                <input type="checkbox" name="is_checked" style="transform: scale(1.5);"
                                                    value="1" onchange="setTimeout(() => this.form.submit(), 300)"
                                                    {{ $allActive ? 'checked' : '' }} />
                                                <div class="ml-4 inline">All</div>
                                            </form>
                                        </th>
                                        <th class="p-2 text-left ">Product</th>
                                        <th class="p-2  ">Unit Price</th>
                                        <th class="p-2  ">Quantity</th>
                                        <th class="p-2  ">Total Price</th>
                                        <th class="p-2 text-left">Action</th>
                                    </tr>
                                    <tr class="h-5">
                                    </tr>
                                </thead>
                                <tbody class=" ">
                                    @foreach ($cartItems as $item)
                                        @if ($item->product != null)
                                            <tr class="my-4 border border-gray-300">
                                                <td class="p-4 text-left " style="max-width: 5%; ">
                                                    <form action="{{ route('customer.updateSelectionCart') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id"
                                                            value="{{ $item->id }}" />
                                                        <input type="hidden" name="is_checked" value="0" />
                                                        <input type="checkbox" name="is_checked"
                                                            style="transform: scale(1.5);" value="1"
                                                            onchange="setTimeout(() => this.form.submit(), 300)"
                                                            {{ $item->is_checked ? 'checked' : '' }} />
                                                    </form>
                                                </td>
                                                <td class="p-2 flex items-center space-x-2 text-left">
                                                    <div class="w-full">
                                                        <img class="h-25 w-25 rounded-lg border border-gray-300 object-cover"
                                                            src="{{ asset('assets/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}" />
                                                        <div style="">
                                                            <p class="text-lg font-semibold">{{ $item->product->name }}
                                                            </p>
                                                            <p class="text-sm text-gray-700 "
                                                                style="
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;
                                                             white-space: nowrap; 
                                                                    ">
                                                                {{ substr($item->product->description, 0, 20) }}

                                                                @if (strlen($item->product->description) > 20)
                                                                    ...
                                                                @endif
                                                            </p>
                                                            <p class="text-sm text-gray-700">Seller:
                                                                {{ $item->product->seller->user->fname ?? 'N/A' }}
                                                            </p>
                                                            <p class="text-sm text-gray-700">Gcash Number:
                                                                {{ $item->product->seller->user->gcash_number ?? 'N/A' }}
                                                            </p>
                                                            <p class="text-sm text-gray-700">Bank Name:
                                                                {{ $item->product->seller->user->bank_name ?? 'N/A' }}
                                                            </p>
                                                            <p class="text-sm text-gray-700">Account No.:
                                                                {{ $item->product->seller->user->bank_account_number ?? 'N/A' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-2  ">₱{{ $item->product->price }}</td>
                                                <td class="p-2 ">
                                                    <form action="{{ route('customer.updateCart') }}" method="POST"
                                                        class="flex items-center space-x-2 w-full">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class=" mx-auto ">
                                                            <input type="hidden" name="id"
                                                                value="{{ $item->id }}">
                                                            <button type="submit" name="decrement" value="decrement"
                                                                class="h-8 w-8 inline-flex items-center justify-center border border-gray-400 bg-gray-100 hover:bg-gray-200">
                                                                <i class='bx bx-minus text-lg'></i>
                                                            </button>
                                                            <span
                                                                class="text-base inline font-medium text-gray-900 text-center mx-2 ">{{ $item->quantity }}</span>
                                                            <button type="submit" name="increment" value="increment"
                                                                class="h-8 w-8 inline-flex items-center justify-center border border-gray-400 bg-gray-100 hover:bg-gray-200">
                                                                <i class='bx bx-plus text-lg'></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="p-2   font-bold">
                                                    ₱{{ $item->product->price * $item->quantity }}
                                                </td>
                                                <td class="p-2 ">
                                                    <form
                                                        action="{{ route('customer.removeCart', ['id' => $item->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 font-medium hover:underline flex items-center">
                                                            <i class='bx bx-trash text-lg me-1.5'></i> Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr class="h-5">
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($cartItems->isNotEmpty() && $item->product != null)
                            <div class="mx-auto mt- flex-1 space-y-6 lg:mt-0 lg:w-full">
                                <div class="space-y-4   bg-white p-4 shadow-sm sm:p-6">
                                    <p class="text-xl font-semibold text-gray-900">Order Summary</p>

                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <dl class="flex items-center justify-between gap-4">
                                                <dt class="text-base font-normal text-gray-500">Original Price</dt>
                                                <dd class="text-base font-medium text-gray-900">
                                                    ₱
                                                    {{ $cartItems->sum(fn($item) => $item->product ? $item->product->price * $item->is_checked : 0) }}

                                                </dd>
                                            </dl>
                                            <div>
                                                <dl
                                                    class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2">
                                                    <dt class="text-base font-bold text-gray-900">Total</dt>
                                                    <dd class="text-base font-bold text-gray-900">
                                                        ₱
                                                        {{ $cartItems->sum(fn($item) =>  $item->product ? $item->product->price * $item->quantity * $item->is_checked :0 ) }}
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-data="{ paymentMethod: '' }">
                                    <button id="proceedToCheckout" @if (!$is_active_checkout) disabled @endif
                                        class="flex w-full items-center justify-center rounded-lg btn bg-orange-900 hover:bg-orange-900 px-5 py-2.5 text-sm font-medium text-white">
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
                                                    <option value="Bank Transfer">Bank Transfer</option>
                                                    <option value="Gcash">Gcash</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fieldset">
                                                <legend class="fieldset-legend">Delivery Method</legend>
                                                <select class="select select-bordered w-full" name="delivery_method"
                                                    id="deliveryMethod"  required>
                                                    <option disabled selected>Pick a Delivery method</option>
                                                    <option value="COD">Cash on Delivery</option>
                                                    <option value="Pick Up">Pick Up</option>
                                                </select>
                                            </div>
                                        </div> 
                                        @foreach ($seller as $seller)
                                            <div class="gcash-details" style="display: none;">
                                                <p class="text-base font-medium text-gray-900">Seller Name:
                                                    {{ $seller->user->fname }}
                                                </p>
                                                <p class="text-base font-medium text-gray-900">Gcash Number:
                                                    {{ $seller->user->gcash_number }}
                                                </p>
                                            </div>
                                            <div class="credit-card-details" style="display: none;">
                                                <p class="text-base font-medium text-gray-900">Bank Name:
                                                    {{ $seller->user->bank_name }}
                                                </p>
                                                <p class="text-base font-medium text-gray-900">Bank Account Number:
                                                    {{ $seller->user->bank_account_number }}
                                                </p>
                                            </div>
                                        @endforeach 
                                        <div class="courier-selection hidden w-full">
                                            <div class="fieldset"  id="pickup_date_id">
                                                <legend class="fieldset-legend">Pickup Date:</legend>
                                                <input type="date" class="input w-full" name="pickup_date"
                                                    min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" />
                                            </div>
                                        </div>
                                        <div class="fieldset">
                                            <input type="text" hidden class="input w-full" name="courier_id"
                                                value="1" />
                                            <legend class="fieldset-legend">Upload Receipt</legend>
                                            <input type="file" class="file-input file-input-bordered w-full"
                                                accept="image/*" name="receipt_file" required />
                                            <label class="fieldset-label">Max size 2MB</label>
                                        </div>

                                        <div class="fieldset">
                                            <legend class="fieldset-legend">Message:</legend>
                                            <textarea rows="8" class="p-2" name="message"></textarea>
                                        </div>

                                        <button type="submit"
                                            class="flex w-full items-center justify-center rounded-lg btn bg-orange-900 hover:bg-orange-900 px-5 py-2.5 text-sm font-medium text-white">Place
                                            Order</button>
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

            $('#deliveryMethod').change(function() {
                var selectedMethod = $(this).val();
                if (selectedMethod == "Pick Up") {
                    $('.courier-selection').show();
                } else {
                    $('.courier-selection').hide();
                }

                if (selectedMethod === 'Gcash') {
                    $('.gcash-details').show();
                    $('.credit-card-details').hide();
                } else if (selectedMethod === 'Bank Transfer') {
                    $('.credit-card-details').show();
                    $('.gcash-details').hide();
                } else {
                    $('.gcash-details, .credit-card-details').hide();
                }
            });
        });
    </script>
</x-app-layout>
