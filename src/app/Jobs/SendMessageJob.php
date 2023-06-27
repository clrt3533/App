<?php

namespace App\Jobs;

use App\Services\App\SmsSetting\Message91Services;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private string $messageType;
    private string $phone_number;
    private string $received_amount;
    private string $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $messageType, string $phone_number, float $received_amount, string $date = '')
    {
        $this->messageType  =  $messageType;
        $this->phone_number =  $phone_number;
        $this->received_amount = $received_amount;
        $this->date         =  $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Message91Services $message91Services)
    {
        if ($this->messageType === 'onboard-message') {
            $response = $message91Services->sendOnBoardedMessage($this->phone_number);
        } elseif ($this->messageType === 'book-confirmation-message') {
            $response = $message91Services->sendBookingConfirmedMessage($this->phone_number, $this->received_amount, $this->date);
        } elseif ($this->messageType === 'estimate-generation-message') {
            $response = $message91Services->sendEstimateGenerationMessage($this->phone_number);
        }
    }
}
