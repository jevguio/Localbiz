<x-app-layout>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-3xl">Account Management</h2>
                <button data-modal-target="addModal" class="btn btn-primary text-lg px-6 py-3" type="button">
                    Add Account
                </button>
            </div>
            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">
                <form class="max-w-md ml-0 mb-4 relative">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <!-- Search Icon -->
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class='bx bx-search text-gray-500 text-2xl'></i>
                        </div>
                        <!-- Search Input -->
                        <input type="search" id="table-search"
                            class="block w-full p-4 ps-10 pr-14 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search for account....">

                        <!-- Filter Button -->
                        <button type="button" id="filter-btn"
                            class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-600 hover:text-gray-900">
                            <i class='bx bx-filter text-2xl'></i>
                        </button>

                        <!-- Filter Dropdown -->
                        <div id="filter-dropdown"
                            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                            <ul class="py-2 text-sm text-gray-700">
                                <a href="{{ route('owner.account') }}?filter=all"
                                    class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                    data-filter="All">All</a>

                                <a href="{{ route('owner.account') }}?filter=Owner"
                                    class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                    data-filter="Owner">Admin</a>
                                    <a href="{{ route('owner.account') }}?filter=GovernmentAgency"
                                        class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                        data-filter="GovernmentAgency">DTI</a>
                                        <a href="{{ route('owner.account') }}?filter=Seller"
                                            class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                            data-filter="Seller">Sellers</a>
                                            <a href="{{ route('owner.account') }}?filter=Customer"
                                                class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                                data-filter="Customer">Customer</a>
                                                <a href="{{ route('owner.account') }}?filter=Cashier"
                                                    class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                                    data-filter="Cashier">Cashier</a>
                                                    <a href="{{ route('owner.account') }}?filter=DeliveryRider"
                                                        class="block px-4 py-2 hover:bg-gray-100 w-full cursor-pointer filter-option"
                                                        data-filter="DeliveryRider">Delivery Rider</a>
                            </ul>
                        </div>
                    </div>
                </form>

                <!-- JavaScript to toggle dropdown -->


                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email Address
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 pl-12">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 pl-14">
                                Last Login
                            </th>
                            <th scope="col" class="px-6 py-3 pl-13">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="account-table-body">
                        @foreach ($users as $user)
                            <tr class="{{ $user->is_active == 1 ? 'bg-white' : 'bg-white ' }} 
                            border-b border-gray-200 hover:{{ $user->is_active == 1 ? 'bg-gray-50' : 'bg-warning-50' }}"
                                data-category="{{ $user->role }}">
                                <th scope="row"
                                    class="px-6 py-4 font-medium {{ $user->is_active == 1 ? 'text-gray-900' : 'text-gray-900' }}   whitespace-nowrap">
                                    {{ $user->fname . ' ' . ($user->role != 'Seller' ? $user->lname : '') }}
                                </th>
                                <td class="px-6 py-4 {{ $user->is_active == 1 ? 'text-gray-900' : 'text-gray-900' }}">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 {{ $user->is_active == 1 ? 'text-gray-900' : 'text-gray-900' }}">
                                    {{ $user->role }}
                                </td>
                                <td
                                    class="px-6 py-4 pl-12 {{ $user->is_active == 1 ? 'text-gray-900' : 'text-gray-900' }} {{ $user->is_active == 1 ? ' text-green-700' : ' text-orange-700' }}">
                                    {{ $user->is_active == 1 ? 'Active' : 'Inactive' }}
                                </td>
                                <td
                                    class="px-6 py-4 pl-13 {{ $user->is_active == 1 ? 'text-gray-900' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($user->last_login)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 flex gap-2">

                                    @if ($user->role != 'Customer')
                                        <button data-modal-target="editModal{{ $user->id }}"
                                            class="font-medium bg-primary p-2  text-white hover:underline"
                                            type="button" style="border-radius:5px">
                                            Edit
                                        </button>
                                    @endif
                                    <!-- <button data-modal-target="deleteModal{{ $user->id }}"
                                        class="font-medium text-red-600 hover:underline" type="button">
                                        Delete
                                    </button> -->
                                    @if ($user->is_active == 1)
                                        <button data-modal-target="ToggleModal{{ $user->id }}"
                                            class="font-medium {{ $user->role == 'Customer' ? 'w-28' : '' }} p-2 bg-red-600 text-white hover:underline"
                                            type="button" style="border-radius:5px">
                                            Disable
                                        </button>
                                    @else
                                        <button data-modal-target="ToggleModal{{ $user->id }}"
                                            class="font-medium p-2 {{ $user->role == 'Customer' ? 'w-28' : '' }} bg-green-600 text-white hover:underline"
                                            type="button" style="border-radius:5px">
                                            Enable
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <div id="editModal{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Edit Account
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="editModal{{ $user->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <div class="grid gap-4 mb-4 p-4">
                                            <form action="{{ route('owner.account.update', $user->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="grid gap-4 mb-4 grid-cols-2">
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="fname">{{ $user->role != 'Seller' ? 'First Name' : 'Seller Business Name' }}</label>
                                                        <input type="text" name="fname" id="fname"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            placeholder='{{ $user->role != 'Seller' ? 'First Name' : 'Seller Business Name' }}'
                                                            value="{{ $user->fname }}">
                                                    </div>
                                                    <div class="col-span-2">
                                                        @if ($user->role != 'Seller')
                                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                                for="lname">Last Name</label>
                                                            <input type="text" name="lname" id="lname"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                                placeholder="Type last name"
                                                                value="{{ $user->lname }}">
                                                        @endif
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="email">Email Address</label>
                                                        <input type="email" name="email" id="email"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            placeholder="Type email address"
                                                            value="{{ $user->email }}">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="phone">Contact Number</label>
                                                        <input type="text" name="phone" id="phone"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            placeholder="Type contact number"
                                                            value="{{ $user->phone }}" maxlength="11"
                                                            pattern="[0-9]{11}" inputmode="numeric">
                                                    </div>

                                                    @if (!in_array($user->role, ['Cashier', 'DeliveryRider', 'Owner']))
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="role">Role</label>
                                                        <input type="text" name="role" id="role"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                            value="{{ $user->role }}" readonly>
                                                    </div>
                                                    @endif
                                                    <div class="col-span-2">
                                                        <label class="block mb-2 text-sm font-medium text-gray-900"
                                                            for="is_active">Is Active</label>
                                                        <select name="is_active" id="is_active"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                                            <option value="1"
                                                                {{ $user->is_active == 1 ? 'selected' : '' }}>Active
                                                            </option>
                                                            <option value="0"
                                                                {{ $user->is_active == 0 ? 'selected' : '' }}>Inactive
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr class="my-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Save
                                                    </button>
                                                    <button type="button"
                                                        data-modal-toggle="editModal{{ $user->id }}"
                                                        class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Delete Account
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="deleteModal{{ $user->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <div class="grid gap-4 mb-4 p-4">
                                            <form action="{{ route('owner.account.destroy', $user->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('DELETE')
                                                <p>Are you sure you want to delete this account?</p>
                                                <hr class="my-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Delete
                                                    </button>
                                                    <button type="button"
                                                        data-modal-toggle="deleteModal{{ $user->id }}"
                                                        class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ToggleModal{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                                <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                                    <div class="relative bg-white rounded-lg shadow-sm">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                {{ $user->is_active == 1 ? 'Disable' : 'Enable' }} Account
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-toggle="ToggleModal{{ $user->id }}">
                                                <i class='bx bx-x text-gray-500 text-2xl'></i>
                                            </button>
                                        </div>
                                        <div class="grid gap-4 mb-4 p-4">
                                            <form action="{{ route('owner.account.toggle', $user->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <p>Are you sure you want to
                                                    {{ $user->is_active == 1 ? 'Disable' : 'Enable' }} this account?
                                                </p>
                                                <hr class="my-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="btn btn-primary text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        {{ $user->is_active == 1 ? 'Disable' : 'Enable' }}
                                                    </button>
                                                    <button type="button"
                                                        data-modal-toggle="ToggleModal{{ $user->id }}"
                                                        class="btn btn-error text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Close
                                                    </button>
                                                </div>
                                            </form>
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
                            class="font-semibold text-gray-900">{{ $users->firstItem() }}-{{ $users->lastItem() }}</span>
                        of <span class="font-semibold text-gray-900">{{ $users->total() }}</span></span>
                    <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                        {{ $users->links() }}
                    </ul>
                </nav>

                <div id="addModal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black bg-opacity-50">
                    <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
                        <div class="relative bg-white rounded-lg shadow-sm">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900">
                                    Add Account
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    data-modal-toggle="addModal">
                                    <i class='bx bx-x text-gray-500 text-2xl'></i>
                                </button>
                            </div>
                            <div class="grid gap-4 mb-4 p-4">
                                <form action="{{ route('owner.account.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="role">Role</label>
                                            <select name="role" id="add_seller_role"
                                                onchange="onRoleSeller(event)"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                                <option value="Seller">Seller</option>
                                                <option value="GovernmentAgency">DTI</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                id="firstName_add" for="fname">First Name</label>
                                            <input type="text" name="fname" id="fname_input"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type first name" value="{{ old('fname') }}" required>
                                        </div>
                                        <div class="col-span-2" id="lastName_container">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                id="lastName_add" for="lname">Last Name</label>
                                            <input type="text" name="lname" id="lname_input"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type last name" value="{{ old('lname') }}" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="email">Email Address</label>
                                            <input type="email" name="email" id="email"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type email address" value="{{ old('email') }}"
                                                required>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="password">Password</label>
                                            <input type="password" name="password" id="password"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type password" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="address">Address</label>
                                            <input type="text" name="address" id="address"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type address" value="{{ old('address') }}" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900"
                                                for="phone">Phone Number</label>
                                            <input type="tel" name="phone" id="phone"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                                placeholder="Type phone number" value="{{ old('phone') }}"
                                                maxlength="11" required>
                                        </div>

                                        <script>
                                            onRoleSeller(null);

                                            function onRoleSeller(event) {
                                                const add_seller_role = document.getElementById('add_seller_role');

                                                const firstName_add = document.getElementById('firstName_add');
                                                const lastName_add = document.getElementById('lname_input');
                                                const lastName_container = document.getElementById('lastName_container');

                                                const fname = document.getElementById('fname_input');
                                                const isTrue = event ? event.target.value == "Seller" : true;

                                                if (isTrue) {
                                                    lastName_add.value = "NA";
                                                    lastName_container.style.display = "none";
                                                    fname.setAttribute('placeholder', 'Seller Business Name');
                                                    firstName_add.innerHTML = "Seller Business Name";
                                                } else {
                                                    lastName_add.value = "";
                                                    lastName_container.style.display = "block";
                                                    fname.setAttribute('placeholder', 'First Name');
                                                    firstName_add.innerHTML = "First Name";
                                                }
                                            }
                                        </script>
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
            let selectedFilter = "All"; // Default filter

            // Toggle filter dropdown
            $("#filter-btn").click(function(event) {
                event.preventDefault();
                $("#filter-dropdown").toggleClass("hidden");
            });

            // Search and filter function
            function filterTable() {
                const searchInput = $("#table-search").val().toLowerCase();

                $("#account-table-body tr").each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const rowCategory = $(this).data("category");

                    const matchesSearch = rowText.indexOf(searchInput) > -1;
                    const matchesFilter = selectedFilter === "All" || rowCategory === selectedFilter;

                    $(this).toggle(matchesSearch && matchesFilter);
                });
            } 

            // Close dropdown when clicking outside
            $(document).click(function(event) {
                if (!$(event.target).closest("#filter-btn, #filter-dropdown").length) {
                    $("#filter-dropdown").addClass("hidden");
                }
            });

            // Apply search
            $("#table-search").on("keyup", filterTable);

            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#account-table-body tr').filter(function() {
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
