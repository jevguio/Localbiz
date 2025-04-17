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
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <form method="GET" action="{{ route('customer.products') }}">
                    <select name="location"
                        class="border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900"
                        id="locationSelect">
                        <option value="">All Locations</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <div class="relative w-full">

                    <i class='bx bx-search absolute text-gray-500 text-xl left-3 top-2.5 z-10'></i>
                    <input type="text" id="productSearch" placeholder="Search products..." class="input w-full pl-10" />
                    <i id="filter-btn" class='absolute bx bx-filter text-2xl right-2 top-2'></i>
                    <div id="filter-dropdown"
                    class="hidden absolute right-0 mt-1 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                    <ul class="py-2 text-sm text-gray-700">
                         <a class="w-full block px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" href="{{route('customer.products')}}">All</a>
                         @foreach ($seller as $sel)
                         <a class="w-full block px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" href="{{route('customer.products').'?seller='.$sel->id}}">{{$sel->user->fname}}</a>
                        
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
                            <img class="w-full h-full object-cover" src="{{ asset('assets/' . $product->image) }}"
                                alt="{{ $product->name }}" />
                        </a>
                    </div>
                    <h3 class="product-name text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                    <p class="text-gray-500 font-semibold">Location: {{ $product->location->name }}</p>
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
                            <div class="w-72 h-72 flex items-center justify-center p-4">
                                <img class="w-full h-full object-contain" src="{{ asset('assets/' . $product->image) }}"
                                    alt="{{ $product->name }}" />
                            </div>
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <div class="space-y-1 text-lg">
                                    <p>{{ $product->description }}</p>
                                    <p>Location: {{ $product->location->name }}</p>
                                    <p>Stock: {{ $product->stock }}</p>
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
                            <h2 class="text-2xl font-bold mb-4">Feedback</h2>
                            @foreach ($product->feedback as $feedback)
                                <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                                            {{ substr($feedback->user->fname, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $feedback->user->fname . ' ' . $feedback->user->lname }}</p>
                                            <p class="text-gray-600">{{ $feedback->comment ?? 'No feedback yet' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            <form action="{{ route('customer.addToCart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button class="w-full btn bg-red-900 hover:bg-red-800 text-white py-3 rounded-lg text-lg">
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
        $(document).ready(function () {
            $('#categorySelect').change(function () {
                $(this).closest('form').submit();
            });

            $('#locationSelect').change(function () {
                $(this).closest('form').submit();
            });

            $("#filter-btn").click(function (event) {
                event.preventDefault();
                $("#filter-dropdown").toggleClass("hidden");
            });

            $(document).click(function (event) {
                if (!$(event.target).closest("#filter-btn, #filter-dropdown").length) {
                    $("#filter-dropdown").addClass("hidden");
                }
            });


            $('#productSearch').on('keyup', function () {
                var searchTerm = $(this).val().toLowerCase();
                $('#productGrid .product-item').filter(function () {
                    $(this).toggle($(this).find('.product-name').text().toLowerCase().indexOf(
                        searchTerm) > -1);
                });
            });
        });
    </script>
</x-app-layout>