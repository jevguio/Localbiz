<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Customer Name',
            'Order ID',
            'Amount',
            'Payment Method',
            'Status',
            'Transaction Date'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->order->user->name,
            $payment->order->id,
            number_format($payment->amount, 2),
            $payment->payment_method,
            $payment->status,
            $payment->created_at->format('Y-m-d H:i:s')
        ];
    }
}