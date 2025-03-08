<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Cashier Management</h2>
                <button data-modal-target="addModal" class="btn btn-primary" type="button">
                    Add Cashier
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
                            placeholder="Search for cashier....">
                    </div>
                </form>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Cashier Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Is Approved
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        @foreach ($cashiers as $cashier)
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $cashier->user->fname." ". $cashier->user->lname }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $cashier->is_approved ? 'Approved' : 'Pending' }}
                                </td>
                                <td class="flex gap-2 px-6 py-4">
                                    <button data-modal-target="editModal{{ $cashier->id }}"
                                        class="font-medium text-green-600 hover:underline" type="button">
                                        Edit
                                    </button>
                                    <button data-modal-target="deleteModal{{ $cashier->id }}"
                                        class="font-medium text-red-600 hover:underline" type="button">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            <div id="editModal{{ $cashier->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Edit Cashier
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="editModal{{ $cashier->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <div class="grid gap-4 mb-4 p-4">
                                            <div class="grid gap-4 mb-4 grid-cols-2">
                                                <form action="{{ route('seller.cashier.update', $cashier->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="fname">Cashier Frist Name</label>
                                                        <input type="text" name="fname" id="fname"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            placeholder="Type cashier first name"
                                                            value="{{ old('fname', $cashier->user->fname) }}">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="lname">Cashier Last Name</label>
                                                        <input type="text" name="lname" id="lname"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            placeholder="Type cashier last name"
                                                            value="{{ old('lname', $cashier->user->lname) }}">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <img src="{{ asset('cashier/documents/' . $cashier->document_file) }}"
                                                            alt="Cashier Document File"
                                                            class="w-36 h-36 object-cover">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="is_approved">Is Approved</label>
                                                        <select name="is_approved" id="is_approved"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            value="{{ old('is_approved', $cashier->is_approved ? 'Approved' : 'Pending') }}">
                                                            <option value="1"
                                                                {{ $cashier->is_approved ? 'selected' : '' }}>Approved
                                                            </option>
                                                            <option value="0"
                                                                {{ !$cashier->is_approved ? 'selected' : '' }}>Pending
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <hr class="my-4">
                                                    <div class="flex justify-end gap-2">
                                                        <button type="submit"
                                                            class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                            Save
                                                        </button>
                                                        <button type="button"
                                                            data-modal-toggle="editModal{{ $cashier->id }}"
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

                            <div id="deleteModal{{ $cashier->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Delete Cashier
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="deleteModal{{ $cashier->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <div class="grid gap-4 mb-4 p-4">
                                            <div class="grid gap-4 mb-4 grid-cols-2">
                                                <form action="{{ route('seller.cashier.destroy', $cashier->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p class="text-gray-900">Are you sure you want to delete this
                                                        cashier?</p>
                                                    <hr class="my-4">
                                                    <div class="flex justify-end gap-2">
                                                        <button type="submit"
                                                            class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                            Delete
                                                        </button>
                                                        <button type="button"
                                                            data-modal-toggle="deleteModal{{ $cashier->id }}"
                                                            class="btn btn-secondary text-white inline-flex items-center bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                            Close
                                                        </button>
                                                    </div>
                                                </form>
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
                            class="font-semibold text-gray-900">{{ $cashiers->firstItem() }}-{{ $cashiers->lastItem() }}</span>
                        of <span class="font-semibold text-gray-900">{{ $cashiers->total() }}</span></span>
                    <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                        {{ $cashiers->links() }}
                    </ul>
                </nav>

                <div id="addModal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                    <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                        <div class="relative bg-white rounded-lg shadow-sm">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900">
                                    Add Cashier
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    data-modal-toggle="addModal">
                                    <i class='bx bx-x text-gray-500 text-2xl'></i>
                                </button>
                            </div>
                            <div class="grid gap-4 mb-4 p-4">
                                <form action="{{ route('seller.cashier.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="fname">Cashier First Name</label>
                                            <input type="text" name="fname" id="fname"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type cashier frist name" value="{{ old('fname') }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="lname">Cashier Last Name</label>
                                            <input type="text" name="lname" id="lname"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type cashier last name" value="{{ old('lname') }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="email">Cashier Email</label>
                                            <input type="email" name="email" id="email"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type cashier email" value="{{ old('email') }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="address">Cashier Address</label>
                                            <textarea name="address" id="address"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type cashier address">{{ old('address') }}</textarea>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="phone">Cashier Phone</label>
                                            <input type="text" name="phone" id="phone"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type cashier phone" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="submit"
                                            class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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
    </script>
</x-app-layout>
