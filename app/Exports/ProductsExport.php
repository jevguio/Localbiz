<?php

namespace App\Exports;

use App\Models\Products;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    public function collection()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        if ($seller) {
            return Products::with(['category', 'seller.user'])
                ->where('seller_id', $seller->id)
                ->get()
                ->map(function ($product) {
                    return [
                        'ID' => $product->id,
                        'Seller Name' => $product->seller->user->name,
                        'Category Name' => $product->category->name,
                        'Name' => $product->name,
                        'Description' => $product->description,
                        'Price' => $product->price,
                        'Stock' => $product->stock,
                        'Image' => $product->image,
                        'Location' => $product->location->name,
                        'Is Active' => $product->is_active,
                        'Created At' => $product->created_at,
                        'Updated At' => $product->updated_at,
                    ];
                });
        }

        return collect();
    }

    public function headings(): array
    {
        return ['ID', 'Seller Name', 'Category Name', 'Name', 'Description', 'Price', 'Stock', 'Image', 'Location', 'Is Active', 'Created At', 'Updated At'];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
            'D' => '@',
            'E' => '@',
            'F' => '0.00',
            'G' => '0',
            'H' => '@',
            'I' => '@',
            'J' => '@',
            'K' => '@',
            'L' => 'yyyy-mm-dd',
            'M' => 'yyyy-mm-dd',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'M') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->setAutoFilter('A1:M1');
            },
        ];
    }
}
