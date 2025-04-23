<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAYMENTS REPORT</title>
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
            style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">PAYMENTS REPORT</div>

        @if ($is_view)
            <button onclick="CloseSalesThis()" class="absolute top-5 right-5 ">
                <i class="bx bx-x text-gray-500 text-2xl"></i>
            </button>
        @endif

        <p style="margin: 5px 0;">Cashier: {{ $selectedSeller ? $selectedSeller->fname : 'NULL' }}</p>
        <p style="margin: 5px 0;" id="SalesDateStartEnd">Generated Date/s: {{ isset($fromDate) ? $fromDate : '' }} -
            {{ isset($toDate) ? $toDate : '' }}</p>
        <p style="margin: 5px 0;" id="SalesDateStartEnd1">Date: {{ now()->format('F j, Y') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center;">Transaction ID</th>
                    <th style="text-align: center;">Payment Date</th> 
                    <th style="text-align: center;">Customer Name</th> 
                    <th style="text-align: center;">Order ID</th>  
                    <th style="text-align: center;">Amount</th> 
                    <th style="text-align: center;">Payment Method</th>
                    <th style="text-align: center;">Status</th> 
                    
                </tr>
            </thead>
            <tbody>  
                @if(count($payments)>0)
                @foreach ($payments as $index => $item) 
 
                        <tr>
                            <td>{{ $item->id }}</td>  
                            <td>{{ $item->paid_at }}</td> 
                            <td>{{ $item->customer->fname.' '.$item->customer->lname }}</td> 
                            <td>{{ $item->order_id }}</td> 
                            <td>{{ $item->payment_amount }}</td>  
                            <td>{{ $item->payment_method }}</td> 
                            <td>{{ $item->order->status }}</td> 
                            
                        </tr>  
                @endforeach
                @else

                <tr>
                    <td colspan="7" style="text-align: center;">No Record Found</td>
                </tr>
                @endif
            </tbody>
            {{-- <tfoot>
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
            </tfoot> --}}
        </table>
    </div>
</body>

</html>
