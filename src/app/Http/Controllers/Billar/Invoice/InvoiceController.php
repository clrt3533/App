<?php

namespace App\Http\Controllers\Billar\Invoice;

use App\Exports\Billar\InvoiceExport;
use App\Filters\Billar\Invoice\InvoiceFilter;
use App\Http\Controllers\Controller;
use App\Jobs\InvoiceAttachmentJob;
use App\Jobs\SendMessageJob;
use App\Models\Billar\Invoice\Invoice;
use App\Models\Billar\Invoice\InvoiceDetail;
use App\Services\Billar\Invoice\InvoiceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(InvoiceService $service, InvoiceFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }


    public function index()
    {
        if (request()->get('invoice_type')) {
            return $this->service
                ->where('recurring', 3)
                ->with('recurringCycle:id,name')
                ->filters($this->filter)
                ->orderBy('id', request()->get('orderBy'))
                ->paginate(request('per_page', 10));
        }

        return $this->service
            ->where('recurring', '!=', 3)
            ->with('recurringCycle:id,name')
            ->filters($this->filter)
            ->orderBy('id', request()->get('orderBy'))
            ->paginate(request('per_page', 10));
    }


    public function store(Request $request)
    {
        $invoice = $this->service
            ->setValidation()
            ->setAttributes($request->all())
            ->save();

        $this->service->when($request->has('products'), fn (InvoiceService $service) => $service->invoiceDetails());
        $invoiceInfo = $this->service->loadInvoiceInfo($invoice);

        $this->service
            ->setAttribute('file_path', 'public/pdf/invoice_' . $invoice->id . '.pdf')
            ->pdfGenerate($invoiceInfo);

        // Send email to client about order confirmation
        // InvoiceAttachmentJob::dispatch($invoiceInfo)->onQueue('high');

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->service->model->date)->format('d-m-y h:i A');

        // Send SMS to client about order confirmation
        SendMessageJob::dispatch('book-confirmation-message', $this->service->model->client_number, $this->service->model->received_amount, $date);

        return created_responses('invoices');
    }


    public function show($id)
    {
        return $this->service
            ->with([
                'createdBy.profile',
                'invoiceDetails' => function ($query) {
                    $query->with('tax:id,name,value')
                        ->join('products', 'invoice_details.product_id', '=', 'products.id')
                        ->join('categories', 'products.category_id', '=', 'categories.id')
                        ->select('invoice_details.*', 'products.name as product_name', 'categories.id as category_id', 'categories.name as category_name');
                }
            ])->find($id);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->service
            ->setModel($invoice)
            ->setValidation()
            ->setAttributes($request->only(
                'client_name',
                'client_email',
                'client_number',
                'invoice_number',
                'recurring',
                'date',
                'packaging_type',
                'recurring_cycle_id',
                'sub_total',
                'discount_type',
                'discount',
                'discount_amount',
                'total',
                'received_amount',
                'due_amount',
                'notes',
                'terms',
                'lift_from_address',
                'lift_to_address',
                'floor_from_address',
                'floor_to_address',
                'from_address',
                'to_address',

                'is_hide_charges',
                'packing',
                'unloading',
                'local',
                'gst',
                'transport',
                'unpacking',
                'car_transport',
                'loading',
                'ac',
                'insuarance'
            ))
            ->update();

        // var_dump($request);
        $this->service->when($request->has('products'), fn (InvoiceService $service) => $service->invoiceDetails());
        return updated_responses('invoices');
    }


    public function destroy(Invoice $invoice)
    {
        $this->service
            ->setModel($invoice)
            ->delete();
        return deleted_responses('invoices');
    }

    public function detailsDelete()
    {
        $invoices = $this->service->find(request('id'));
        $invoices->update(request()->all());
        $invoice = InvoiceDetail::where('id', request('invoice_detail_id'))->delete();
        return deleted_responses('products', ['invoice' => $invoice]);
    }

    public function invoiceExport()
    {
        return Excel::download(new InvoiceExport(), 'invoices.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
