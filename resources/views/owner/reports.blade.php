<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <div class="p-4 sm:ml-64"  >
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-3xl">Reports Management</h2>
            </div>
            <div class="relative overflow-x-auto mt-10 bg-white p-4 rounded-lg">
                <form class="  ml-0 mb-4 w-full">
                    <label for="table-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                        <div class="relative flex">
                            <div class="relative w-100">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <i class='bx bx-search text-gray-500 text-2xl'></i>
                                </div>
                                <input type="search" id="table-search"
                                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search for reports....">
                                    
                                <button type="button" id="filter-btn"
                                    class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-600 hover:text-gray-900">
                                    <i class='bx bx-filter text-2xl'></i>
                                </button>

                                <div id="filter-dropdown"
                                    class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                                    <ul class="py-2 text-sm text-gray-700">
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="All">All</li>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="Sales Report">Sales Report</li>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="Inventory Report">Inventory Report</li>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="Top Seller Report">Top Seller Report</li>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer filter-option" data-filter="Top Purchase Report">Top Purchase Report</li> 
                                    </ul>
                                </div>
                            </div>
                            <div class=" flex gap-2 my-2 mx-2">
                            <a href="#" onclick="openInventorySeller()"
                                class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">Generate
                                Inventory</a>
                                
                            <a href="#" onclick="openSalesSeller()"
                                class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                                Generate Sales
                            </a>
                            <a  href="{{route('owner.topseller')}}" 
                                class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                                Top Seller
                            </a>
                            
                            <a href="#" onclick="openTopProducts()"
                                class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                                Top Purchased Products
                            </a>
                        </div>
                   </div>
                </form>
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
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50"  data-category="{{ $report->report_name }}">
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

  
    <div class="p-4 " id="Seller_Inventory_Management" style="display:none; position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseInventorySeller(event)">
        <div class="p-4  relative rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%" onclick="">
             
             
        <div class="header">List of Sellers</div> 
        <button  onclick="CloseInventorySeller()" 
        class="absolute top-5 right-5 "
        >
            <i class="bx bx-x text-gray-500 text-2xl"></i>
        </button>
        <table class="hidden_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Seller Business Name</th>
                    <th>Address</th> 
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody >
                @foreach ($sellers as $index => $seller)
                    <tr>
                    <td>{{ $seller->id }}</td> 
                        <td>{{ $seller->fname}}</td>
                        <td>{{ $seller->address  }}</td> 
                        <td>
                        <a id="generateReportBtn{{ $seller->id }}"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-2 py-1 m-1 rounded-md">
                        Generate Report
                        </a>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let generateBtn = document.getElementById("generateReportBtn{{ $seller->id }}");
                                let datePicker = dateRangeflatpickr; // Ensure this is correctly defined in your script
                                console.log(datePicker);
                                generateBtn.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let baseUrl = "{{ route('owner.reports') }}"; // Blade generates this correctly
                                    let sellerId = "{{ $seller->id }}"; // Get seller ID from Blade 

                                    let url = `${baseUrl}?id=${sellerId}&inventory=true`;
                                    window.location.href = url; // Redirect dynamically
                                });
                            });
                        </script>
                    </td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
                 
        </div>
    </div>
    
<div class="p-4 " id="Seller_Sales_Management" style="display:none; position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseSalesSeller(event)">
        <div class="p-4  relative rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%" onclick="">
             
             
        <div class="header">List of Sellers</div> 

        @if($is_view)
<button  onclick="CloseSalesSeller()" 
        class="absolute top-5 right-5 "
        >
            <i class="bx bx-x text-gray-500 text-2xl"></i>
        </button>
@endif
        <table class="hidden_table">
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
                        <td>{{ $seller->fname }}</td>
                        <td>{{ $seller->address  }}</td> 
                        <td>
                        <a id="generateSalesReportBtn{{ $seller->id }}"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-2 py-1 m-1 rounded-md">
                        Generate Report
                        </a>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let generateBtn = document.getElementById("generateSalesReportBtn{{ $seller->id }}");
                                let datePicker = dateRangeflatpickr; // Ensure this is correctly defined in your script
                                console.log(datePicker);
                                generateBtn.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let baseUrl = "{{ route('owner.reports') }}"; // Blade generates this correctly
                                    let sellerId = "{{ $seller->id }}"; // Get seller ID from Blade 

                                    let url = `${baseUrl}?id=${sellerId}&sales=true`;
                                    window.location.href = url; // Redirect dynamically
                                });
                            });
                        </script>
                    </td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
                 
        </div>
    </div>
    <div class="overlay" id="overlay">
    @include('reports.daterangepicker')
        
    </div>
    <div class="p-4 " id="Product_Management" style="position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseInventory(event)">
        <div class="p-4   rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%">
           
                @include('reports.inventory')
        
                <a  id="export_inventory"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md" style="position:absolute;right:21%; bottom:23%">Download PDF</a>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let export_inventory = document.getElementById("export_inventory"); 

                                export_inventory.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let DateStartEnd = document.getElementById("DateStartEnd"); 
                                    let baseUrl = "{{ route('owner.inventory.export') }}"; // Blade generates this correctly
                                    const id = urlParams.get("id");  
                                    let start=DateStartEnd.getAttribute('start');
                                    let end=DateStartEnd.getAttribute('end');
                                    let url = `${baseUrl}?id=${id}&sales=true&start_date=${start}&end_date=${end}`;
                                    window.location.href = url; // Redirect dynamically
                                });
                            });
                        </script>
                 
        </div>
    </div>
     


    
    <div class="p-4 " id="Sales_Management" style="position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseSales(event)">
        <div class="p-4   rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%">
           
                @include('reports.sales')
        
                <a href="{{ route('owner.inventory.export') }}" id="export_sales"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md" style="position:absolute;right:21%; bottom:23%">Download PDF</a>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let export_sales = document.getElementById("export_sales"); 

                                export_sales.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let DateStartEnd = document.getElementById("DateStartEnd"); 
                                    let baseUrl = "{{ route('owner.sales.export') }}"; // Blade generates this correctly
                                    
                                    const id = urlParams.get("id");  
                                    let start=DateStartEnd.getAttribute('start');
                                    let end=DateStartEnd.getAttribute('end');
                                    let url = `${baseUrl}?id=${id}&sales=true&start_date=${start}&end_date=${end}`;
                                    window.location.href = url; // Redirect dynamically
                                });
                            });
                        </script>
                 
        </div>
    </div>
    <style> .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000; 
            opacity: 0;
            transition: opacity 0.3s ease;
        }
 
</style> 
    <script> 
    function openTopProducts(){
        let baseUrl = "{{ route('owner.toppurchase') }}"; // Blade generates this correctly
        let sellerId = "{{ $seller->id }}"; // Get seller ID from Blade 
        let url = `${baseUrl}?id=${sellerId}&inventory=true`;
        window.location.href = url; // Redirect dynamically
    }
        const Seller = document.getElementById('Seller_Inventory_Management');
         
         const datePicker = document.createElement("input"); 
         datePicker.setAttribute('type','text'); 

         let grid = document.getElementById("grid");
         if(grid){

         }else{

            grid = document.createElement("div"); 
            grid.setAttribute('id','grid'); 
         }
         

 

        //   -------------------------------Inventory--------------------------------------
        //   -------------------------------Inventory--------------------------------------
        //   -------------------------------Inventory--------------------------------------
        //   -------------------------------Inventory--------------------------------------
        

        let selectedFilter = "All"; // Default filter

        // Toggle filter dropdown
        $("#filter-btn").click(function (event) {
            event.preventDefault();
            $("#filter-dropdown").toggleClass("hidden");
        });

        // Search and filter function
        function filterTable() {
            const searchInput = $("#table-search").val().toLowerCase();

            $("#reports-table-body tr").each(function () {
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


         function openInventoryReport(event){ 
            
            const overlay = document.getElementById("overlay");  
            const invent = document.getElementById('Product_Management');
            invent.style.display="none";
                overlay.style.display= "block"; 
                overlay.style.visibility= "visible";   
                overlay.style.opacity= 1;   
                dateRangeflatpickr.open(); 
        }
        function CloseInventory(event){
            const invent = document.getElementById('Product_Management');
            if (event.target === event.currentTarget) {
                invent.style.display="none";
                window.location.href ="#";
            }
        }
        function openInventorySeller(event){ 
            const Seller = document.getElementById('Seller_Inventory_Management');
                Seller.style.display="block"; 
        }
        function CloseInventorySeller(event){
            event.stopPropagation(); 
            if (event.target === event.currentTarget) {
                const Seller = document.getElementById('Seller_Inventory_Management');
                Seller.style.display="none";
            }
        }
        
        function CloseInventorySeller(){
                const Seller = document.getElementById('Seller_Inventory_Management');
                Seller.style.display="none";
        }
        
        //   -------------------------------Sales--------------------------------------
        //   -------------------------------Sales--------------------------------------
        //   -------------------------------Sales--------------------------------------
        //   -------------------------------Sales--------------------------------------
        
        function openSalesReport(event){ 
            
            const overlay = document.getElementById("overlay");  
            const invent = document.getElementById('Sales_Management');
            invent.style.display="none";
                overlay.style.display= "block"; 
                overlay.style.visibility= "visible";   
                overlay.style.opacity= 1;   
                dateRangeflatpickr.open(); 
        }
        function CloseSales(event){
            const invent = document.getElementById('Sales_Management');
            if (event.target === event.currentTarget) {
                invent.style.display="none";
            }
        } 
        function openSalesSeller(event){ 
            const Seller = document.getElementById('Seller_Sales_Management');
                Seller.style.display="block"; 
        }
        function CloseSalesSeller(event){
            if (event.target === event.currentTarget) {
                const Seller = document.getElementById('Seller_Sales_Management');
                Seller.style.display="none";
            }
        }
        function CloseSalesSeller(){
                const Seller = document.getElementById('Seller_Sales_Management');
                Seller.style.display="none";
        }
        $(document).ready(function() {
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#reports-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });
        });
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get("id"); 
        const sales = urlParams.get("sales"); 
        const inventory = urlParams.get("inventory");  
        if(sales && id!=null){

            openSalesSeller(null);
            openSalesReport(null);
        }else {
            
            const Product_Management = document.getElementById("Product_Management");
            Product_Management.style.display="none"; 
            const Sales_Management = document.getElementById("Sales_Management");
            Sales_Management.style.display="none"; 
        }
        
        if(inventory && id!=null){

            openInventorySeller(null);
            openInventoryReport(null);
        } else {
            const Product_Management = document.getElementById("Product_Management");
            Product_Management.style.display="none"; 
            const Sales_Management = document.getElementById("Sales_Management");
            Sales_Management.style.display="none"; 
        }
        </script> 

</x-app-layout>
