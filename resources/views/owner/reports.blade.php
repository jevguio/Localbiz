<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <div class="p-4 sm:ml-64"  >
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
                            <th scope="col" class="px-6 py-3">
                                Content
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
                                <td class="px-6 py-4">
                                    <a href="{{ asset('reports/' . $report->content) }}"
                                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded"
                                        download>Download</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex gap-2 my-4">
                    <a href="#" onclick="openSeller()"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">Generate
                        Inventory</a>
                    <a href="{{ route('owner.products.export') }}"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                        Generate Products
                    </a>
                    <a href="{{ route('owner.sales.export') }}"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                        Generate Sales
                    </a>
                </div>
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

<div class="p-4 " id="Seller_Management" style="display:none; position:fixed; top:0;left:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%" onclick="CloseSeller(event)">
        <div class="p-4   rounded-lg" style="width:60%; margin-left:auto;margin-right:auto; background-color:white;height:80%" onclick="">
             
             
        <div class="header">Seller Management</div> 

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>seller Name</th>
                    <th>Address</th> 
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($sellers as $index => $seller)
                    <tr>
                    <td>{{ $seller->id }}</td> 
                        <td>{{ $seller->name }}</td>
                        <td>{{ $seller->address  }}</td> 
                        <td>
                        <a id="generateReportBtn"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md">
                        Generate Report
                        </a>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let generateBtn = document.getElementById("generateReportBtn");
                                let datePicker = dateRangeflatpickr; // Ensure this is correctly defined in your script

                                generateBtn.addEventListener("click", function (event) {
                                    event.preventDefault(); // Prevent default link behavior

                                    let baseUrl = "{{ route('owner.reports') }}"; // Blade generates this correctly
                                    let sellerId = "{{ $seller->id }}"; // Get seller ID from Blade
                                    let dateStart = datePicker.valueStart || ''; // Get start date from flatpickr
                                    let dateEnd = datePicker.valueEnd || ''; // Get end date from flatpickr

                                    let url = `${baseUrl}?id=${sellerId}&dateStart=${dateStart}&dateEnd=${dateEnd}`;
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
        
                <a href="{{ route('owner.inventory.export') }}"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md" style="position:absolute;right:21%; bottom:23%">Download PDF</a>
            
                 
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
        const Seller = document.getElementById('Seller_Management');
         
         const datePicker = document.createElement("input"); 
         datePicker.setAttribute('type','text'); 

         let grid = document.getElementById("grid");
         if(grid){

         }else{

            grid = document.createElement("div"); 
            grid.setAttribute('id','grid'); 
         }
         
  
        function openInventory(event){ 
            
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
            }
        }
        function openSeller(event){ 
            const Seller = document.getElementById('Seller_Management');
                Seller.style.display="block"; 
        }
        function CloseSeller(event){
            if (event.target === event.currentTarget) {
                const Seller = document.getElementById('Seller_Management');
                Seller.style.display="none";
            }
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
        if (id!=null) { 
            openSeller(null);
            openInventory(null);
        } else {
            const invent = document.getElementById("Product_Management");
            invent.style.display="none"; 
        }
        </script> 

</x-app-layout>
