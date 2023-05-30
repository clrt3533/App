<?php

namespace App\Jobs;

use App\Models\Billar\Invoice\Invoice;
use App\Services\App\SmsSetting\Message91Services;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(Message91Services $message91Services)
    {
        Invoice::query()->with(['client.profile'])
            ->where('reminder',false)
            ->whereDate('date',Carbon::tomorrow())
            ->chunk(100,function (Collection $invoices) use($message91Services) {
                $invoices->each(function ($invoice) use($message91Services){
                    $response = $message91Services->sendReminderMessage($invoice->client->profile->contact,$invoice->date);
                    $invoice->update(['reminder'=>true]);
                });
            });
    }
}
