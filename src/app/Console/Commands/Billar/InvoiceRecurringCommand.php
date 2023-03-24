<?php

namespace App\Console\Commands\Billar;

use App\Jobs\InvoiceRecurringJob;
use App\Models\Billar\Invoice\Invoice;
use App\Repositories\Core\Status\StatusRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InvoiceRecurringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        Invoice::query()
            ->with(['recurringCycle:id,name'])
            ->where('date', '<=', date('Y-m-d'))
            ->where('recurring', 1)
            ->get()->each(function ($item) {
                if ($item->recurringCycle) {
                    $this->checkRecurringCycle($item);

                }
            });
    }

    protected function invoiceRecurringQuery($item)
    {
        $recurringInvoice = $item->invoiceRecurrings()
            ->whereYear('recurring_date', Carbon::now()->year)
            ->whereMonth('recurring_date', Carbon::now()->month)
            ->orderBy('recurring_date', 'desc')
            ->first();
        if (!$recurringInvoice) {
            return $item->invoiceRecurrings()
                ->orderBy('recurring_date', 'desc')
                ->first();
        }
        return $recurringInvoice;
    }

    protected function checkRecurringCycle($item)
    {
        $invoiceRecurring = $this->invoiceRecurringQuery($item);
        if ($item->recurringCycle->name == 'Monthly') {

            $recurringDate = $invoiceRecurring ? Carbon::createFromDate($invoiceRecurring->recurring_date)->addMonth()
                : Carbon::createFromDate($item->date)->addMonth();

            $this->store($item, $recurringDate);

        } elseif ($item->recurringCycle->name == 'Quarterly') {

            $recurringDate = $invoiceRecurring ? Carbon::createFromDate($invoiceRecurring->recurring_date)->addMonths(3)
                : Carbon::createFromDate($item->date)->addMonths(3);

            $this->store($item, $recurringDate);


        } elseif ($item->recurringCycle->name == 'Semi annually') {

            $recurringDate = $invoiceRecurring ? Carbon::createFromDate($invoiceRecurring->recurring_date)->addMonths(6)
                : Carbon::createFromDate($item->date)->addMonths(6);

            $this->store($item, $recurringDate);

        } else if ($item->recurringCycle->name == 'Annually') {

            $recurringDate = $invoiceRecurring ? Carbon::createFromDate($invoiceRecurring->recurring_date)->addYear()
                : Carbon::createFromDate($item->date)->addYear();

            $this->store($item, $recurringDate);

        }

    }


    protected function store($item, $recurringDate)
    {
        $recurringDateFormate = Carbon::parse($recurringDate)->format('Y-m-d');

        if (Carbon::today()->format('Y-m-d') > $recurringDateFormate) {

            DB::transaction(function () use ($item, $recurringDateFormate) {

                $date = Carbon::createFromFormat('Y-m-d', $recurringDateFormate);

                $create = $item->create(array_merge($item->toArray(), [
                        'invoice_number' => "{$item->id}-re-" . rand(44444, 66666),
                        'recurring' => 3,
                        'date' => $date,
                        'due_date' => Carbon::createFromFormat('Y-m-d', $recurringDateFormate)->addDays(7),
                        'status_id' => resolve(StatusRepository::class)->invoiceUnpaid(),
                        'received_amount' => 0,
                        'total' => $item->total,
                        'due_amount' => $item->total,
                    ])
                );

                $create->invoiceDetails()->createMany($item->invoiceDetails->toArray());

                $item->invoiceRecurrings()->create([
                    'invoice_id' => $create->id,
                    'recurring_date' => $date
                ]);
                InvoiceRecurringJob::dispatch($create)->onQueue('high');
            });

        }
    }
}
