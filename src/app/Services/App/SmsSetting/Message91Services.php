<?php

namespace App\Services\App\SmsSetting;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Message91Services
{
    private PendingRequest $client;

    public function __construct(PendingRequest $client)
    {
        $this->client = $client;
    }

    public function sendOnBoardedMessage($phone_number)
    {
        $phone_number = Str::remove('+',$phone_number);
        $phone_number = Str::remove(' ',$phone_number);
        return $this->client->post('/',[
            "template_id"=>"6040cb0ce9550e4c262b10ab",
            "sender"=>"SAIPKR",
            "mobiles"=>"$phone_number",
        ]);
    }

    public function sendBookingConfirmedMessage($phone_number,$date)
    {
        $phone_number = Str::remove('+',$phone_number);
        $phone_number = Str::remove(' ',$phone_number);
       return $this->client->post('/',[
            "template_id"=>"6040ca8e0fdf72715d773ec8",
            "sender"=>"SAIPKR",
            "mobiles"=>"$phone_number",
             "var" => "$date",
        ]);
    }

    public function sendReminderMessage($phone_number,$date)
    {
        $phone_number = Str::remove('+',$phone_number);
        $phone_number = Str::remove(' ',$phone_number);
        return $this->client->post('/',[
            "template_id"=>"6040ca63367591043f1a4183",
            "sender"=>"SAIPKR",
            "mobiles"=>"$phone_number",
            "var" => "$date",
        ]);
    }

    public function sendEstimateGenerationMessage(string $phone_number)
    {
        $phone_number = Str::remove('+',$phone_number);
        $phone_number = Str::remove(' ',$phone_number);
        return $this->client->post('/',[
            "template_id"=>"6040bfedd6fc05609509b496",
            "sender"=>"SAIPKR",
            "mobiles"=>"$phone_number",
        ]);
    }
}