<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Inventory Management</h2>
                <a href="{{ route('seller.reports.export') }}"
                    class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">Generate
                    Report</a>
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
                            placeholder="Search for orders....">
                    </div>
                </form>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Customer Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Amount
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody id="order-table-body">
                        @foreach ($orders as $order)
                            @foreach ($order->orderItems as $orderItem)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $order->user->name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $orderItem->product->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $order->total_amount }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $order->status }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    <span class="text-lg font-bold text-gray-900">Total Sales:
                        ₱ {{ number_format($totalSales, 2) }}</span>
                </div>
                <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4"
                    aria-label="Table navigation">
                    <span
                        class="text-sm font-normal text-gray-500 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
                        <span
                            class="font-semibold text-gray-900">{{ $orders->firstItem() }}-{{ $orders->lastItem() }}</span>
                        of <span class="font-semibold text-gray-900">{{ $orders->total() }}</span></span>
                    <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                        {{ $orders->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#order-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });
        });
    </script>
</x-app-layout>
