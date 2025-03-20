 
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
<div class="header">Localbiz</div>
<div class="subheader" id="subheader" style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">Top Purchase's Report</div>
 
@php
    $maxValue = max($topProducts->pluck('total_sold')->toArray()) ?? 1; // Get highest sales
    $barWidth = 150; // Adjust width of bars
    $chartWidth = count($topProducts) * ($barWidth + 20) + 100; // Dynamic width
@endphp

<h2 class="text-xl font-bold mb-4">Top 10 Products</h2>

<!-- SVG Bar Chart -->
<div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md flex justify-center">
    <svg width="{{ $chartWidth }}" height="350" viewBox="0 0 {{ $chartWidth }} 350" xmlns="http://www.w3.org/2000/svg" style="margin-left:'auto';margin-right:'auto'">
        
        <!-- Y-Axis and Labels -->
        <line x1="50" y1="50" x2="50" y2="300" stroke="black" stroke-width="2"></line>
        <line x1="50" y1="300" x2="{{ $chartWidth - 50 }}" y2="300" stroke="black" stroke-width="2"></line>

        <!-- Y-Axis Scale Labels -->
        @for ($i = 0; $i <= 5; $i++)
            @php
                $yValue = round($maxValue * ($i / 5));
                $yPos = 300 - ($i * 50);
            @endphp
            <text x="10" y="{{ $yPos + 5 }}" font-size="14" fill="black">{{ $yValue }}</text>
            <line x1="45" y1="{{ $yPos }}" x2="50" y2="{{ $yPos }}" stroke="black"></line>
        @endfor

        <!-- Bars with Tooltips -->
        @foreach ($topProducts as $index => $product)
            @php
                $barHeight = ($product->total_sold / $maxValue) * 250;
                $barX = 70 + ($index * ($barWidth + 20));
                $tooltipX = $barX + ($barWidth / 2);
            @endphp

            <!-- Bar -->
            <rect x="{{ $barX }}" y="{{ 300 - $barHeight }}" width="{{ $barWidth }}" height="{{ $barHeight }}"
                  fill="#007BFF" stroke="black" stroke-width="1"
                  ></rect>

            <!-- Product Name Below Each Bar -->
            <text x="{{ $barX + ($barWidth / 2) }}" y="320" font-size="12" text-anchor="middle" fill="black">
                {{ Str::limit($product->name, 10) }}
            </text>
        @endforeach

        
    </svg>
</div>



<table class="hidden_table">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Product</th>
            <th>Total Orders</th>
            <th>Total Units Sold</th>
            <th>Revenue ($)</th>
            <th>Average Order Value ($)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($topProducts as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->total_orders  }}</td>
                <td>{{  $product->total_sold }}</td>
                <td>${{ number_format(($product->price * $product->total_sold), 2) }}</td>
                <td>${{ number_format(($product->price * $product->total_orders) /$product->total_orders, 2) }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
              
              

 