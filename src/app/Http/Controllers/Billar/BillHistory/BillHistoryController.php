<?php

namespace App\Http\Controllers\Billar\BillHistory;

use App\Http\Controllers\Controller;
use App\Filters\Billar\BillHistory\BillHistoryFilter;
use App\Models\Billar\BillHistory\BillHistory;
use App\Services\Billar\BillHistory\BillHistoryService;
use Illuminate\Http\Request;

class BillHistoryController extends Controller
{
    public function __construct(BillHistoryService $service, BillHistoryFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->with(['invoice' => function ($query) {
                $query->select('id', 'invoice_number', 'client_name', "client_number");
            }])
            ->filters($this->filter)
            ->orderBy('id', request()->get('orderBy'))
            ->paginate(request('per_page'));
    }

    public function store(Request $request)
    {
        $bill = $this->service
            ->setValidation()
            ->setAttributes($request->all())
            ->save();

        $invoiceInfo = $bill->load('invoice')->invoice;
        return created_responses('bills');
    }

    public function show($id)
    {
        $containsInvoiceQuery = request()->has('invoice');
        if ($containsInvoiceQuery) {
            $bill = $this->service->findBillByInvoiceId($id);;
            if (!$bill) {
                $billByInvoice = $this->service->findByInvoiceId($id);

                unset($billByInvoice["id"]);

                $billByInvoice["invoice_id"] = $id;
                return [
                    "bill" => $billByInvoice
                ];
            } else {
                return [
                    "bill" => $bill
                ];
            }
        } else {
            $bill = $this->service->find($id);
            $invoiceDetails = $bill->invoice;
            return [
                "bill" => $bill,
                "invoice" => $invoiceDetails
            ];
        }
    }

    public function update(Request $request, BillHistory $billHistory)
    {
        $this->service
            ->setModel($billHistory)
            ->setValidation()
            ->setAttributes($request->all())
            ->update();
        return updated_responses('bills');
    }

    public function destroy(BillHistory $billHistory)
    {
        $this->service
            ->setModel($billHistory)
            ->delete();
        return deleted_responses('bills');
    }
}
