<?php

namespace App\Exports;

use App\Models\OrderItems;
use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AdminOrdersExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    public function collection()
    {
        return Orders::with('user', 'orderItems')
            ->get()
            ->map(function ($order) {
                return [
                    'Customer Name' => $order->user->name,
                    'Total Amount' => $order->total_amount,
                    'Order Number' => $order->order_number,
                    'Status' => $order->status,
                ];
            });
    }

    public function headings(): array
    {
        return ['Customer Name', 'Total Amount', 'Order Number', 'Status'];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
            'D' => '@',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'D') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->setAutoFilter('A1:D1');
            },
        ];
    }
}
