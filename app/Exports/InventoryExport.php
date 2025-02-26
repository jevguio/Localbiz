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

class InventoryExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    protected $sellerId;

    public function __construct($sellerId)
    {
        $this->sellerId = $sellerId;
    }

    public function collection()
    {
        return Products::with('seller.user')
            ->where('seller_id', $this->sellerId)
            ->get()
            ->map(function ($product) {
                return [
                    'Seller Name' => $product->seller->user->name,
                    'Name' => $product->name,
                    'Stock' => $product->stock,
                ];
            });
    }

    public function headings(): array
    {
        return ['Seller Name', 'Name', 'Stock'];
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
                $sheet->setAutoFilter('A1:C1');
            },
        ];
    }
}
