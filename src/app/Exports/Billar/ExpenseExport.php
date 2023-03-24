<?php

namespace App\Exports\Billar;

use App\Models\Billar\Expense\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            __t('name'),
            __t('amount'),
            __t('purpose'),
            __t('reference'),
            __t('note'),
            __t('details'),
            __t('date')
        ];
    }

    public function collection()
    {
        $data = Expense::query()->get();

        return $data->map(function ($row) {
            return [
                'name' => $row->name,
                'amount' => $row->amount,
                'purpose' => $row->purpose->name,
                'reference' => $row->reference,
                'note' => $row->note,
                'details' => $row->details,
                'date' => $row->date,
            ];
        });
    }
}
