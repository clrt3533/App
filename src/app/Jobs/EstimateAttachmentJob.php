<?php

namespace App\Jobs;

use App\Mail\Billar\EstimateAttachmentMail;
use App\Models\Billar\Estimates\Estimate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EstimateAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Estimate $estimate;
    protected $notificationEventName;

    public function __construct(Estimate $estimate, $notificationEventName = null)
    {
        $this->estimate = $estimate;
        $this->notificationEventName = $notificationEventName;
    }


    public function handle()
    {
        Mail::to(optional($this->estimate->client)->email)
            ->send(
                (new EstimateAttachmentMail($this->estimate, $this->notificationEventName)));

        Storage::delete('public/pdf/estimate_' . $this->estimate->id . '.pdf');
    }
}
