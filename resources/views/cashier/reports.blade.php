<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Reports Management</h2>
            </div>

            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">
                <form class=" ml-0 mb-4  w-full">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>

                    <div class="flex justify-between items-center w-full">
                        <div class="relative  w-120">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class='bx bx-search text-gray-500 text-2xl'></i>
                            </div>
                            <input type="search" id="table-search"
                                class="block w-full p-4 ps-10 text-sm  text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search for reports....">
                        </div>
                        <div class="flex gap-2 my-4">
                            <button type="button"
                                class="btn bg-red-900 text-base hover:bg-red-800 text-white px-4 py-2 rounded-md"
                                data-modal-target="datePickerModal">
                                Generate Payment Transactions
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Flatpickr CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                <!-- Flatpickr JS -->
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <!-- Date Picker Modal -->
                <div id="datePickerModal"
                    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <style>
                                .flatpickr-disabled {
                                    color:rgba(0,0,0,0.2) !important;
                                }
                            </style>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Date Range</h3>
                            <form action="{{ route('cashier.sales.export') }}" method="GET">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Date
                                        Range</label>

                                    <!-- Visible Date Range Picker -->
                                    <input type="text" id="dateRange"
                                        class="flatpickr-input block w-full p-2.5 mb-2 border border-gray-300 rounded-lg"
                                        placeholder="Select date range" readonly>

                                    <!-- Hidden Inputs to Hold Confirmed Dates -->
                                    <input type="hidden" name="from_date" id="from_date">
                                    <input type="hidden" name="to_date" id="to_date">

                                </div>
                                <script>
                                    let selectedDates = [];
                                    let fp;

                                    document.addEventListener('DOMContentLoaded', function() {
                                        fp = flatpickr("#dateRange", {
                                            mode: "range",
                                            dateFormat: "Y-m-d",
                                            closeOnSelect: false,
                                            showMonths: 1, // You can increase to 2 if you're displaying two months
                                            maxDate: 'today',
                                            disableMobile: true, // Ensures desktop calendar on mobile
                                            onChange: function(dates) {
                                                selectedDates = dates;
                                            },
                                            onReady: function(selectedDates, dateStr, instance) {
                                                // Create confirm/cancel buttons
                                                const confirmBtn = document.createElement("button");
                                                confirmBtn.type = "button";
                                                confirmBtn.innerText = "Confirm";
                                                confirmBtn.className =
                                                    "mt-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm";

                                                const cancelBtn = document.createElement("button");
                                                cancelBtn.type = "button";
                                                cancelBtn.innerText = "Cancel";
                                                cancelBtn.className =
                                                    "mt-2 ml-2 bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm";

                                                // Button container
                                                const btnContainer = document.createElement("div");
                                                btnContainer.className = "flatpickr-confirm-buttons flex justify-center mt-2";
                                                btnContainer.appendChild(confirmBtn);
                                                btnContainer.appendChild(cancelBtn);

                                                // Append buttons to Flatpickr calendar
                                                instance.calendarContainer.appendChild(btnContainer);

                                                // Confirm logic
                                                confirmBtn.addEventListener("click", function() {
                                                    document.getElementById('from_date').value = instance.formatDate(
                                                        selectedDates[0], "Y-m-d");
                                                    document.getElementById('to_date').value = instance.formatDate(
                                                        selectedDates[1], "Y-m-d");
                                                    instance.close();

                                                });

                                                // Cancel logic
                                                cancelBtn.addEventListener("click", function() {
                                                    instance.clear();
                                                    document.getElementById('from_date').value = '';
                                                    document.getElementById('to_date').value = '';
                                                });
                                            }
                                        });
                                    });
                                </script>




                                <div class="flex justify-end gap-2">
                                    <button type="submit"
                                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                                        Download as PDF
                                    </button>
                                    <button type="button"
                                        class="btn bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md"
                                        data-modal-toggle="datePickerModal">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Report Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Report Type
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created At
                            </th>
                            <!-- <th scope="col" class="px-6 py-3">
                                Content
                            </th> -->
                        </tr>
                    </thead>
                    <tbody id="reports-table-body">
                        @foreach ($reports as $report)
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $report->report_name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $report->report_type }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->created_at->format('F d, Y h:i:s A') }}
                                </td>
                                <!-- <td class="px-6 py-4">
                                    <a href="{{ asset('reports/' . $report->content) }}"
                                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded"
                                        download>Download</a>
                                </td> -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4"
                    aria-label="Table navigation">
                    <span
                        class="text-sm font-normal text-gray-500 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
                        <span
                            class="font-semibold text-gray-900">{{ $reports->firstItem() }}-{{ $reports->lastItem() }}</span>
                        of <span class="font-semibold text-gray-900">{{ $reports->total() }}</span></span>
                    <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                        {{ $reports->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Existing search functionality
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#reports-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });

            // Modal functionality
            $('[data-modal-target]').on('click', function() {
                const modalId = $(this).data('modal-target');
                $(`#${modalId}`).removeClass('hidden');
            });

            $('[data-modal-toggle]').on('click', function() {
                const modalId = $(this).data('modal-toggle');
                $(`#${modalId}`).addClass('hidden');
            });

            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            $('input[name="to_date"]').val(today);

            // Set default from_date to 30 days ago
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            $('input[name="from_date"]').val(thirtyDaysAgo.toISOString().split('T')[0]);
        });
    </script>
</x-app-layout>
