<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AdminProductsExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    public function collection()
    {
        return Products::with(['seller', 'category'])
            ->get()
            ->map(function ($product) {
                return [
                    'Seller Name' => $product->seller->user->name,
                    'Category Name' => $product->category->name,
                    'Name' => $product->name,
                    'Description' => $product->description,
                    'Price' => $product->price,
                    'Stock' => $product->stock,
                    'Image' => $product->image,
                    'Location' => $product->location->name,
                    'Is Active' => $product->is_active,
                ];
            });
    }

    public function headings(): array
    {
        return ['Seller Name', 'Category Name', 'Name', 'Description', 'Price', 'Stock', 'Image', 'Location', 'Is Active'];
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
            'H' => '@',
            'I' => '@',
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->setAutoFilter('A1:I1');

            },
        ];
    }
}
