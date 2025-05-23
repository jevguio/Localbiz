<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900 sm:text-3xl">Products</h2>
                <button data-modal-target="addModal" class="btn btn-primary text-lg px-6 py-6 w-full md:w-auto rounded-lg"
                    type="button">
                    Add Product
                </button>

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
                            placeholder="Search for products....">
                    </div>
                </form>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 pl-15 text-black">
                                Product Image
                            </th>
                            <th scope="col" class="px-6 py-3 text-black">
                                Product Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-black">
                                Product Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-black">
                                Product Stock
                            </th>
                            <th scope="col" class="px-6 py-3 pl-9 text-black">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        @foreach ($products as $product)
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <img data-modal-target="viewModal"
                                        onclick='OnImageShow(@json($product->images), 0)'
                                        src="{{ asset('assets/' . ($product->images[0]->filename ?? 'default.png')) }}"
                                        alt="Product Image" class="w-50 h-50 object-cover rounded">

                                </th>
                                <td class="px-6 py-4 pl-6 text-black">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 pl-12 text-black">
                                    {{ $product->price }}
                                </td>
                                <td class="px-6 py-4 pl-15 text-black">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-6 py-4 gap-2">
                                    <button data-modal-target="editModal{{ $product->id }}"
                                        class="font-medium text-blue-600 hover:underline" type="button">
                                        Edit
                                    </button>
                                    <button data-modal-target="deleteModal{{ $product->id }}"
                                        class="font-medium text-red-600 hover:underline" type="button">
                                        Archive
                                    </button>
                                </td>
                            </tr>

                            <div id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-opacity-50" style="background-color: rgba(0,0,0,0.75);">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm p-4">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Edit Product
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="editModal{{ $product->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <hr class="my-4">


                                        <div class="grid gap-4 mb-4 p-4">
                                            <form action="{{ route('seller.products.update', $product->id) }}"
                                                method="POST" enctype="multipart/form-data"
                                                id="formUpdate{{ $product->id }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid gap-4 mb-4 p-4 grid-cols-2">
                                                    <div class="col-span-2 ">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="image">Product Images</label>

                                                        <!-- Hidden File Input -->
                                                        <input id="imageInput{{ $product->id }}" type="file"
                                                            class="hidden" name="image[]" accept="image/*" multiple />

                                                        <!-- Preview Container -->
                                                        <div id="imagePreviewContainer{{ $product->id }}"
                                                            class="flex flex-wrap gap-4">
                                                            <!-- EXISTING product images -->
                                                            @foreach ($product->images as $img)
                                                                <div
                                                                    class="w-32 h-32 border rounded-lg overflow-hidden shadow relative">
                                                                    <img src="{{ asset('assets/' . $img->filename) }}"
                                                                        class="w-full h-full object-cover">

                                                                    <a href="{{ route('seller.products.delete.image') }}?id={{ $img->id }}"
                                                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-bl px-1 text-xs">✕</a>

                                                                </div>
                                                            @endforeach

                                                            <!-- Add Image Card -->
                                                            <div id="addImageCard{{ $product->id }}"
                                                                onclick="document.getElementById('imageInput{{ $product->id }}').click()"
                                                                class="w-32 h-32 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-lg cursor-pointer hover:bg-gray-100">
                                                                <span class="text-gray-500 text-sm">+ Add Image</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Other product fields go here -->

                                                <button type="submit" id="formUpdateBTN{{ $product->id }}"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded">Update
                                                    Product</button>
                                            </form>
                                            <!-- JavaScript for preview -->
                                            <script>
                                                const formUpdateBTN{{ $product->id }} = document.getElementById('formUpdateBTN{{ $product->id }}');
                                                const formUpdate{{ $product->id }} = document.getElementById('formUpdate{{ $product->id }}');
                                                const imageInput{{ $product->id }} = document.getElementById('imageInput{{ $product->id }}');
                                                const previewContainer{{ $product->id }} = document.getElementById('imagePreviewContainer{{ $product->id }}');
                                                const addImageCard{{ $product->id }} = document.getElementById('addImageCard{{ $product->id }}');

                                                let selectedFiles{{ $product->id }} = [];
                                                formUpdateBTN{{ $product->id }}.addEventListener('click', (event) => {
                                                    formUpdate{{ $product->id }}.submit();
                                                })
                                                imageInput{{ $product->id }}.addEventListener('change', (event) => {
                                                    const files = Array.from(event.target.files);

                                                    files.forEach(file => {
                                                        selectedFiles{{ $product->id }}.push(file);
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => {
                                                            const card = document.createElement('div');
                                                            card.className = 'w-32 h-32 border rounded-lg overflow-hidden shadow relative';

                                                            const img = document.createElement('img');
                                                            img.src = e.target.result;
                                                            img.className = 'w-full h-full object-cover';

                                                            const removeBtn = document.createElement('button');
                                                            removeBtn.textContent = '✕';
                                                            removeBtn.className =
                                                                'absolute top-0 right-0 bg-red-500 text-white rounded-bl px-1 text-xs';
                                                            removeBtn.onclick = () => {
                                                                selectedFiles{{ $product->id }} = selectedFiles{{ $product->id }}
                                                                    .filter(f => f !== file);
                                                                card.remove();
                                                                updateInputFiles();
                                                            };

                                                            card.appendChild(img);
                                                            card.appendChild(removeBtn);
                                                            previewContainer{{ $product->id }}.insertBefore(card,
                                                                addImageCard{{ $product->id }});
                                                        };

                                                        reader.readAsDataURL(file);
                                                    });

                                                    updateInputFiles{{ $product->id }}();
                                                });

                                                function updateInputFiles{{ $product->id }}() {
                                                    const dataTransfer = new DataTransfer();
                                                    selectedFiles{{ $product->id }}.forEach(file => dataTransfer.items.add(file));
                                                    imageInput{{ $product->id }}.files = dataTransfer.files;
                                                }
                                            </script>


                                        </div>
                                        <div class="col-span-2">
                                            <label for="product_name"
                                                class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                            <input type="text" name="name" id="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type product name"
                                                value="{{ old('name', $product->name) }}">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="description"
                                                class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                                            <textarea id="description" rows="4"
                                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Write product description here" name="description">{{ old('description', $product->description) }}</textarea>
                                        </div>

                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="price"
                                                class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                                            <input type="number" name="price" id="price"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="$2999" value="{{ old('price', $product->price) }}">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="stock"
                                                class="block mb-2 text-sm font-medium text-gray-900">Stock</label>
                                            <input type="number" name="stock" id="stock" readonly disabled
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                value="{{ old('stock', $product->stock) }}">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="category"
                                                class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                            <select id="category_{{ $product->id }}" name="category_id"
                                                onchange="categoryChange{{ $product->id }}(event)"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                                <option selected="">Select category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <script>
                                            // Add this to check on page load
                                            function categoryChange{{ $product->id }}(e) {
                                                let bestBeforeDiv = document.getElementById('bestBeforeDateDiv_{{ $product->id }}');
                                                let selectedOption = e.target.options[e.target.selectedIndex];
                                                console.log(selectedOption.text.toLowerCase() == 'processed foods' ? selectedOption.text.toLowerCase() :
                                                    'none');
                                                bestBeforeDiv.style.display = selectedOption.text.toLowerCase() == 'processed foods' ? 'block' : 'none';
                                            };
                                        </script>
                                        <div id="bestBeforeDateDiv_{{ $product->id }}"
                                            style="display: @if ($product->category_id == 3) block @else none @endif;"
                                            class="col-span-2 sm:col-span-1">
                                            <div class="mt-2">
                                                <label for="best_before_date"
                                                    class="block mb-2 text-sm font-medium text-gray-900">Best
                                                    Before Date</label>
                                                <input type="date" name="best_before_date"
                                                    id="best_before_date_{{ $product->id }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                    value="{{ old('best_before_date', $product->best_before_date ?? '') }}">
                                            </div>
                                        </div>

                                    </div>
                                    <hr class="my-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="submit"
                                            class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                            Save
                                        </button>
                                        <button type="button" data-modal-toggle="editModal{{ $product->id }}"
                                            class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                            Close
                                        </button>
                                    </div>
                                    </form>
                                </div>
                            </div>
            </div>
        </div>

        <div id="deleteModal{{ $product->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-opacity-50" style="background-color: rgba(0,0,0,0.75);">
            <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                <div class="relative bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            Archive Product
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="deleteModal{{ $product->id }}">
                            <i class='bx bx-x text-gray-500 text-2xl'></i>
                        </button>
                    </div>
                    <form action="{{ route('seller.products.archive', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex flex-col items-center p-4">

                            @foreach ($product->images as $img)
                                <div class="w-32 h-32 border rounded-lg overflow-hidden shadow relative">
                                    <img src="{{ asset('assets/' . $img->filename) }}"
                                        class="w-full h-full object-cover">
                                    <form action="{{ route('seller.products.delete.image', $img->id) }}"
                                        method="POST" class="absolute top-0 right-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="absolute top-0 right-0 bg-red-500 text-white rounded-bl px-1 text-xs">✕</button>
                                    </form>
                                </div>
                            @endforeach
                            <p class="text-sm text-gray-500">Are you sure you want to archive this product?</p>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-end gap-2 p-4">
                            <button type="submit" data-modal-toggle="deleteModal{{ $product->id }}"
                                class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Archive
                            </button>
                            <button type="button" data-modal-toggle="deleteModal{{ $product->id }}"
                                class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Close
                            </button>
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
            <span class="text-sm font-normal text-gray-500 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
                <span
                    class="font-semibold text-gray-900">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
                of <span class="font-semibold text-gray-900">{{ $products->total() }}</span></span>
            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                {{ $products->links() }}
            </ul>
        </nav>

        <div id="viewModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-opacity-1" style="background-color: rgba(0,0,0,0.75);">
            <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">

                <button type="button"
                    class="text-gray-400 absolute right-3 z-5 top-3 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-toggle="viewModal">
                    <i class='bx bx-x text-gray-500 text-2xl'></i>
                </button>
                <div class="relative bg-white rounded-lg pt-5 shadow-sm">
                    <img src="" id="view_image" alt="Product Image" class="h-90 mx-auto max-w-5xl  object-cover rounded">

                    <button type="button" onclick="nextPrev(-1)"
                        class="text-gray-400 absolute left-3 z-5 top-50 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <i class='bx bx-left-arrow text-gray-500 text-2xl'></i>
                    </button>
                    <button type="button" onclick="nextPrev(+1)"
                        class="text-gray-400 absolute right-3 z-5 top-50 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <i class='bx bx-right-arrow text-gray-500 text-2xl'></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="addModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full" style="background-color: rgba(0,0,0,0.75);">
            <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                <div class="relative bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            Add Product
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="addModal">
                            <i class='bx bx-x text-gray-500 text-2xl'></i>
                        </button>
                    </div>
                    <div class="grid gap-4 mb-4 p-4">
                        <form action="{{ route('seller.products.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="grid gap-4 mb-4 grid-cols-2">
                                <div class="col-span-2">
                                    <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Product
                                        Images</label>

                                    <!-- Hidden File Input -->
                                    <input id="imageInput" type="file" class="hidden" name="image[]"
                                        accept="image/*" multiple required />

                                    <!-- Preview Container with "Add Image" Card -->
                                    <div id="imagePreviewContainer" class="flex flex-wrap gap-4">
                                        <!-- Add Image Card -->
                                        <div id="addImageCard" onclick="document.getElementById('imageInput').click()"
                                            class="w-32 h-32 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-lg cursor-pointer hover:bg-gray-100">
                                            <span class="text-gray-500 text-sm">+ Add Image</span>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    const imageInput = document.getElementById('imageInput');
                                    const previewContainer = document.getElementById('imagePreviewContainer');
                                    const addImageCard = document.getElementById('addImageCard');

                                    let selectedFiles = [];
                                    imageInput.addEventListener('change', (event) => {
                                        const files = Array.from(event.target.files);

                                        files.forEach(file => {
                                            selectedFiles.push(file);
                                            const index = selectedFiles.length - 1;

                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                const card = document.createElement('div');
                                                card.className = 'w-32 h-32 border rounded-lg overflow-hidden shadow relative';

                                                const img = document.createElement('img');
                                                img.src = e.target.result;
                                                img.className = 'w-full h-full object-cover';

                                                card.appendChild(img);
                                                previewContainer.insertBefore(card, addImageCard);
                                                const removeBtn = document.createElement('button');
                                                removeBtn.textContent = '✕';
                                                removeBtn.className =
                                                    'absolute top-0 right-0 bg-red-500 text-white rounded-bl px-1 text-xs';
                                                removeBtn.onclick = () => {
                                                    selectedFiles.splice(index, 1);
                                                    card.remove();
                                                    updateInputFiles();
                                                };

                                                card.appendChild(removeBtn);

                                            };

                                            reader.readAsDataURL(file);
                                        });

                                        updateInputFiles();
                                        console.log(imageInput.files);
                                    });

                                    function updateInputFiles() {
                                        const dataTransfer = new DataTransfer();
                                        selectedFiles.forEach(file => dataTransfer.items.add(file));
                                        imageInput.files = dataTransfer.files;
                                    }
                                </script>


                                <div class="col-span-2">
                                    <label for="name"
                                        class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                    <input type="text" name="name" id="name"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                        placeholder="Type product name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="description"
                                        class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                                    <textarea id="description" rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Write product description here" name="description" required>{{ old('description') }}</textarea>
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="category_1"
                                        class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                    <select id="category_1" name="category_id" onchange="categoryChange(event)"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                        <option selected="">Select category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="number" name="is_active" id="price" class="hidden"
                                    value="1">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="price"
                                        class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                                    <input type="number" name="price" id="price" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                        placeholder="P2999" value="{{ old('price') }}">
                                </div>

                                <div id="bestBeforeDateDiv" style="display: none;" class="col-span-2 sm:col-span-1">
                                    <div class="mt-2">
                                        <label for="best_before_date"
                                            class="block mb-2 text-sm font-medium text-gray-900">Best Before
                                            Date</label>
                                        <input type="date" name="best_before_date" id="best_before_date"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                    </div>
                                </div>

                                <script>
                                    function categoryChange(e) {
                                        let bestBeforeDiv = document.getElementById('bestBeforeDateDiv');
                                        let selectedOption = e.target.options[e.target.selectedIndex];
                                        console.log(selectedOption.text.toLowerCase());
                                        bestBeforeDiv.style.display = selectedOption.text.toLowerCase() == 'processed foods' ? 'block' : 'none';
                                    };
                                </script>
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="stock"
                                        class="block mb-2 text-sm font-medium text-gray-900">Stock</label>
                                    <input type="number" name="stock" id="stock" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                        value="{{ old('stock') }}">
                                </div>

                                <hr class="my-4">
                                <div class="flex justify-end gap-2 w-full">
                                    <button type="submit"
                                        class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ms-auto">
                                        Save
                                    </button>
                                    <button type="button" data-modal-toggle="addModal"
                                        class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Close
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

        let imageList = [];
        let indexImage = 0;

        function OnImageShow(images, startIndex = 0) {
            imageList = images;
            console.log(images);
            indexImage = startIndex;

            const view_image = document.getElementById('view_image');
            if (view_image && imageList.length > 0) {
                view_image.src = "/assets/" + imageList[indexImage].filename;
            }
        }

        function nextPrev(step) {
            indexImage += step;

            if (indexImage < 0) {
                indexImage = imageList.length - 1;
            } else if (indexImage >= imageList.length) {
                indexImage = 0;
            }

            const view_image = document.getElementById('view_image');
            if (view_image && imageList.length > 0) {

                view_image.src = "/assets/" + imageList[indexImage].filename;
            }
        }
    </script>


</x-app-layout>
