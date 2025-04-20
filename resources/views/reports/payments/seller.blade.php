<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <div class="p-4 w-full">
        <div class="p-4 @if(Auth::user()->role=='Seller')hidden @endif" id="Seller_Inventory_Management"
            style=" position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%"
            onclick="javascript:history.back()">
            <div class="p-4  relative rounded-lg" onclick="event.stopPropagation()"
                style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%" onclick="">


                <div class="header">List of Sellers</div>
                <a href="javascript:history.back()" class="absolute top-5 right-5">
                    <i class="bx bx-x text-gray-500 text-2xl"></i>
                </a>
                <table class="hidden_table w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Seller Business Name</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $index => $seller)
                            <tr>
                                <td>{{ $seller->id }}</td>
                                <td>{{ $seller->fname}}</td>
                                <td>{{ $seller->address  }}</td>
                                <td>
                                    <button id="generateReportBtn{{ $seller->id }}"
                                        onclick="generateReportBtn({{ $seller->id }})"
                                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-2 py-1 m-1 rounded-md">
                                        Generate Report
                                    </button>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            let generateBtn = document.getElementById("generateReportBtn{{ $seller->id }}");
                                            // Ensure this is correctly defined in your script

                                        }); 
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @if(Auth::user()->role=='Seller') 
            <script>
                        
                        document.addEventListener("DOMContentLoaded", function () {
                        let datePicker = dateRangeflatpickr;
                                            let overlay = document.getElementById("overlay");
                                        if (datePicker) {

                                            overlay.style.display = 'block';
                    overlay.style.opacity = 1;
                                            datePicker.open();
                                        }
                                        }); 
                    </script>
        @endif 
        <span id="slected_id" selected_id="{{ Auth::user()->id }}"></span>
        <style>
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                /* Semi-transparent black */
                display: @if(Auth::user()->role=='Seller') none @else none @endif ; 
                justify-content: center;
                align-items: center;
                z-index: 1000;
                opacity: @if(Auth::user()->role=='Seller') 0 @else 1 @endif ;
                transition: opacity 0.3s ease;
            }
        </style>
        <div class="overlay" id="overlay">
            <div id="DateStartEnd"></div>
            <div style="width:100%" id="date-range">
            </div>
            <style>
                .flatpickr-day.nextMonthDay,
                .flatpickr-day.prevMonthDay {
                    opacity: 1;
                    /* Dim past/future month days */
                    color: rgba(57, 57, 57, 0.7) !important;
                    /* Make disabled future dates look faded */

                }

                .flatpickr-day.flatpickr-disabled {
                    color: rgba(57, 57, 57, 0.4) !important;
                    /* Make disabled future dates look faded */
                }
            </style>
            <script>
                let startDate, endDate;
                const dateRange = document.getElementById('date-range');
                const dateRangePicker = document.createElement('input');
                dateRangePicker.setAttribute('type', 'text');
                dateRangePicker.setAttribute('id', 'dateRangePicker');
                dateRange.appendChild(dateRangePicker);
                const dateRangeflatpickr = flatpickr(dateRangePicker, {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    maxDate: "today",
                    clickOpens: false,
                    onOpen: function (selectedDates, dateStr, instance) {
                        const overlay = document.getElementById('overlay');
                        overlay.style.display = "block";

                    },
                    onChange: function (selectedDates, dateStr, instance) {
                        if (selectedDates.length === 2) {
                            startDate = selectedDates[0];
                            endDate = selectedDates[1];
                            buttonContainer.style.display = "block";
                            const DateStartEnd = document.getElementById('DateStartEnd');
                            const SalesDateStartEnd = document.getElementById('SalesDateStartEnd');
                            const startFormatted = startDate.toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            const endFormatted = endDate.toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            DateStartEnd.setAttribute('start', startFormatted);
                            DateStartEnd.setAttribute('end', endFormatted);
                            const overlay = document.getElementById('overlay');
                            overlay.style.display = "block";
                            overlay.style.opacity = 1;
                            dateRangeflatpickr.open();
                        }
                    },
                    onClose: function (selectedDates, dateStr, instance) {


                        const overlay = document.getElementById('overlay');
                        overlay.style.display = "none";
                        overlay.style.opacity = 0;
                        buttonContainer.style.display = "none";
                    },
                });

                const buttonContainer = document.createElement("div");
                buttonContainer.style.display = "none";
                const confirmButton = document.createElement("button");
                confirmButton.id = "confirm-picker";
                confirmButton.textContent = "Confirm";
                confirmButton.addEventListener("click", function () {
                    let baseUrl = "{{ route('report.payment') }}"; // Blade generates this correctly

                    let slected_id = document.getElementById("slected_id");
                    let start = DateStartEnd.getAttribute('start');
                    let end = DateStartEnd.getAttribute('end');
                    let url = `${baseUrl}?seller_id=${slected_id.getAttribute('selected_id')}&payment=true&start_date=${start}&end_date=${end}`;
                    window.location.href = url; // Redirect dynamically 
                    const overlay = document.getElementById('overlay');
                    overlay.style.display = "none";
                    dateRangeflatpickr.clear();
                    dateRangeflatpickr.close();

                });

                document.getElementById("overlay").addEventListener("click", function (event) {
                    event.stopPropagation();
                });
                const cancelButton = document.createElement("button");
                cancelButton.id = "cancel-picker";
                cancelButton.textContent = "Cancel";
                cancelButton.classList.add("cancel-button");
                cancelButton.addEventListener("click", function () {
                    dateRangeflatpickr.clear();
                    dateRangeflatpickr.close();
                    document.getElementById("date-range").value = "";
                    const overlay = document.getElementById('overlay');
                    overlay.style.display = "none";
                    overlay.style.opacity = 0;
                });
                const collection = document.getElementsByClassName("flatpickr-calendar");
                buttonContainer.classList.add("button-group");
                buttonContainer.appendChild(confirmButton);
                buttonContainer.appendChild(cancelButton);
                collection[0].appendChild(buttonContainer);

                function generateReportBtn(id) {
                    let slected_id = document.getElementById("slected_id");
                    if (slected_id.hasAttribute('selected_id')) { 
                        slected_id.removeAttribute('selected_id');
                    }
                    slected_id.setAttribute('selected_id', id);
                    overlay.style.display = 'block';
                    overlay.style.opacity = 1;
                    let datePicker = dateRangeflatpickr;
                    if (datePicker) {

                        datePicker.open();
                    }
                }
            </script>
        </div>
</x-app-layout>