<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Order Tracking</h2>
            </div>
            @include('customer.tracking.breadcrumbs')
            <div class="flex flex-col bg-white p-4 rounded-lg mt-4">
                @foreach ($cartItems as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-32 h-28 max-lg:w-24 max-lg:h-24 flex p-3 shrink-0 rounded-md">
                            <img src='{{ asset('assets/' . $item->product->image) }}' class="w-full object-contain" />
                        </div>
                        <div class="w-full">
                            <h3 class="text-sm lg:text-base text-gray-800 font-bold">{{ $item->product->name }}</h3>
                            <ul class="text-xs text-gray-800 space-y-1 mt-3">
                                <li class="flex flex-wrap gap-4">Order Number <span
                                        class="ml-auto font-bold">{{ $item->order->order_number }}</span>
                                </li>
                                <li class="flex flex-wrap gap-4">Quantity <span
                                        class="ml-auto font-bold">{{ $item->quantity }}</span></li>
                                <li class="flex flex-wrap gap-4">Total Price <span class="ml-auto font-bold">₱
                                        {{ number_format($item->product->price * $item->quantity, 2, '.', ',') }}</span>
                                </li>
                                <li class="flex flex-wrap gap-4">Location <span
                                        class="ml-auto font-bold">{{ $item->product->location->name }}</span></li>
                                <h3 class="text-sm lg:text-base text-gray-800 font-bold">Receipt</h3>
                                <img src="{{ asset('receipt_file/' . $item->order->payments->first()->receipt_file) }}"
                                    alt="Receipt" class="w-60">
                            </ul>
                            <form action="{{ route('customer.cancelled') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="order_id" value="{{ $item->order->id }}">
                                <button class="mt-2 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                    Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
