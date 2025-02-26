<?php

namespace App\Exports;

use App\Models\Orders;
use App\Models\Seller;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents, WithMapping
{
    public function collection()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();

        $orderItems = OrderItems::with(['order', 'product'])
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered');
            })
            ->get();

        $orderData = $orderItems->map(function ($orderItem) {
            return [
                'Order Number' => $orderItem->order->order_number,
                'Customer Name' => $orderItem->order->user->name,
                'Product Name' => $orderItem->product->name,
                'Price' => $orderItem->price,
                'Location' => $orderItem->product->location->name,
                'Total Amount' => $orderItem->order->total_amount,
                'Status' => $orderItem->order->status,
            ];
        });

        return $orderData;
    }

    public function headings(): array
    {
        return ['Order Number', 'Customer Name', 'Product Name', 'Price', 'Location', 'Total Amount', 'Status'];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
            'D' => '@',
            'E' => '@',
            'F' => '@',
            'G' => '@',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->setAutoFilter('A1:G1');
            },
        ];
    }

    public function map($order): array
    {
        return [$order['Order Number'], $order['Customer Name'], $order['Product Name'], $order['Price'], $order['Location'], $order['Total Amount'], $order['Status']];
    }
}
