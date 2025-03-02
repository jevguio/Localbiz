<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
</head>
<body>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px;  }
        #subheader { 
            text-align: center; 
            font-size: 14px; 
            font-weight: bold; 
            margin-bottom: 20px; 
            background-color:'rgb(192, 147, 0)'; 
            width:100%; 
            height:auto;
        }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
<div class="header">Localbiz</div>
<div class="subheader" id="subheader" style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">Inventory Report</div>
<p>Seller: {{ $selectedSeller->name ?? 'N/A' }}</p>
    <p>Generated Date/s: {{ now()->format('F j, Y, g:i a') }}</p>

    <table class="table">
        <thead >
            <tr>
            <th>Quantity Sold</th>
            <th>Unit</th>
                <th>Descriptsion</th>
                <th>Selling Price</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
        @php $totalPrice = 0; @endphp
        @php $QuantityPrice = 0; @endphp
            @foreach ($items as $index => $item)
                @php $QuantityPrice += $item->price * $item->sold; @endphp

                @if ($item->seller_id == 1)
                <tr>
                    <td>{{ $item->sold }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->price }}</td>
                    <td>${{ $QuantityPrice }}</td>
                </tr>
                @endif
                @php $totalPrice += $QuantityPrice; @endphp
            @endforeach

        </tbody> 
            <tfoot>
                <tr>
                    <td colspan="4"><strong>Sub-Total Less 40%</strong></td>
                    <td><strong>${{ number_format($totalPrice * 0.4, 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Total:</strong></td>
                    <td><strong>${{ number_format($totalPrice, 2) }}</strong></td>
                </tr> 
                <tr>
                    <td colspan="5" style="padding-top: 10px;"><strong>Date: {{ now()->format('F j, Y, g:i a') }}</strong></td>
                </tr>
            </tfoot> 
    </table>
    
</body>
</html>
