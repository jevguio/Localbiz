<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-3xl">Products</h2>
            </div>
            <div class="flex items-center space-x-4">
                <form method="GET" action="{{ route('customer.products') }}">
                    <select name="category"
                        class="border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900"
                        id="categorySelect">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="relative w-full">

                    <i class='bx bx-search absolute text-gray-500 text-xl left-3 top-2.5 z-10'></i>
                    <input type="text" id="productSearch" placeholder="Search products..."
                        class="input w-full pl-10" />
                    <i id="filter-btn" class='absolute bx bx-filter text-2xl right-2 top-2'></i>
                    <div id="filter-dropdown"
                        class="hidden absolute right-0 mt-1 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                        <ul class="py-2 text-sm text-gray-700">
                            <a class="w-full block px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option"
                                href="{{ route('customer.products') }}">All</a>
                            @foreach ($seller as $sel)
                                <a class="w-full block px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option"
                                    href="{{ route('customer.products') . '?seller=' . $sel->id }}">{{ $sel->user->fname }}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>



            </div>
        </div>
        <div id="productGrid" class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($products as $product)
                <div class="product-item rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="h-56 w-full">
                        <a href="#">
                            @if (isset($product->images[0]))
                                <img class="w-full h-full object-cover"
                                    src="{{ asset('assets/' . $product->images[0]->filename) }}" alt="Product Image">
                            @else
                                <img class="w-full h-full object-cover" src="{{ asset('assets/default.png') }}"
                                    alt="No Image">
                            @endif

                        </a>
                    </div>
                    <h3 class="product-name text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                    @php
                        $average = round($product->feedback()->avg('rating'), 1); // e.g. 3.6
                        $percentage = ($average / 5) * 100;
                    @endphp
                    <style>
                        .star-rating {
                            display: inline-block;
                            font-size: 1.8rem;
                            position: relative;
                            line-height: 1;
                        }

                        .star-rating .empty-stars {
                            color: #ccc;
                        }

                        .star-rating .filled-stars {
                            color: #f5c518;
                            position: absolute;
                            top: 0;
                            left: 0;
                            overflow: hidden;
                            white-space: nowrap;
                            width: 0;
                            /* default */
                        }
                    </style>

                    <div class="star-rating mt-2">
                        <div class="empty-stars">★★★★★</div>
                        <div class="filled-stars" style="width: {{ $percentage }}%;">★★★★★</div>
                    </div>


                    <p class="text-sm text-muted mt-1">
                        @if ($product->feedback->count() == 0)
                            No Ratings
                        @else
                            {{ $average }} out of 5
                        @endif
                    </p>

                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 font-bold">₱ {{ number_format($product->price, 2, '.', ',') }}</p>
                        <p class="text-gray-500">Stock: {{ $product->stock }}</p>
                    </div>

                    <button class="btn w-full mt-4 py-2 bg-red-900 text-white rounded-md hover:bg-red-800"
                        onclick="productModal{{ $product->id }}.showModal()">View</button>
                </div>

                <dialog id="productModal{{ $product->id }}" class="modal">
                    <div class="modal-box max-w-2xl">
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>
                        <div class="flex gap-6">

                            <div id="sliderContainer" class="relative w-64 h-64 mx-auto">
                                <img id="sliderImage{{ $product->id }}"
                                    src="{{ isset($product->images[0]) ? asset('assets/' . $product->images[0]->filename) : asset('assets/default.png') }}"
                                    class="w-full h-full object-cover rounded" />

                                @if (count($product->images) > 1)
                                    <button id="prevBtn{{ $product->id }}"
                                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black text-white px-2 rounded-l">←</button>
                                    <button id="nextBtn{{ $product->id }}"
                                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black text-white px-2 rounded-r">→</button>
                                @endif
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {

                                    const images = @json($product->images);
                                    let currentIndex = 0;
                                    const sliderImage = document.getElementById('sliderImage{{ $product->id }}');

                                    function updateImage{{ $product->id }}() {
                                        if (images.length > 0) {
                                            const current = images[currentIndex];
                                            sliderImage.src = `/assets/${current.filename}`;
                                        } else {
                                            sliderImage.src = `/assets/default.png`;
                                        }
                                    }

                                    function nextImage{{ $product->id }}() {
                                        if (images.length > 0) {
                                            currentIndex = (currentIndex + 1) % images.length;
                                            updateImage{{ $product->id }}();
                                        }
                                    }

                                    function prevImage{{ $product->id }}() {
                                        if (images.length > 0) {
                                            currentIndex = (currentIndex - 1 + images.length) %
                                                images.length;
                                            updateImage{{ $product->id }}();
                                        }
                                    }

                                    const nextBtn = document.getElementById('nextBtn{{ $product->id }}');
                                    const prevBtn = document.getElementById('prevBtn{{ $product->id }}');

                                    if (nextBtn) nextBtn.addEventListener('click', nextImage{{ $product->id }});
                                    if (prevBtn) prevBtn.addEventListener('click', prevImage{{ $product->id }});
                                });
                            </script>

                            <div class="flex-1">
                                <div class="mt-6">
                                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h3>
                                    <div class="space-y-1 text-lg">
                                        <p>{{ $product->description }}</p>

                                        <p>Stock: {{ $product->stock }}</p>
                                        @if ($product->category && $product->category->name === 'Processed Foods' && $product->best_before)
                                            <p class="text-red-600">Best Before:
                                                {{ \Carbon\Carbon::parse($product->best_before)->format('M d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h2 class="text-2xl font-bold mb-4">Payment Information</h2>
                            <div class="space-y-2">
                                <p>Seller: {{ $product->seller->user->fname }}</p>
                                <p>Gcash Number/Inquiry Number: {{ $product->seller->user->gcash_number }}</p>
                                <p>Bank Name: {{ $product->seller->user->bank_name }}</p>
                                <p>Bank Account Number: {{ $product->seller->user->bank_account_number }}</p>
                                <p>Price: ₱ {{ number_format($product->price, 2, '.', ',') }}</p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h2 class="text-xl font-bold mb-4">Feedback</h2>
                            @if ($product->feedback->count() > 0)
                                @foreach ($product->feedback as $feedback)
                                    <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                                                {{ substr($feedback->user->fname, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold">
                                                    Comment {{ $loop->iteration }}
                                                </p>
                                                <label for="rating"
                                                    class="block text-sm font-medium text-gray-700">Rating</label>

                                                @php
                                                    $average = $feedback->rating; // e.g. 3.6
                                                    $percentage = ($average / 5) * 100;
                                                @endphp
                                                <style>
                                                    .star-rating {
                                                        display: inline-block;
                                                        font-size: 1.8rem;
                                                        position: relative;
                                                        line-height: 1;
                                                    }

                                                    .star-rating .empty-stars {
                                                        color: #ccc;
                                                    }

                                                    .star-rating .filled-stars {
                                                        color: #f5c518;
                                                        position: absolute;
                                                        top: 0;
                                                        left: 0;
                                                        overflow: hidden;
                                                        white-space: nowrap;
                                                        width: 0;
                                                        /* default */
                                                    }
                                                </style>

                                                <div class="star-rating mt-2">
                                                    <div class="empty-stars">★★★★★</div>
                                                    <div class="filled-stars" style="width: {{ $percentage }}%;">
                                                        ★★★★★</div>
                                                </div>

                                                <p class="text-gray-600">{{ $feedback->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-center">No feedback yet</p>
                            @endif
                        </div>

                        <div class="mt-8">
                            <form action="{{ route('customer.addToCart') }}" method="POST">
                                @csrf
                                {{-- <div class="absolute flex" style="top:32%; right: 30%;">

                                <label>Quantity</label>
                                <input type="number" min='1' max="{{  $product->stock }}" value="1" name="quantity" class="ml-2">
                                </div> --}}
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button
                                    class="w-full btn bg-red-900 hover:bg-red-800 text-white py-3 rounded-lg text-lg">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </dialog>
            @endforeach
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categorySelect').change(function() {
                $(this).closest('form').submit();
            });

            $('#locationSelect').change(function() {
                $(this).closest('form').submit();
            });

            $("#filter-btn").click(function(event) {
                event.preventDefault();
                $("#filter-dropdown").toggleClass("hidden");
            });

            $(document).click(function(event) {
                if (!$(event.target).closest("#filter-btn, #filter-dropdown").length) {
                    $("#filter-dropdown").addClass("hidden");
                }
            });


            $('#productSearch').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('#productGrid .product-item').filter(function() {
                    $(this).toggle($(this).find('.product-name').text().toLowerCase().indexOf(
                        searchTerm) > -1);
                });
            });
        });
    </script>
</x-app-layout>
