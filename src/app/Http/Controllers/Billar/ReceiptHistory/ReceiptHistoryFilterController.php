<?php

namespace App\Http\Controllers\Billar\ReceiptHistory;

use App\Filters\Billar\ReceiptHistory\ReceiptHistoryFilter;
use App\Http\Controllers\Controller;
use App\Services\Billar\ReceiptHistory\ReceiptHistoryService;

class ReceiptRangeFilterController extends Controller
{
    public function __construct(ReceiptHistoryService $service, ReceiptHistoryFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function rangeFilter(): array
    {
        $payment['max_amount'] = $this->service->max('amount') ?? 0;
        $payment['min_amount'] = $this->service->min('amount') ?? 0;
        return $payment;
    }
}
