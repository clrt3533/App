<?php

namespace App\Mail\Tag;

use App\Helpers\App\Traits\DateFormatTrait;

class InvoiceTag extends Tag
{
    use DateFormatTrait;

    protected object $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function invoiceGenerate(): array
    {
        return $this->notification();
    }

    public function notification(): array
    {
        return array_merge([
            '{receiver_name}' => $this->invoice->client->full_name,
            '{invoice_number}' => $this->invoice->invoice_number,
            '{date}' => $this->dateFormat($this->invoice->due_date),
        ], $this->appNameAndLogo());
    }
}