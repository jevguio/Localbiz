<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Reports Management</h2>
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
                            placeholder="Search for reports....">
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
                
                <a href="#" onclick="openInventory(event)"
                    class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md my-4">Generate
                    Inventory</a>
                <a href="{{ route('seller.products.export') }}"
                    class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                    Generate Products
                </a>
                <a  href="#" onclick="openSales(event)"
                    class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                    Generate Sales
                </a>
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
    <div class="p-4 " id="Sales_Management" style="position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseSales(event)">
        <div class="p-4   rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%">
           
                @include('reports.sales')
        
                <a href="{{ route('seller.inventory.export') }}" id="export_sales"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md" style="position:absolute;right:21%; bottom:23%">Download PDF</a>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let export_sales = document.getElementById("export_sales"); 

                                export_sales.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let DateStartEnd = document.getElementById("DateStartEnd"); 
                                    let baseUrl = "{{ route('seller.sales.export') }}"; // Blade generates this correctly
                                    let sellerId = "{{ $selectedSeller->id }}"; // Get seller ID from Blade 
                                    let start=DateStartEnd.getAttribute('start');
                                    let end=DateStartEnd.getAttribute('end');
                                    let url = `${baseUrl}?id=${sellerId}&sales=true&start_date=${start}&end_date=${end}`;
                                    window.location.href = url; // Redirect dynamically
                                });
                            });
                        </script>
                 
        </div>
    </div>
    
    <div class="overlay" id="overlay">
    @include('reports.daterangepicker')
        
    </div>
    <div class="p-4 " id="Product_Management" style="position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseInventory(event)">
        <div class="p-4   rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%">
           
                @include('reports.inventory')
        
                <a href="{{ route('seller.inventory.export') }}" id="export_inventory"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md" style="position:absolute;right:21%; bottom:23%">Download PDF</a>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let export_inventory = document.getElementById("export_inventory"); 

                                export_inventory.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let DateStartEnd = document.getElementById("DateStartEnd"); 
                                    let baseUrl = "{{ route('seller.inventory.export') }}"; // Blade generates this correctly
                                    let sellerId = "{{ $selectedSeller->id }}"; // Get seller ID from Blade 
                                    let start=DateStartEnd.getAttribute('start');
                                    let end=DateStartEnd.getAttribute('end');
                                    let url = `${baseUrl}?id=${sellerId}&sales=true&start_date=${start}&end_date=${end}`;
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
 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-search').on('keyup', function() {
                const searchInput = $(this).val().toLowerCase();
                $('#reports-table-body tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1);
                });
            });
        });
        
        function openSalesReport(event){  
            let datePicker = dateRangeflatpickr; // Ensure this is correctly defined in your script
            // console.log(datePicker);  
            const overlay = document.getElementById("overlay");  
            const invent = document.getElementById('Sales_Management');
            invent.style.display="none";
                overlay.style.display= "block"; 
                overlay.style.visibility= "visible";   
                overlay.style.opacity= 1;   
                dateRangeflatpickr.open(); 
        } 
        function openInventory(event){ 
            let baseUrl = "{{ route('seller.reports') }}"; // Blade generates this correctly
            let sellerId = "{{ $seller->id }}"; // Get seller ID from Blade 
            let url = `${baseUrl}?id=${sellerId}&inventory=true`;
            window.location.href = url; // Redirect dynamically
        }
        
        function openSales(event){ 
            let baseUrl = "{{ route('seller.reports') }}"; // Blade generates this correctly
            let sellerId = "{{ $seller->id }}"; // Get seller ID from Blade 
            let url = `${baseUrl}?id=${sellerId}&sales=true`;
            window.location.href = url; // Redirect dynamically
        }
        
        function openInventoryReport(event){  

                                    
                                    // window.location.href = url; // Redirect dynamically
            const overlay = document.getElementById("overlay");  
            const invent = document.getElementById('Product_Management');
            invent.style.display="none";
                overlay.style.display= "block"; 
                overlay.style.visibility= "visible";   
                overlay.style.opacity= 1;   
                dateRangeflatpickr.open(); 
        }
        function CloseInventory(){
            if (event.target === event.currentTarget) {
                const Product_Management =document.getElementById('Product_Management');
                Product_Management.style.display="none";
            }
        }
        function CloseSales(){
            if (event.target === event.currentTarget) {
                const Product_Management =document.getElementById('Sales_Management');
                Product_Management.style.display="none";
            }
        }
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get("id"); 
        const sales = urlParams.get("sales"); 
        const inventory = urlParams.get("inventory");  
        if(sales && id!=null){
 
            openSalesReport(null);
        }else {
            
            const Product_Management = document.getElementById("Product_Management");
            Product_Management.style.display="none"; 
            const Sales_Management = document.getElementById("Sales_Management");
            Sales_Management.style.display="none"; 
        }
        
        if(inventory && id!=null){
 
            openInventoryReport(null);
        } else {
            const Product_Management = document.getElementById("Product_Management");
            Product_Management.style.display="none"; 
            const Sales_Management = document.getElementById("Sales_Management");
            Sales_Management.style.display="none"; 
        }
    </script>
    
</x-app-layout>
