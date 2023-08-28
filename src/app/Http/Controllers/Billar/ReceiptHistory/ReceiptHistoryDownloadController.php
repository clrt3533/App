<?php

namespace App\Http\Controllers\Billar\ReceiptHistory;

use App\Http\Controllers\Controller;
use App\Models\App\PaymentMethods\PaymentMethod;
use App\Services\Billar\ReceiptHistory\ReceiptHistoryService;
use Illuminate\Http\Request;
use PDF;

class ReceiptHistoryDownloadController extends Controller
{
    public function __construct(ReceiptHistoryService $service)
    {
        $this->service = $service;
    }

    public function download(Request $request)
    {
        $id = $request->route('receipt');
        $receiptInfo = $this->service->find($id);

        $paymentMethodId = $receiptInfo->payment_method_id;
        $paymentMethodName = PaymentMethod::where('id', $paymentMethodId)->value('name');

        $receiptInfo->paymentMethod = $paymentMethodName;

        // dd($receiptInfo);
        
        $pdf = PDF::loadView('receipts.receipt-generate', [
            'receipt' => $receiptInfo
        ]);
        
        return $pdf->download('receipt' . $receiptInfo->id . '.pdf');
    }
}
