<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-3xl">Order Tracking</h2>
            </div>
            @include('customer.tracking.breadcrumbs')
            <div class="flex flex-col bg-white p-4 rounded-lg mt-4">
                @if ($cartItems->isEmpty())
                    <p style="text-align: center;">No orders found</p>
                @else
                    @foreach ($cartItems as $item)
                        <div class="border border-gray-200 hover:bg-gray-50 shadow-md md:p-8 flex items-start gap-4 p-5 my-5"
                            data-modal-target="viewModal{{ $item->id }}">
                            <div class="w-32 h-28 max-lg:w-24 max-lg:h-24 flex p-3 shrink-0 rounded-md">
                                <img src='{{ asset('assets/' . $item->product->image) }}'
                                    class="w-full object-contain" />
                            </div>
                            <div class="w-full relative">
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
                                </ul>
                                @if (!$item->feedback && $item->order->status == 'delivered' && $item->order->user_id == Auth::id())
                                    <form action="{{ route('customer.tracking.delivered.upload') }}" method="POST"
                                        enctype="multipart/form-data" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $item->order->id }}">
                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                        <hr class="p-1 " />
                                        <label for="rating"
                                            class="block text-sm font-medium text-gray-700">Rating</label>
                                        <style>
                                            .star-rating {
                                                display: flex;
                                                flex-direction: row-reverse;
                                                font-size: 2rem;
                                                justify-content: flex-end;
                                            }

                                            .star-rating input {
                                                display: none;
                                            }

                                            .star-rating label {
                                                cursor: pointer;
                                                color: #ccc;
                                                transition: color 0.2s;
                                            }

                                            .star-rating input:checked~label,
                                            .star-rating label:hover,
                                            .star-rating label:hover~label {
                                                color: #f5c518;
                                            }
                                        </style>

                                        <div class="star-rating mt-2">
                                            <input type="radio" id="star5" name="rating" value="5" />
                                            <label for="star5" title="5 stars">★</label>

                                            <input type="radio" id="star4" name="rating" value="4" />
                                            <label for="star4" title="4 stars">★</label>

                                            <input type="radio" id="star3" name="rating" value="3" />
                                            <label for="star3" title="3 stars">★</label>

                                            <input type="radio" id="star2" name="rating" value="2" />
                                            <label for="star2" title="2 stars">★</label>

                                            <input type="radio" id="star1" name="rating" value="1" required/>
                                            <label for="star1" title="1 star">★</label>
                                        </div>

                                        <label for="comment"
                                            class="block text-sm font-medium text-gray-700 my-2">Comment</label>
                                        <textarea class="textarea w-full" rows="3" placeholder="Comment" name="comment"></textarea>
                                        <button type="submit"
                                            class="btn bg-orange-900 hover:bg-orange-800 text-white rounded-md my-2">Submit
                                            Feedback</button>
                                    </form>
                                @else
                                    <div role="alert" class="alert alert-success mt-4 mb-15 ">
                                        <i class='bx bx-check-circle text-white text-2xl'></i>
                                        <span class="text-white">You have already submitted feedback for this
                                            product!</span>
                                    </div>
                                @endif

                                <div class="col-span-3 mt-2  absolute right-0 bottom-0">
                                    <form action="{{ route('customer.addToCart') . '?targ=customer.cart' }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                        <button
                                            class="btn px-4 py-2 bg-red-900 text-white
                                            rounded-md hover:bg-red-800">
                                            Buy Again
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div id="viewModal{{ $item->id }}" tabindex="-1" aria-hidden="true"
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
                                            data-modal-toggle="viewModal{{ $item->id }}">
                                            <i class='bx bx-x text-gray-500 text-2xl'></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mb-4 p-4">
                                        <div class="col-span-1">
                                            <label for="customer_id"
                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                Name</label>
                                            <input type="text" name="customer_id" id="customer_id"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type customer first name"
                                                value="{{ $item->order->user->fname . ' ' . $item->order->user->lname }}"
                                                readonly>
                                        </div>

                                        <!-- <div class="col-span-1">
                                        <label for="customer_id"
                                            class="block mb-2 text-sm font-medium text-gray-900">
                                            Last Name</label>
                                        <input type="text" name="customer_id" id="customer_id"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                            placeholder="Type customer last name" value="{{ $item->order->user->lname }}"
                                            readonly>
                                    </div> -->

                                        <div class="col-span-1">
                                            <label for="customer_id"
                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                Address</label>
                                            <input type="text" name="customer_id" id="customer_id"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type customer address"
                                                value="{{ $item->order->user->address }}" readonly>
                                        </div>
                                        <div class="col-span-1">
                                            <label for="customer_id"
                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                Contact Number</label>
                                            <input type="text" name="customer_id" id="customer_id"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type customer contact number"
                                                value="{{ $item->order->user->phone }}" readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="product_name"
                                                class="block mb-2 text-sm font-medium text-gray-900">Product
                                                Name</label>
                                            <input type="text" name="name" id="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type product name"
                                                value="{{ $item->order->orderItems->first() ? $item->order->orderItems->first()->product->name : 'N/A' }}"
                                                readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="category"
                                                class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                            <div id="category" name="category_id"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                disabled>
                                                @foreach ($categories as $category)
                                                    @if ($item->order->orderItems->first()->product->category_id == $category->id)
                                                        <div value="{{ $category->id }}">
                                                            {{ $category->name }}</div>
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
                                                readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="quantity"
                                                class="block mb-2 text-sm font-medium text-gray-900">Quantity</label>
                                            <input type="number" name="quantity" id="quantity"
                                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Type quantity" name="quantity"
                                                value="{{ $item->quantity }}" readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="payment_amount"
                                                class="block mb-2 text-sm font-medium text-gray-900">Total
                                                Amount</label>
                                            <input type="text" name="payment_amount" id="payment_amount"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Payment Amount"
                                                value="₱{{ number_format($item->order->orderItems->first()->product->price * $item->quantity, 2, '.', ',') }}"
                                                readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="payment_date"
                                                class="block mb-2 text-sm font-medium text-gray-900">Order
                                                Date</label>
                                            <input type="text" name="order_date" id="payment_date"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                value="{{ $item->first() ? \Carbon\Carbon::parse($item->first()->created_at)->format('F d, Y') : 'N/A' }}"
                                                readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="payment_method"
                                                class="block mb-2 text-sm font-medium text-gray-900">Payment
                                                Method</label>
                                            <input type="text" name="payment_method" id="payment_method"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Payment Method"
                                                value="{{ $item->order->payments->first() ? $item->order->payments->first()->payment_method : 'N/A' }}"
                                                readonly>
                                        </div>


                                        <div class="col-span-1">
                                            <label for="payment_date"
                                                class="block mb-2 text-sm font-medium text-gray-900">Delivery
                                                Method</label>
                                            <div name="status" id="status"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                                                @if (optional($item->order->payments)->delivery_method == 'cod')
                                                    Cash on Delivery
                                                @else
                                                    Pick Up
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="payment_date"
                                                class="block mb-2 text-sm font-medium text-gray-900">Payment
                                                Date</label>
                                            <input type="text" name="payment_date" id="payment_date"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                value="{{ $item->order->payments->first() ? \Carbon\Carbon::parse($item->order->payments->first()->payment_date)->format('F d, Y') : 'N/A' }}"
                                                readonly>
                                        </div>

                                        <div class="col-span-1">
                                            <label for="status"
                                                class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                            <div name="status" id="status"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                readonly>
                                                <div value="pending">
                                                    {{ $item->order->status }}
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-span-2">
                                            <label for="feedback"
                                                class="block mb-2 text-sm font-bold text-gray-900">Feedback</label>
                                            <ul class="bg-gray-50 border border-gray-300 rounded-lg p-2">
                                                <li class="mb-2">
                                                    <strong>{{ $item->order->user->fname . ' ' . $item->order->user->lname }}:</strong>
                                                    <span>{{ $item->order->orderItems->first()->feedback->comment ?? 'No feedback yet' }}</span>
                                                    <span class="text-gray-500"> (Rating:
                                                        {{ $item->order->orderItems->first()->feedback->rating ?? 'No rating yet' }})</span>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>

                                    <div class="col-span-2 flex justify-around gap-2">
                                        <div class="col-span-1">
                                            <label for="proof_of_delivery"
                                                class="block mb-2 text-sm font-medium text-gray-900">Proof of
                                                Delivery</label>
                                            <img src="{{ asset('delivery_receipt/' . $item->order->proof_of_delivery) }}"
                                                alt="Proof of Delivery" class="w-60 object-cover"
                                                onclick="openModal(this.src)">
                                        </div>
                                        <div class="col-span-1">
                                            <label for="receipt_file"
                                                class="block mb-2 text-sm font-medium text-gray-900">Receipt
                                                File</label>
                                            <img src="{{ asset('receipt_file/' . $item->order->payments->first()->receipt_file) }}"
                                                alt="Receipt File" class="w-60 object-contain cursor-pointer"
                                                onclick="openModal(this.src)">
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                            onclick="window.location.href='{{ route('customer.tracking.delivered') }}'"
                                            class="btn bg-red-700 hover:bg-red-800 text-white inline-flex items-center focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center m-4">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
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
        $(document).ready(function() {
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#product-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });

            $('[data-modal-target]').on('click', function(e) {
                const tagName = e.target.tagName.toLowerCase();

                // Ignore if the clicked element is an input or select
                if (tagName === 'textarea' || tagName === 'label' || tagName === 'input') return;
                const modalId = $(this).data('modal-target');
                $(`#${modalId}`).removeClass('hidden');
            });
            $('[data-modal-toggle]').on('click', function(e) {
                const tagName = e.target.tagName.toLowerCase();

                // Ignore if the clicked element is an input or select
                if (tagName === 'textarea' || tagName === 'label' || tagName === 'input') return;
                const modalId = $(this).data('modal-toggle');
                $(`#${modalId}`).addClass('hidden');
            });
            $('.btn').on('click', function(event) {
                event.stopPropagation(); // Stops the click event from reaching the parent
            });
        });

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
</x-app-layout>
