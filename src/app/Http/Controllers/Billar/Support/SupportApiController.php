<?php

namespace App\Http\Controllers\Billar\Support;

use App\Exceptions\GeneralException;
use App\Filters\Billar\Category\CategoryFilter;
use App\Filters\Billar\Client\ClientFilter;
use App\Filters\Billar\Invoice\InvoiceFilter;
use App\Filters\Billar\Product\ProductFilter;
use App\Filters\Billar\Reports\PaymentSummaryFilter;
use App\Helpers\traits\DateRangeHelper;
use App\Http\Controllers\Controller;
use App\Models\App\PaymentMethods\PaymentMethod;
use App\Models\Billar\Category\Category;
use App\Models\Billar\Currency\Currency;
use App\Models\Billar\Expense\Expense;
use App\Models\Billar\Invoice\Invoice;
use App\Models\Billar\PaymentHistory\PaymentHistory;
use App\Models\Billar\Product\Product;
use App\Models\Billar\Recurring\RecurringCycle;
use App\Models\Billar\Tax\Tax;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SupportApiController extends Controller
{

    use DateRangeHelper;

//    public function deleteAddress(ClientAddress $clientAddress)
//    {
//        $clientAddress->delete();
//        return deleted_responses('address');
//    }

    public function recurringCycle()
    {
        return RecurringCycle::select('id', 'name')->get();
    }

    public function invoice()
    {
        $paidInvoiceStatus = resolve(StatusRepository::class)->invoicePaid();
        return Invoice::query()
            ->filters(new InvoiceFilter())
            ->where('status_id', '<>', $paidInvoiceStatus)
            ->select('id', 'invoice_number', 'due_amount')
            ->orderByDesc('id')
            ->paginate(request('per_page', 10));
    }

    public function invoiceShow(Invoice $invoice)
    {
        return $invoice;
    }

    public function paymentMethod()
    {
        $activePaymentMethod = Status::findByNameAndType('status_active', 'payment_method')->id;

        return PaymentMethod::query()
            ->when(!auth()->user()->can('create_invoices'), function ($query) {
                $query->whereIn('alias', ['paypal', 'stripe', 'razorpay']);
            })
            ->when(auth()->user()->can('create_invoices'), function ($query) {
                $query->whereNotIn('alias', ['paypal', 'stripe', 'razorpay']);

            })
            ->where('status_id', '=', $activePaymentMethod)
            ->select('id', 'name', 'alias')
            ->get();
    }

    public function clientUser()
    {
        return User::query()->whereHas('roles', function ($query) {
            $query->where('alias', '=', 'client');
        })
            ->filters(new ClientFilter())
            ->select('id', 'first_name', 'last_name')
            ->orderByDesc('id')
            ->paginate(request('per_page', 10));
    }

    public function currency()
    {
        return Currency::query()->select('id', 'name')->get();
    }

    public function category()
    {
        return Category::select('id', 'name')
            ->filters(new CategoryFilter())
            ->orderByDesc('id')
            ->paginate(request('per_page', 10));
    }

    public function invoiceNumber()
    {
        return Invoice::withTrashed()->select('id')->orderByDesc('id')->first();
    }

    public function taxes()
    {
        return Tax::select('id', 'name', 'value')->get();
    }

    public function roles()
    {
        return Role::with('users:id,first_name,last_name,email', 'users.profilePicture')
            ->whereNull('alias')
            ->orderBy('id')
            ->get();
    }

    public function invoiceSummation()
    {
        return Invoice::query()->filters(new InvoiceFilter())->select(
            DB::raw('SUM(total) as total_amount'),
            DB::raw('SUM(received_amount) as paid_amount'),
            DB::raw('SUM(due_amount) as due_amount')
        )->first();
    }

    public function paymentSummation(): array
    {
        return [
            'total_amount' => $this->paymentSummationQuery(),
            'total_this_month_amount' => $this->paymentSummationQuery($this->getStartAndEndOf('thisMonth')),
            'total_last_month_amount' => $this->paymentSummationQuery($this->getStartAndEndOf('lastMonth')),
            'total_this_year_amount' => $this->paymentSummationQuery($this->getStartAndEndOf('thisYear')),
        ];
    }

    private function paymentSummationQuery($dateRange = [])
    {
        return PaymentHistory::query()
            ->when(count($dateRange), function (Builder $query) use ($dateRange) {
                $query->whereBetween('received_on', $dateRange);
            })
            ->filters(new PaymentSummaryFilter())->sum('amount');
    }

    public function getSummery(): array
    {
        $expenses = Expense::query()->select('id', 'amount', 'date')->get();
        $total_expense = $expenses->sum('amount');
        $month_expense = $expenses->whereBetween('date', $this->getStartAndEndOf('thisMonth'))->sum('amount');
        $week_expense = $expenses->whereBetween('date', $this->getStartAndEndOf('thisWeek'))->sum('amount');
        $today_expense = $expenses->where('date', today()->toDateString())->sum('amount');

        return [
            'totalExpense' => $total_expense,
            'monthExpense' => $month_expense,
            'weekExpense' => $week_expense,
            'todayExpense' => $today_expense,
            ];
    }

    public function product()
    {
        return Product::query()
            ->filters(new ProductFilter())
            ->select('id', 'name')
            ->orderByDesc('id')
            ->paginate(request('per_page', 10));
    }

    public function stopRecurring(Invoice $invoice)
    {
        throw_if(!auth()->user()->isAppAdmin(), new GeneralException(trans('default.403')));

        $invoice->update([
            'recurring' => 2,
            'recurring_cycle_id' => null
        ]);
        return updated_responses('stop_recurring');

    }

    public function cacheClear()
    {
        throw_if(!auth()->user()->isAppAdmin(), new GeneralException(trans('default.403')));

        Artisan::call('optimize:clear');

        return response()->json([
            'message' => __t('cache_clear_successfully')
        ]);
    }

    public function getStatus($type)
    {
        return Status::query()->where('type', $type)->paginate(request()->get('per_page' ?? 10));
    }
}

