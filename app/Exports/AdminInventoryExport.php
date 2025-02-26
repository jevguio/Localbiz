<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AdminInventoryExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    public function collection()
    {
        return Products::with(['seller.user'])
            ->select('seller_id', 'name', 'stock')
            ->get()
            ->map(function ($product) {
                return [
                    'Seller Name' => optional($product->seller->user)->name ?? 'N/A',
                    'Product Name' => $product->name,
                    'Stock' => $product->stock,
                ];
            });
    }

    public function headings(): array
    {
        return ['Seller Name', 'Product Name', 'Stock'];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach (range('A', 'C') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getStyle('A1:C1')->getFont()->setBold(true);
                $sheet->setAutoFilter('A1:C1');
            },
        ];
    }
}