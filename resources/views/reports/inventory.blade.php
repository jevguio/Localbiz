<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
</head>

<body>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            width: 20%;
            margin-left: 'auto';
            margin-right: 'auto';
            font-weight: bold;
            margin-bottom: 20px;
        }

        #subheader {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            background-color: 'rgb(192, 147, 0)';
            width: 100%;
            height: auto;
        }

        .hidden_table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .hidden_table th,
        .table td {
            padding: 8px;
            text-align: left;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-family: Arial, sans-serif;
        }

        .table th {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
    </style>
    <div class="relative mb-10 w-full">
        @php
            $imagePath = public_path('assets/img/logo.jpg');
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageSrc = 'data:image/jpeg;base64,' . $imageData;

        @endphp
        <div style="text-align: center;">
            <img class="header mx-auto" src="{{ $imageSrc }}" style="display: block; margin: 0 auto; margin-bottom: 20px;" />
        </div> 
        <script>
            function CloseInventoryThis() {

                const Product_Management = document.getElementById('Product_Management');
                Product_Management.style.display = "none";
            }
        </script>
        <div class="subheader" id="subheader"
            style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">INVENTORY REPORT</div>

        @if ($is_view)
            <button onclick="CloseInventoryThis()" class="absolute top-2 right-5 ">
                <i class="bx bx-x text-gray-500 text-2xl"></i>
            </button>
        @endif
        <p>Seller: {{ $selectedSeller ? $selectedSeller->fname : 'NULL' }}</p>
        <p id="DateStartEnd">Generated Date/s: {{ isset($startDate) ? $startDate : '' }} -
            {{ isset($endDate) ? $endDate : '' }}</p>
        <p id="DateStartEnd1">Date: {{ now()->format('F j, Y') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Remaining Stock</th>
                    <th>Number of Sold</th>
                    <th>Unit Price</th>
                    <th>Product Name</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $QuantityPrice = 0; @endphp
                @foreach ($items as $index => $item)
                    @php $QuantityPrice = $item->price * $item->sold; @endphp
 
                        <tr>
                            @php
                                $units = ['pcs', 'packs', 'sets']; 

                                $totalquantity = 0;
                                foreach ($item->orderItems as $order) {
                                    $totalquantity += $order->quantity;
                                }
                            @endphp

                            <td>{{ $item->stock }}</td>
                            <td> {{ $totalquantity }}</td>
                            <td>{{ $item->price ?? 'No Data' }}</td>
                            <td>{{ $item->name }}</td>
                            <td>&#8369;{{ $QuantityPrice }}</td>
                        </tr> 
                @endforeach

            </tbody>
        </table>
    </div>


</body>

</html>
