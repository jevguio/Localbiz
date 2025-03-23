 
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
        .hidden_table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .hidden_table th, .hidden_table td { padding: 8px; text-align: left; }
        .hidden_table th { background-color: #f2f2f2; }
    </style>

<!-- <div class="header">Localbiz</div>
<div class="subheader" id="subheader" style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">Top Seller's Report</div>
   -->
<table class="hidden_table">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Seller Business Name</th>
            <th>Total Orders</th>
            <th>Total Units Sold</th>
            <th>Revenue (₱)</th>
            <th>Average Order Value (₱)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($topSellers as $index => $seller)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $seller->fname }}  </td>
                <td>{{ $seller->total_order }}</td>
                <td>{{ $seller->total_units_sold }}</td>
                <td>₱{{ number_format($seller->revenue, 2) }}</td>
                <td>₱{{ number_format($seller->avg_order_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
              
              

 