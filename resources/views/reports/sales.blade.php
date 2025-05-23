<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALES REPORT</title>
</head>

<body>
    <style>
        body {
            font-family: Arial, sans-serif, 'DejaVu Sans', sans-serif;
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
            font-family: Arial, sans-serif, 'DejaVu Sans', sans-serif;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>

    <div class="relative mb-10">
        @php
            $imagePath = public_path('assets/img/logo.jpg');
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageSrc = 'data:image/jpeg;base64,' . $imageData;

        @endphp
        <div style="text-align: center;">
            <img class="header mx-auto" src="{{ $imageSrc }}" style="display: block; margin: 0 auto; margin-bottom: 20px;" />
        </div>

        <script>
            function CloseSalesThis() {

                const invent = document.getElementById('Sales_Management');
                invent.style.display = "none";
            }
        </script>
        <div class="subheader" id="subheader"
            style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">SALES REPORT</div>

        @if ($is_view)
            <button onclick="CloseSalesThis()" class="absolute top-5 right-5 ">
                <i class="bx bx-x text-gray-500 text-2xl"></i>
            </button>
        @endif

        <p>Seller: {{ $selectedSeller ? $selectedSeller->fname : 'NULL' }}</p>
        <p id="SalesDateStartEnd">Generated Date/s: {{ isset($startDate) ? $startDate : '' }} -
            {{ isset($endDate) ? $endDate : '' }}</p>
        <p id="SalesDateStartEnd1">Date: {{ now()->format('F j, Y') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Quantity Sold</th>
                    <th>Unit</th>
                    <th>Product Description</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $units = ['pcs', 'packs', 'sets'];
                    $unit = ['pc', 'pack', 'set'];
                    $randomUnit = $units[array_rand($units)];
                    $randomUnits = $units[array_rand($units)];
                @endphp
                @php $totalPrice = 0; @endphp
                @php $QuantityPrice = 0; @endphp
                @foreach ($items as $index => $item)
                    @php $QuantityPrice = $item->price * $item->sold; @endphp
 
                        <tr>
                            <td>{{ $item->sold }}</td>
                            <td> {{ $item->sold > 1 ? $randomUnits : $randomUnit }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price }}</td>
                            <td>&#8369;{{ $QuantityPrice }}</td>
                        </tr> 
                    @php $totalPrice += ($QuantityPrice); @endphp
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"> </td>
                    <td colspan="1">Sub-Total</td>
                    <td colspan="1">&#8369;{{ $totalPrice }} </td>
                </tr>
                <tr>
                    <td colspan="3"> </td>
                    <td colspan="1">Less 40%</td>
                    <td colspan="1">-&#8369;{{ $totalPrice - $totalPrice * (1 - 0.4) }} </td>
                </tr>
                <tr>
                    <td colspan="3"> </td>
                    <td colspan="1">Total Sales: </td>
                    <td colspan="1">&#8369;{{ $totalPrice - $totalPrice * 0.4 }} </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
