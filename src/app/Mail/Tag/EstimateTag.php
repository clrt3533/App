<?php

namespace App\Mail\Tag;

use App\Helpers\App\Traits\DateFormatTrait;

class EstimateTag extends Tag
{
    use DateFormatTrait;

    protected object $estimate;

    public function __construct($estimate)
    {
        $this->estimate = $estimate;
    }

    public function estimateGenerate(): array
    {
        return $this->notification();
    }

    public function notification(): array
    {
        return array_merge([
            '{receiver_name}' => $this->estimate->client->full_name,
            '{estimate_number}' => $this->estimate->estimate_number,
            '{date}' => $this->dateFormat($this->estimate->issue_date),
        ], $this->appNameAndLogo());
    }
}