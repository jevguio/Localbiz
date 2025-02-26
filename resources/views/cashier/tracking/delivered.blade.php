<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Order Tracking</h2>
            </div>
            @include('cashier.tracking.breadcrumbs')
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
                            </ul>
                            @if (!$item->feedback && $item->order->status == 'delivered' && $item->order->user_id == Auth::id())
                                <form action="{{ route('customer.tracking.delivered.upload') }}" method="POST"
                                    enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $item->order->id }}">
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                    <select class="select mt-2 w-full" name="rating">
                                        <option disabled selected>Pick a rating</option>
                                        <option value="1">1 (Poor)</option>
                                        <option value="2">2 (Fair)</option>
                                        <option value="3">3 (Good)</option>
                                        <option value="4">4 (Very Good)</option>
                                        <option value="5">5 (Excellent)</option>
                                    </select>
                                    <label for="comment"
                                        class="block text-sm font-medium text-gray-700 my-2">Comment</label>
                                    <textarea class="textarea w-full" rows="3" placeholder="Comment" name="comment"></textarea>
                                    <button type="submit"
                                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white rounded-md my-2">Submit
                                        Feedback</button>
                                </form>
                            @else
                                <div role="alert" class="alert alert-success mt-4">
                                    <i class='bx bx-check-circle text-white text-2xl'></i>
                                    <span class="text-white">You have already submitted feedback for this
                                        product!</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
