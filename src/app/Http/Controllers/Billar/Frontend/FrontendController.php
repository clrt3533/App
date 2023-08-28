<?php

namespace App\Http\Controllers\Billar\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Billar\Invoice\Invoice;
use App\Models\Billar\Recurring\RecurringCycle;
use App\Models\Billar\Tax\Tax;
use App\Models\Core\Setting\Setting;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function clientView()
    {
        return view('clients.index');
    }

    public function clientDetailsView($id)
    {
        return view('clients.client-details', compact('id'));
    }

    public function invoiceView()
    {
        return view('invoices.index');
    }
    public function invoiceCreateView()
    {
        $status = resolve(StatusRepository::class)->statuses('invoice');
        $recurringCycle = RecurringCycle::query()->select('id', 'name')->get();
        $taxs = Tax::query()->select('id', 'name', 'value')->get();
        $terms = Setting::where('name','company_terms')->first();
        if (empty($terms)){
            $terms = [
                'error' => 'error',
                'value' => 'error',
            ];
            $terms = collect($terms);
        }

        return view('invoices.create', compact('status', 'recurringCycle', 'taxs','terms'));
    }

    public function invoiceEditView($id)
    {
        $status = resolve(StatusRepository::class)->statuses('invoice');
        $recurringCycle = RecurringCycle::query()->select('id', 'name')->get();
        $taxs = Tax::query()->select('id', 'name', 'value')->get();

        return view('invoices.edit', compact('status', 'recurringCycle', 'taxs', 'id'));
    }

    public function invoiceDetails($id)
    {
        return view('invoices.invoice-details', compact('id'));
    }

    public function paymentView()
    {
        return view('payments.index');
    }

    public function productView()
    {
        return view('products.index');
    }

    public function categoryView()
    {
        return view('products.categories');
    }

//    public function generalSummaryView()
//    {
//        return view('reports.general-summary-report');
//    }

    public function paymentSummaryView()
    {
        return view('reports.payment-summary-report');
    }

    public function clientStatementView()
    {
        return view('reports.client-statement-report');
    }

    public function invoiceReportView()
    {
        return view('reports.invoice-report');
    }

    public function expenseReportView()
    {
        return view('reports.expense-report');
    }

    public function settings()
    {
        return view('settings.app-setting');
    }

    public function expenseView()
    {
        return view('expense.index');
    }

    public function purposeView()
    {
        return view('purpose.index');
    }

    public function recurringInvoiceView()
    {
        return view('invoices.recurring-invoice');
    }

    public function estimatesView()
    {
        $status = resolve(StatusRepository::class)->statuses('estimate');

        return view('estimates.index',compact('status'));
    }

    public function estimatesCreateView()
    {
        $status = resolve(StatusRepository::class)->statuses('estimate');
        $taxs = Tax::query()->select('id', 'name', 'value')->get();

        return view('estimates.create', compact('status','taxs' ));
    }

    public function estimatesEditView($id)
    {
        $status = resolve(StatusRepository::class)->statuses('estimate');
        $taxs = Tax::query()->select('id', 'name', 'value')->get();

        return view('estimates.edit', compact('status','taxs','id'));
    }

    public function receiptView()
    {
        return view('receipts.index');
    }

    public function receiptDetails($id)
    {
        return view('receipts.receipt-details', compact('id'));
    }

    public function billView()
    {
        return view('bills.index');
    }

    public function billDetails($id)
    {
        return view('bills.bill-details', compact('id'));
    }
}
