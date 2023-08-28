<?php

namespace App\Services\Billar\BillHistory;

use App\Models\Billar\BillHistory\BillHistory;
use App\Models\Billar\Invoice\Invoice;
use App\Services\Billar\ApplicationBaseService;
use PDF;
use Illuminate\Support\Facades\Storage;

class BillHistoryService extends ApplicationBaseService
{
    public function __construct(BillHistory $billHistory)
    {
        $this->model = $billHistory;
    }

    public function setValidation(): self
    {
        validator(request()->all(), [
            'invoice_id' => 'required',
            'packing' => 'required',
            'loading' => 'required',
            'unloading' => 'required',
            'local' => 'required',
            'gst' => 'required',
            'transport' => 'required',
            'car_transport' => 'required',
            'unpacking' => 'required',
            'ac' => 'required',
            'insuarance' => 'required'
        ], [
            'invoice_id.required' => 'The invoice field is required.',
        ])->validate();

        return $this;
    }

    public function pdfGenerate($billInfo): self
    {
        $pdf = PDF::loadView('bills.bill-generate', [
            'bill' => $billInfo
        ]);

        $output = $pdf->output();
        $filePath = $this->getAttribute('file_path');
        Storage::put($filePath, $output);
        return $this;
    }

    public function findBillByInvoiceId($invoiceId)
    {
        return BillHistory::where('invoice_id', $invoiceId)->first();
    }

    public function findByInvoiceId($invoiceId)
    {
        return Invoice::where('id', $invoiceId)->first();
    }
}
