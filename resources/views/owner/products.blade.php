<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900 sm:text-3xl">Products</h1> 
            </div>
            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">
                <form class="max-w-md ml-0 mb-4 relative">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">

                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class='bx bx-search text-gray-500 text-2xl'></i>
                        </div>

                        <input type="search" id="table-search"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search for products....">
                            
                            <!-- Filter Button -->
                        <button type="button" id="filter-btn"
                            class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-600 hover:text-gray-900">
                            <i class='bx bx-filter text-2xl'></i>
                        </button>

                        <!-- Filter Dropdown -->
                        <div id="filter-dropdown"
                            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                            <ul class="py-2 text-sm text-gray-700"> 
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="All">All</li>
                           
                            @foreach ($sellers as $seller)
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="{{$seller->user->fname}}">{{$seller->user->fname}}</li>
                            @endforeach 
                            </ul>
                        </div>
                    </div>
                    
                </form>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Product Image
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Stock
                            </th>
                            <th scope="col" class="px-6 py-3 hidden" >
                                Seller
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        @foreach ($products as $product)
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50"  data-category="{{ $product->seller->user->fname }}">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <img src="{{ asset('assets/' . $product->image) }}" alt="Product Image"
                                        class="w-10 h-10">
                                </th>
                                <td class="px-6 py-4">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 pl-11">
                                    {{ $product->price }}
                                </td>
                                <td class="px-6 py-4 pl-15">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-6 py-4 hidden">
                                    {{ $product->seller->user->fname }}
                                </td>
                                <td class="px-6 py-4">
                                    <button data-modal-target="viewModal{{ $product->id }}"
                                        class="font-medium text-blue-600 hover:underline" type="button">
                                        View
                                    </button>
                                </td>
                            </tr>

                            <div id="viewModal{{ $product->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                View Product
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="viewModal{{ $product->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <img src="{{ asset('assets/' . $product->image) }}" alt="Product Image"
                                            class="w-60 mx-auto">
                                        <div class="grid grid-cols-2 gap-4 mb-4 p-4">
                                            <div class="col-span-1">
                                                <label for="seller_id"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Seller
                                                    Business Name</label>
                                                <input type="text" name="seller_id" id="seller_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type seller name"
                                                    value="{{ $product->seller->user->fname }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="product_name"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                                <input type="text" name="name" id="name"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type product name" value="{{ $product->name }}"
                                                    readonly>
                                            </div>
                                            <div class="col-span-2">
                                                <label for="description"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                                                <textarea id="description" rows="4"
                                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Write product description here" name="description" readonly>{{ $product->description }}</textarea>
                                            </div>
                                            <div class="col-span-2">
                                                <label for="location"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Location</label>
                                                <input type="text" name="location" id="location"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="Type product location"
                                                    value="{{ $product->location->name }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="price"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                                                <input type="number" name="price" id="price"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    placeholder="$2999" value="{{ $product->price }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="stock"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Stock</label>
                                                <input type="number" name="stock" id="stock"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ $product->stock }}" readonly>
                                            </div>
                                            <div class="col-span-1">
                                                <label for="category"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                                <div id="category" name="category_id" disabled readonly
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                                     
                                                    @foreach ($categories as $category)
                                                        @if($product->category_id == $category->id ) 
                                                        <div>
                                                            {{ $category->name }}
                                                        
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <!-- <div class="col-span-1">
                                                <label for="status"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                <select name="status" id="status"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                                    <option value="1" {{ $product->is_active ? 'selected' : '' }}
                                                        readonly>Active
                                                    </option>
                                                    <option value="0"
                                                        {{ !$product->is_active ? 'selected' : '' }} readonly>
                                                        Inactive
                                                    </option>
                                                </select>
                                            </div> -->
                                            <!-- <hr class="my-4"> -->
                                            <div class="flex justify-end">
                                                <button type="button"
                                                    data-modal-toggle="viewModal{{ $product->id }}"
                                                    class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
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
                            class="font-semibold text-gray-900">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
                        of <span class="font-semibold text-gray-900">{{ $products->total() }}</span></span>
                    <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                        {{ $products->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
           
            let selectedFilter = "All"; // Default filter

        // Toggle filter dropdown
        $("#filter-btn").click(function (event) {
            event.preventDefault();
            $("#filter-dropdown").toggleClass("hidden");
        });

        // Search and filter function
        function filterTable() {
            const searchInput = $("#table-search").val().toLowerCase();

            $("#product-table-body tr").each(function () {
                const rowText = $(this).text().toLowerCase();
                const rowCategory = $(this).data("category");

                const matchesSearch = rowText.indexOf(searchInput) > -1;
                const matchesFilter = selectedFilter === "All" || rowCategory === selectedFilter;

                $(this).toggle(matchesSearch && matchesFilter);
            });
        }
        // Apply filter
        $(".filter-option").click(function () {
            selectedFilter = $(this).data("filter");
            $("#filter-dropdown").addClass("hidden"); // Hide dropdown after selection
            filterTable();
        });

        // Close dropdown when clicking outside
        $(document).click(function (event) {
            if (!$(event.target).closest("#filter-btn, #filter-dropdown").length) {
                $("#filter-dropdown").addClass("hidden");
            }
        });
            
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
