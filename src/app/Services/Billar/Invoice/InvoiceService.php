<?php


namespace App\Services\Billar\Invoice;


use App\Helpers\Core\Traits\HasWhen;
use App\Models\Billar\Invoice\Invoice;
use App\Models\Billar\Invoice\InvoiceDetail;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Billar\ApplicationBaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PDF;

class InvoiceService extends ApplicationBaseService
{
    use HasWhen;

    public function __construct(Invoice $invoice)
    {
        $this->model = $invoice;
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
            'client_name' => 'required|max:191',
            'client_email' => 'required|max:191',
            'client_number' => 'required|max:191',
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $id,
            'recurring' => 'required',
            'date' => 'required|date',
            'recurring_cycle_id' => 'required_if:recurring, ==, 1',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required',
            'product.*.packages' => ['nullable', 'sometimes', 'required', 'integer', 'gt:0', Rule::in([1, 2, 3, 4, 5])],
            'sub_total' => 'required',
            'total' => 'required',
            'from_address' => ['required', 'string'],
            'to_address'   => ['required', 'string']
        ], [
            'client_name.required' => 'The client name is required.',
            'client_email.required' => 'The client email is required.',
            'client_number.required' => 'The client number is required.',
            'recurring_cycle_id.required_if' => 'The recurring cycle field is required.',
            'products.*.product_id.required' => 'The product field is required.',
            'products.*.quantity.required' => 'The quantity field is required.',

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

    public function invoiceDetails(): self
    {
        if (request()->products) {
            foreach (request()->products as $item) {
                if (collect($item)->has('id')) {
                    InvoiceDetail::where('id', $item['id'])->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
                } else {
                    InvoiceDetail::create([
                        'invoice_id' => $this->model->id,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                    ]);
                }
            }
        }

        return $this;
    }

    public function loadInvoiceInfo($invoice)
    {
        $invoiceInfo = $invoice->load([
            'invoiceDetails' => function ($query) {
                $query->with(
                    'product:id,name',
                    'tax:id,name,value'
                );
            },
            'createdBy:id,first_name,last_name'
        ]);

        $invoiceInfo->totalTax = $invoiceInfo->invoiceDetails->map(function ($item) {
            $tax = $item->load('tax')->tax ? $item->load('tax')->tax->value : 0;
            return $this->productTaxSum($item->quantity, $item->price, $tax);
        })->sum();

        return $invoiceInfo;
    }

    public function pdfGenerate($invoiceInfo): self
    {
        $invoiceNote = resolve(SettingRepository::class)->getFormattedSettings('app');

        $invoiceInfo->invoice_note = @$invoiceNote['invoice_note'];

        $pdf = PDF::loadView('invoices.invoice-generate', [
            'invoice' => $invoiceInfo
        ]);

        $output = $pdf->output();
        $filePath = $this->getAttribute('file_path');
        Storage::put($filePath, $output);
        return $this;
    }

    public function tags($invoice, $message): string
    {
        $data = [
            '{invoice_number}' => $invoice['invoice_number'],
            '{invoice_amount}' => $invoice['total'],
            '{invoice_logo}' => '<img src= "' . asset(config('settings.application.invoice_logo')) . '"/>',
            '{client_name}' => $invoice['client_name'],
            '{client_email}' => $invoice['client_email'],
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
}
