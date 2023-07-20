<?php

namespace App\Http\Controllers\Billar\ReceiptHistory;

use App\Http\Controllers\Controller;
use App\Filters\Billar\ReceiptHistory\ReceiptHistoryFilter;
use App\Models\Billar\ReceiptHistory\ReceiptHistory;
use App\Services\Billar\ReceiptHistory\ReceiptHistoryService;
use Illuminate\Http\Request;

class ReceiptHistoryController extends Controller
{
    public function __construct(ReceiptHistoryService $service, ReceiptHistoryFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->with(
                ['paymentMethod:id,name', 'payment' => function ($query) {
                    $query->select('id', 'amount');
                }]
            )->filters($this->filter)
            ->orderBy('id', request()->get('orderBy'))
            ->paginate(request('per_page'));
    }

    public function store(Request $request)
    {
        $receipt = $this->service
            ->setValidation()
            ->setAttributes($request->all())
            ->save();

        $invoiceInfo = $receipt->load('payment')->payment;
        return created_responses('receipts');
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update(Request $request, ReceiptHistory $receipt_history)
    {
        $this->service
            ->setModel($receipt_history)
            ->setValidation()
            ->setAttributes($request->all())
            ->update();
        return updated_responses('receipts');
    }

    public function destroy(ReceiptHistory $receipt_history)
    {
        $this->service
            ->setModel($receipt_history)
            ->delete();
        return deleted_responses('receipts');
    }
}
