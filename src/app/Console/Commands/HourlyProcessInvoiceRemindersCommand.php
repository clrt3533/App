<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Models\Billar\Invoice\Invoice;
use App\Services\App\SmsSetting\Message91Services;

class HourlyProcessInvoiceRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:invoice_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'On hourly basis, check any invoice whose move date is 12 hours from now and send reminder to client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Message91Services $message91Services)
    {
        $now = now();
        $twelveHoursFromNow = Carbon::now()->addHours(24)->setSeconds(0)->toDateTimeString();

        $invoices = Invoice::with([])
            // ->whereBetween('date', [$now, $twentyFourHoursFromNow])
            ->where('date', '=', $twelveHoursFromNow)
            ->where('received_amount', '>', 0)
            ->get();


        if (!$invoices->isEmpty()) {
            foreach ($invoices as $invoice) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('d-m-y h:i A');

                $message91Services->sendReminderMessage($invoice->client_number,$date);

                // $invoiceInfo = $invoice->load(['invoiceDetails' => function ($query) {
                //     $query->with('product:id,name', 'tax:id,name,value');
                // }, 'createdBy:id,first_name,last_name']);
                // InvoiceAttachmentJob::dispatch($invoiceInfo)->onQueue('high');

                // PaymentReminderJob::dispatch($invoice)->onQueue('high');

                $this->info('Reminder send successfully to ' . $invoice->client_number . ', ' . $invoice->client_email . ' at ' . $now);
            }
        } else {
            $this->info('No reminders to send ' . $twelveHoursFromNow);
        }
    }
}
