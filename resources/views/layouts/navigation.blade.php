<nav class="bg-white h-screen fixed top-0 left-0 min-w-[250px] py-6 px-4 shadow-lg border-r border-gray-200">
    <div class="relative">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="logo" class='w-46' />
        </a>
        <i
            class='bx bx-chevron-right absolute -right-6 top-2 p-2 text-lg cursor-pointer bg-blue-700 flex items-center justify-center rounded-full text-white'></i>
    </div>

    <div class="overflow-auto py-6 h-full mt-4">
        <ul class="space-y-1">
            @if (Auth::user()->role == 'Owner')
                <li>
                    <div class="group">
                        <a href="{{ route('owner.account') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-user-circle text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Accounts</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('owner.products') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Products</span>
                        </a>
                    </div>
                </li>
                {{-- <li>
                    <div class="group">
                        <a href="{{ route('owner.courier') }}"
                            class="text-gray-800 text-sm flex items-center group-hover:text-blue-600 transition-all">
                            <i class='bx bxs-truck text-2xl mr-4 text-gray-400 group-hover:text-blue-600'></i>
                            <span>Courier Management</span>
                        </a>
                    </div>
                </li> --}}
                <li>
                    <div class="group">
                        <a href="{{ route('owner.orders') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-cart text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Orders</span>
                        </a>
                    </div>
                </li>
                
                <li>
                    <div class="group">
                        <a href="{{ route('owner.inventory') }}?filter=all"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Inventory</span>
                        </a>
                    </div>
                </li>

                <li>
                    <div class="group">
                        <a href="{{ route('owner.reports') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-file-pdf text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Reports</span>
                        </a>
                    </div>
                </li>
            @endif


            @if (Auth::user()->role == 'Customer')
                <li>
                    <div class="group">
                        <a href="{{ route('customer.products') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Home</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('customer.cart') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-cart text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Shopping Cart</span>
                        </a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#"
                        class="dropdown-toggle text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all"
                        onclick="toggleDropdown(this)">
                        <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                        <span>Orders</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="dropdown-item"><a href="{{ route('customer.tracking.all') }}"
                            class="text-sm text-gray-700 hover:text-orange-900">All</a></li>
                        <li class="dropdown-item"><a href="{{ route('customer.tracking.pending') }}"
                                class="text-sm text-gray-700 hover:text-orange-900">Pending</a></li>
                        <li class="dropdown-item"><a href="{{ route('customer.tracking.processed') }}"
                                class="text-sm text-gray-700 hover:text-orange-900">Processed</a></li>
                        <li class="dropdown-item"><a href="{{ route('customer.tracking.receiving') }}"
                                class="text-sm text-gray-700 hover:text-orange-900">To Receive</a></li>
                        <li class="dropdown-item"><a href="{{ route('customer.tracking.delivered') }}"
                                class="text-sm text-gray-700 hover:text-orange-900">Completed</a></li>
                        <li class="dropdown-item"><a href="{{ route('customer.tracking.cancelled') }}"
                                class="text-sm text-gray-700 hover:text-orange-900">Cancelled</a></li>
                    </ul>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('customer.order-history') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-wallet text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Order History</span>
                        </a>
                    </div>
                </li>
            @endif


            @if (Auth::user()->role == 'Seller')
                <li>
                    <div class="group">
                        <a href="{{ route('seller.dashboard') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-dashboard text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"
                        class="dropdown-toggle text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all"
                        onclick="toggleDropdown(this)">
                        <i class='bx bxs-user-circle text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                        <span>Accounts</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="dropdown-item"><a href="{{ route('seller.cashier') }}"
                                class="text-sm text-black-700 hover:text-orange-900">Cashier</a></li>
                        <li class="dropdown-item"><a href="{{ route('seller.rider') }}"
                                class="text-sm text-black-700 hover:text-orange-900">Rider</a></li>
                    </ul>
                </li>
                <br>
                <!-- <li>
                    <div class="group">
                        <a href="{{ route('seller.products') }}"
                            class="text-gray-800 text-sm flex items-center group-hover:text-blue-600 transition-all">
                            <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-blue-600'></i>
                            <span>Products</span>
                        </a>
                    </div>
                </li> -->

                <li class="dropdown">
                    <a href="#"
                        class="dropdown-toggle text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all"
                        onclick="toggleDropdown(this)">
                        <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                        <span>Products</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="dropdown-item"><a href="{{ route('seller.products') }}"
                                class="text-sm text-black-700 hover:text-orange-900">Products</a></li>
                        <li class="dropdown-item"><a href="{{ route('seller.categories') }}"
                                class="text-sm text-black-700 hover:text-orange-900">Categories</a></li>
                    </ul>
                </li>


                <br>
                <!-- <li>
                    <div class="group">
                        <a href="{{ route('seller.categories') }}"
                            class="text-gray-800 text-sm flex items-center group-hover:text-blue-600 transition-all">
                            <i class='bx bxs-category text-2xl mr-4 text-gray-400 group-hover:text-blue-600'></i>
                            <span>Category Management</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('seller.locations') }}"
                            class="text-gray-800 text-sm flex items-center group-hover:text-blue-600 transition-all">
                            <i class='bx bxs-map text-2xl mr-4 text-gray-400 group-hover:text-blue-600'></i>
                            <span>Location Management</span>
                        </a>
                    </div>
                </li> -->
                <!-- <li>
                    <div class="group">
                        <a href="{{ route('seller.cashier') }}"
                            class="text-gray-800 text-sm flex items-center group-hover:text-blue-600 transition-all">
                            <i class='bx bxs-user-check text-2xl mr-4 text-gray-400 group-hover:text-blue-600'></i>
                            <span>Cashier Management</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('seller.rider') }}"
                            class="text-gray-800 text-sm flex items-center group-hover:text-blue-600 transition-all">
                            <i class='bx bxs-user-check text-2xl mr-4 text-gray-400 group-hover:text-blue-600'></i>
                            <span>Rider Management</span>
                        </a>
                    </div>
                </li> -->
                <li class="dropdown">
                    <a href="#"
                        class="dropdown-toggle text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all"
                         >
                        <i class='bx bxs-cart text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                        <span>Orders</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <!-- <li class="dropdown-item"><a href="{{ route('seller.tracking.pending') }}"
                                class="text-sm text-gray-700 hover:text-gray-900">Pending</a></li> -->
                        <li class="dropdown-item"><a href="{{ route('seller.tracking.processed') }}?filter=all"
                                class="text-sm text-black-700 hover:text-orange-900">Processing</a></li>
                        <li class="dropdown-item"><a href="{{ route('seller.tracking.receiving') }}?filter=all"
                                class="text-sm text-black-700 hover:text-orange-900">To Receive</a></li>
                        <li class="dropdown-item"><a href="{{ route('seller.tracking.delivered') }}?filter=all"
                                class="text-sm text-black-700 hover:text-orange-900">Completed</a></li>
                        <li class="dropdown-item"><a href="{{ route('seller.tracking.cancelled') }}?filter=all"
                                class="text-sm text-black-700 hover:text-orange-900">Cancelled</a></li>
                    </ul>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('seller.order-history') }}?filter=all"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-wallet text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Order History</span>
                        </a>
                    </div>
                </li>
                
                <li>
                    <div class="group">
                        <a href="{{ route('seller.inventory') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Inventory</span>
                        </a>
                    </div>
                </li>

                <li>
                    <div class="group">
                        <a href="{{ route('seller.reports') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-file-pdf text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Reports</span>
                        </a>
                    </div>
                </li>
            @endif


            @if (Auth::user()->role == 'GovernmentAgency')
                <li>
                    <div class="group">
                        <a href="{{ route('government.approval') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-user-check text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Accounts</span>
                        </a>
                    </div>
                </li>
            @endif


            @if (Auth::user()->role == 'Cashier')
                <li>
                    <div class="group">
                        <a href="{{ route('cashier.dashboard') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-dashboard text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('cashier.orders') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-cart text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Orders</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('cashier.reports') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-file-pdf text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Reports</span>
                        </a>
                    </div>
                </li>
            @endif


            @if (Auth::user()->role == 'DeliveryRider')
                <li>
                    <div class="group">
                        <a href="{{ route('rider.dashboard') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-dashboard text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <a href="{{ route('rider.orders') }}"
                            class="text-black-800 text-sm flex items-center group-hover:text-orange-900 transition-all">
                            <i class='bx bxs-package text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Orders</span>
                        </a>
                    </div>
                </li>
            @endif

            <hr class="my-6" />

            <li>
                <div class="group">
                    <a href="{{ route('profile.edit') }}"
                        class="text-black-800 text-sm flex items-center hover:text-orange-900 transition-all">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('avatar/' . Auth::user()->avatar) }}" alt="avatar"
                                class="w-7 h-7 rounded-full mr-4">
                        @else
                            <i class='bx bxs-user-circle text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                        @endif
                        <span>Profile</span>
                    </a>
                </div>
            </li>
            <li>
                <div class="group">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-black-800 text-sm flex items-center hover:text-orange-900 transition-all">
                            <i class='bx bxs-log-out text-2xl mr-4 text-gray-400 group-hover:text-orange-900'></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').click(function(e) {
            e.preventDefault();
            $(this).next('.dropdown-menu').slideToggle();
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').slideUp();
            }
        });

        let sidebarOpen = false;

        $('.bx-chevron-right').click(function() {
            sidebarOpen = !sidebarOpen;

            if (sidebarOpen) {
                $('nav').removeClass('w-16').addClass('min-w-[250px]');
                $('.overflow-auto').removeClass('hidden');
                $(this).removeClass('bx-chevron-right').addClass('bx-chevron-left');
                $('.relative > a').removeClass('hidden');
                $(this).css('left', 'w-[250px]');
                $('nav').css('visibility', 'visible');
                $('.bx-chevron-right').css('visibility', 'hidden');
            } else {
                $('nav').removeClass('min-w-[250px]').addClass('w-16');
                $('.overflow-auto').addClass('hidden');
                $(this).removeClass('bx-chevron-left').addClass('bx-chevron-right');
                $('.relative > a').addClass('hidden');
                $(this).css('left', 'w-[250px]');
                $('nav').css('visibility', 'hidden');
                $('.bx-chevron-right').css('visibility', 'visible');
            }
        });
    });
</script>
