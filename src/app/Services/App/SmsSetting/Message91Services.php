<?php

namespace App\Services\App\SmsSetting;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class Message91Services
{
    private PendingRequest $client;

    public function __construct(PendingRequest $client)
    {
        $this->client = $client;
    }

    public function sendOnBoardedMessage($phone_number)
    {
        $phone_number = Str::remove('+', $phone_number);
        $phone_number = Str::remove(' ', $phone_number);
        return $this->client->post('/', [
            "template_id" => "6040cb0ce9550e4c262b10ab",
            "sender" => "SAIPKR",
            "mobiles" => "$phone_number",
        ]);
    }

    public function sendBookingConfirmedMessage($phone_number, $received_amount, $date)
    {

        $phone_number = Str::remove('+', $phone_number);
        $phone_number = Str::remove(' ', $phone_number);

        // Set the header parameters
        $headers = [
            'accept' => 'application/json',
            'authkey' => '238708A7BIRMsept5ba3c275',
            'content-type' => 'application/json',
        ];

        $template_id = "";
        if ($received_amount > 0) {
            $template_id = "6040ca8e0fdf72715d773ec8";
        } else {
            $template_id = "6040bfedd6fc05609509b496";
        }

        $response = Http::withHeaders($headers)->post('https://control.msg91.com/api/v5/flow/', [
            "sender" => "SAIPKR",
            "template_id" => $template_id,
            "mobiles" => "$phone_number",
            "var" => "$date",
        ]);
        return $response;

        // return $this->client->post('/', [
        //     "template_id" => "6040ca8e0fdf72715d773ec8",
        //     "sender" => "SAIPKR",
        //     "mobiles" => "$phone_number",
        //     "var" => "$date",
        // ]);
    }

    public function sendReminderMessage($phone_number, $date)
    {
        $phone_number = Str::remove('+', $phone_number);
        $phone_number = Str::remove(' ', $phone_number);
        return $this->client->post('/', [
            "template_id" => "6040ca63367591043f1a4183",
            "sender" => "SAIPKR",
            "mobiles" => "$phone_number",
            "var" => "$date",
        ]);
    }

    public function sendEstimateGenerationMessage(string $phone_number)
    {
        $phone_number = Str::remove('+', $phone_number);
        $phone_number = Str::remove(' ', $phone_number);
        return $this->client->post('/', [
            "template_id" => "6040bfedd6fc05609509b496",
            "sender" => "SAIPKR",
            "mobiles" => "$phone_number",
        ]);
    }
}
