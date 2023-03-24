<?php

namespace App\Http\Controllers\Billar\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billar\Expense\Expense;
use App\Services\Billar\Dashboard\DashboardService;

class DashboardController extends Controller
{
    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function card(): array
    {
        $totalClient = $this->service->totalClient();
        $totalInvoice = $this->service->invoiceCount();
        $totalPayment = $this->service->paymentSummation('received_amount');
        $totalAmount = $this->service->paymentSummation('total');
        $totalDueAmount = $this->service->paymentSummation('due_amount');
        $totalProduct = $this->service->totalProduct();
        $totalPaidInvoice = $this->service->invoiceCount($this->service->status['status_paid']);
        $unpaidInvoices = $this->service->invoiceCount($this->service->status['status_unpaid']);
        $partiallyPaidInvoices = $this->service->invoiceCount($this->service->status['status_partially_paid']);
        $overdueInvoices = $this->service->invoiceCount($this->service->status['status_overdue']);
        $totalExpense = auth()->user()->can('view_expenses') ? Expense::query()->sum('amount') : 0;

        return [
            'totalClient' => auth()->user()->can('show_all_data') ? $totalClient : 0,
            'totalInvoice' => $totalInvoice,
            'totalPayment' => $totalPayment,
            'totalAmount' => $totalAmount,
            'totalDueAmount' => $totalDueAmount,
            'totalProduct' => $totalProduct,
            'totalPaidInvoice' => $totalPaidInvoice,
            'unpaidInvoices' => $unpaidInvoices,
            'partiallyPaidInvoices' => $partiallyPaidInvoices,
            'overdueInvoices' => $overdueInvoices,
            'totalExpense' => $totalExpense,
        ];
    }

    public function paymentOverview(): array
    {
        if (auth()->user()->can('payment_overview')) {
            $payments = $this->service->paymentOverView();

            $paymentChat = [];

            foreach ($payments as $payment) {
                $paymentChat[trans('default.received_amount')] = $payment->received_amount;
                $paymentChat[trans('default.due_amount')] = $payment->due_amount;
            }

            return ['payment_overview' => $paymentChat];
        } else {
            return [];
        }
    }

    public function yearlyOverview(): array
    {
        if (auth()->user()->can('yearly_overview')) {
            $monthlyIncome = $this->service->monthlyIncome();
            return $this->service->manipulateChart($monthlyIncome);
        } else {
            return [];
        }

    }
}
