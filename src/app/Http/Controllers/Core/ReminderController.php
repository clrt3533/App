<?php

namespace App\Http\Controllers\Core;

use Carbon\Carbon;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;

use App\Models\Billar\Invoice\Invoice;

use App\Services\Billar\Invoice\InvoiceService;

use App\Jobs\SendMessageJob;
use App\Jobs\InvoiceAttachmentJob;

/**
 * Class ReminderController.
 */
class ReminderController extends Controller
{

    /**
     * ReminderController constructor.
     *
     * @param InvoiceService $invoice
     */
    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    protected function productTaxSum($quantity, $price, $taxValue)
    {
        return (($quantity * $price) * ($taxValue / 100));
    }


    /**
     *
     * @return mixed
     */
    public function index()
    {
        $now = now();
        $twentyFourHoursFromNow = $now->copy()->addHours(24)->toDateTimeString();
        $twelveHoursFromNow = Carbon::now()->addHours(12)->toDateTimeString();

        $invoices = Invoice::with([])
            ->whereBetween('date', [$now, $twentyFourHoursFromNow])
            // ->where('date', '=', $twelveHoursFromNow)
            ->where('received_amount', '>', 50000)
            ->get();

        $invoiceDetailsArray = [];

        foreach ($invoices as $invoice) {
            $invoiceInfo = $invoice->load(['invoiceDetails' => function ($query) {
                $query->with('product:id,name', 'tax:id,name,value');
            }, 'createdBy:id,first_name,last_name']);

            $invoiceInfo->totalTax = $invoiceInfo->invoiceDetails->map(function ($item) {
                $tax = $item->load('tax')->tax ? $item->load('tax')->tax->value : 0;
                return $this->productTaxSum($item->quantity, $item->price, $tax);
            })->sum();

            $invoiceDetailsArray[$invoice->id] = $invoice->invoiceDetails;

            // InvoiceAttachmentJob::dispatch($invoiceInfo)->onQueue('high');
        }

        return response()->json([
            'invoices' => $invoices,
            // 'invoice_details' => $invoiceDetailsArray
        ]);
    }
}