<?php
namespace App\Services\Billar\ReceiptHistory;

use App\Models\Billar\ReceiptHistory\ReceiptHistory;
use App\Services\Billar\ApplicationBaseService;
use PDF;
use Illuminate\Support\Facades\Storage;

class ReceiptHistoryService extends ApplicationBaseService {
    public function __construct(ReceiptHistory $receiptHistory)
    {
        $this->model = $receiptHistory;
    }

    public function setValidation(): self
    {
        validator(request()->all(), [
            'payment_id' => 'required',
            'payment_method_id' => 'required',
            'date' => 'required|date',
            'amount' => 'required',
            'amount_words' => 'required',
            'client_name' => 'required',
            'client_phone' => 'required',
            'from' => 'required',
            'to' => 'required'
        ], [
            'payment_id.required' => 'The payment field is required.',
            'payment_method_id.required' => 'The payment method field is required.',
        ])->validate();

        return $this;
    }

    public function pdfGenerate($receiptInfo):self
    {
        $pdf = PDF::loadView('receipts.receipt-generate', [
            'receipt' => $receiptInfo
        ]);

        $output = $pdf->output();
        $filePath = $this->getAttribute('file_path');
        Storage::put($filePath, $output);
        return $this;
    }
}
