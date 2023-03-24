<?php

namespace App\Exports\Billar;

use App\Models\Billar\Invoice\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoiceExport implements FromCollection, WithHeadings
{

    public function headings(): array
    {
        return [
            __t('client_name'),
            __t('invoice_number'),
            __t('date'),
            __t('due_date'),
            __t('status'),
            __t('recurring_cycle'),
            __t('sub_total'),
            __t('discount_amount'),
            __t('due_amount'),
            __t('received_amount'),
            __t('total'),
        ];
    }

    public function collection()
    {
        $data = Invoice::get();

        return $data->map(function ($row) {
            return [
                'client_name' => @$row->client->full_name,
                'invoice_number' => $row->invoice_number,
                'date' => $row->date,
                'due_date' => $row->due_date,
                'status' => $row->status->translated_name,
                'recurring_cycle' => @$row->recurringCycle->name,
                'sub_total' => $row->sub_total ?? 0,
                'discount_amount' => $row->discount_amount ?? 0,
                'due_amount' => $row->due_amount ?? 0,
                'received_amount' => $row->received_amount ?? 0,
                'total' => $row->total,
            ];
        });
    }
}
