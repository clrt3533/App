<?php

namespace App\Http\Controllers\Billar\Reports;

use App\Filters\Billar\Reports\PaymentSummaryFilter;
use App\Http\Controllers\Controller;
use App\Services\Billar\Reports\PaymentSummaryReportService;
use Illuminate\Http\Request;

class PaymentSummaryController extends Controller
{
    public function __construct(PaymentSummaryReportService $service, PaymentSummaryFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->with('paymentMethod', 'invoice.client')
            ->filters($this->filter)
            ->paginate(request('per_page', 10));
    }
}
