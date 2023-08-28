<?php

namespace App\Http\Controllers\Billar\BillHistory;

use App\Http\Controllers\Controller;
use App\Services\Billar\BillHistory\BillHistoryService;
use Illuminate\Http\Request;
use PDF;

class BillHistoryDownloadController extends Controller
{
    public function __construct(BillHistoryService $service)
    {
        $this->service = $service;
    }

    public function download(Request $request)
    {
        $id = $request->route('bill');
        $billInfo = $this->service->find($id);

        $pdf = PDF::loadView('bills.bill-generate', [
            'bill' => $billInfo
        ]);
        
        return $pdf->download('bill' . $billInfo->id . '.pdf');
    }
}
