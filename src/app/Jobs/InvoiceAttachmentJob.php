<?php

namespace App\Jobs;

use App\Mail\Billar\InvoiceAttachmentMail;
use App\Models\Billar\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Invoice $invoice;
    protected $notificationEventName;

    public function __construct(Invoice $invoice, $notificationEventName = null)
    {
        $this->invoice = $invoice;
        $this->notificationEventName = $notificationEventName;
    }


    public function handle()
    {
        Mail::to(optional($this->invoice->client)->email)
            ->send(
                (new InvoiceAttachmentMail($this->invoice, $this->notificationEventName)));

        Storage::delete('public/pdf/invoice_' . $this->invoice->id . '.pdf');
    }
}
