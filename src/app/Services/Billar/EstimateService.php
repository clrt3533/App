<?php

namespace App\Services\Billar;

use App\Helpers\Core\Traits\HasWhen;
use App\Jobs\InvoiceAttachmentJob;
use App\Models\Billar\Estimates\Estimate;
use App\Models\Billar\Estimates\EstimateDetails;
use App\Models\Billar\Invoice\Invoice;
use App\Models\Billar\Invoice\InvoiceDetail;
use App\Repositories\Core\Setting\SettingRepository;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Billar\Invoice\InvoiceService;
use App\Services\Core\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;

class EstimateService extends BaseService
{
    use HasWhen;

    public $invoiceService;

    public function __construct(Estimate $estimate, InvoiceService $invoiceService)
    {
        $this->model = $estimate;
        $this->invoiceService = $invoiceService;
    }

    public function individualClient(): self
    {
        $this->builder->when(!auth()->user()->isAppAdmin(), function ($query) {
            $query->where('client_id', auth()->id());
        });
        return $this;
    }

    public function setValidation(): self
    {
        $id = $this->model->id ?: '';
        validator(request()->all(), [
            'client_id' => 'required|max:191',
            'estimate_number' => 'required|unique:estimates,estimate_number,' . $id,
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required',
            'products.*.price' => 'required',
            'products.*.amount' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
        ], [
            'client_id.required' => 'The client field is required.',
            'status_id.required' => 'The status field is required.',
            'products.*.product_id.required' => 'The product field is required.',
            'products.*.quantity.required' => 'The quantity field is required.',
            'products.*.price.required' => 'The price field is required.',
            'products.*.amount.required' => 'The amount field is required.',

        ])->validate();

        return $this;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setSendInvoiceValidation(): self
    {
        validator(request()->all(), [
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ])->validate();

        return $this;
    }

    public function estimateDetails(): self
    {
        if (request()->products) {
            foreach (request()->products as $item) {
                if (collect($item)->has('id')) {
                    EstimateDetails::where('id', $item['id'])->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'tax_id' => $item['tax_id'],
                    ]);
                } else {
                    EstimateDetails::create([
                        'estimate_id' => $this->model->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'tax_id' => $item['tax_id'],
                    ]);
                }
            }
        }

        return $this;
    }

    public function loadEstimateInfo($invoice)
    {
        $estimateInfo = $invoice->load(['estimateDetails' => function ($query) {
            $query->with('product:id,name', 'tax:id,name,value');
        }, 'client:id,first_name,last_name,email', 'createdBy:id,first_name,last_name']);

        $estimateInfo->totalTax = $estimateInfo->estimateDetails->map(function ($item) {
            $tax = $item->load('tax')->tax ? $item->load('tax')->tax->value : 0;
            return $this->productTaxSum($item->quantity, $item->price, $tax);
        })->sum();

        return $estimateInfo;

    }

    public function pdfGenerate($estimateInfo): self
    {
        $pdf = PDF::loadView('estimates.estimate-generate', [
            'estimate' => $estimateInfo
        ]);

        $output = $pdf->output();
        $filePath = $this->getAttribute('file_path');
        Storage::put($filePath, $output);
        return $this;
    }

    public function tags($estimate, $message): string
    {
        $data = [
            '{estimate_number}' => $estimate['estimate_number'],
            '{estimate_amount}' => $estimate['total'],
            '{company_logo}' => '<img src= "' . asset(config('settings.application.company_logo')) . '"/>',
            '{client_name}' => $estimate['client']['full_name'],
            '{client_email}' => $estimate['client']['email'],
            '{company_name}' => config('app.name'),
            '{login_link}' => '<a href="' . url('/') . '"> Click Here </a>',
        ];

        return $this->tagsReplace($data, $message);
    }

    private function tagsReplace($data, $message): string
    {
        return strtr($message, $data);
    }


    public function productTaxSum($quantity, $price, $taxValue)
    {
        return (($quantity * $price) * ($taxValue / 100));
    }

    public function cloneInvoice()
    {
        $estimate = $this->model;
        $estimate->status_id = resolve(StatusRepository::class)->estimateCloned();
        $estimate->is_clone = true;
        $estimate->save();

        $lastInvoice = Invoice::query()->orderByDesc('id')->first();
        $invoice = Invoice::create([
            'client_id' => $this->model->client_id,
            'invoice_number' => $lastInvoice ? $lastInvoice->invoice_number  + 1 : 1,
            'recurring' => 2,
            'is_from_estimate' => true,
            'date' => $this->model->date,
            'due_date' => Carbon::parse($this->model->date)->addDays(7),
            'status_id' => resolve(StatusRepository::class)->invoiceUnpaid(),
            'sub_total' => $this->model->sub_total,
            'discount_type' => $this->model->discount_type,
            'discount' => $this->model->discount,
            'discount_amount' => $this->model->discount_amount,
            'total' => $this->model->total,
            'received_amount' => 0,
            'due_amount' => $this->model->total,
            'notes' => $this->model->notes,
            'terms' => $this->model->terms,
            'created_by' => $this->model->created_by,
        ]);

        foreach ($this->model->estimateDetails as $estimateDetail) {
            $invoice->invoiceDetails()->create([
                'product_id' => $estimateDetail->product_id,
                'quantity' => $estimateDetail->quantity,
                'price' => $estimateDetail->price,
                'tax_id' => $estimateDetail->tax_id
            ]);
        }

        $invoiceInfo = $this->invoiceService->loadInvoiceInfo($invoice);

        $invoiceNote = resolve(SettingRepository::class)->getFormattedSettings('app');

        $invoiceInfo->invoice_note = @$invoiceNote['invoice_note'];

        $this->invoiceService
            ->setAttribute('file_path', 'public/pdf/invoice_' . $invoice->id . '.pdf')
            ->pdfGenerate($invoiceInfo);

        InvoiceAttachmentJob::dispatch($invoiceInfo)->onQueue('high');

    }
}