 
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
    
<!-- <div class="subheader" id="subheader" style="background-color:rgb(161, 124, 0); width:100%; color:white;padding:10px ">Top Purchase's Report</div> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<div class="bg-white p-5 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Top 10 Products</h2>
    <canvas id="topProductsChart"></canvas>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('topProductsChart').getContext('2d');

        const chartData = @json($chartData); // Laravel Data to JS
        const months = Object.keys(chartData); // Extract month labels
        const productNames = [...new Set(Object.values(chartData).flatMap(obj => Object.keys(obj)))];

        const datasets = productNames.map((product, index) => ({
            label: product,
            data: months.map(month => chartData[month][product] || 0),
            backgroundColor: `hsl(${index * 30}, 70%, 60%)`,
            borderColor: `hsl(${index * 30}, 70%, 40%)`,
            borderWidth: 1
        }));

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // 📌 Convert Chart to Image and Export as PDF
        document.getElementById('downloadPDF').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');

            // Convert chart to image
            const canvas = document.getElementById('topProductsChart');
            const imgData = canvas.toDataURL('image/png');

            // Add Image to PDF
            pdf.addImage(imgData, 'PNG', 10, 10, 180, 100);

            // Add Table Data (optional)
            let yOffset = 120;
            pdf.text('Top 10 Products', 10, yOffset);
            yOffset += 10;

            topProducts.forEach((product, index) => {
                pdf.text(`${index + 1}. ${product.name} - Sold: ${product.total_sold}`, 10, yOffset);
                yOffset += 10;
            });

            // Save the PDF
            pdf.save('Top_Products_Report.pdf');
        });
    });
</script>




<table class="hidden_table">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Product</th>
            <th>Total Orders</th>
            <th>Total Units Sold</th>
            <th>Revenue ({!! html_entity_decode('&#8369;') !!})</th>
            <th>Average Order Value ({!! html_entity_decode('&#8369;') !!})</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($topProducts as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->total_orders  }}</td>
                <td>{{  $product->total_sold }}</td>
                <td>{!! html_entity_decode('&#8369;') !!}{{ number_format(($product->price * $product->total_sold), 2) }}</td>
                <td>{!! html_entity_decode('&#8369;') !!}{{ number_format(($product->price * $product->total_orders) /$product->total_orders, 2) }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
              
              

 