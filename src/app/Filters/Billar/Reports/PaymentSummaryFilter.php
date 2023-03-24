<?php


namespace App\Filters\Billar\Reports;


use App\Filters\Billar\Traits\InvoiceClientFilterTraits;
use App\Filters\Billar\Traits\PaymentMethodFilterTraits;
use App\Filters\FilterBuilder;
use App\Models\Billar\Traits\InvoiceIndividualClientTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentSummaryFilter extends FilterBuilder
{
    use InvoiceClientFilterTraits, PaymentMethodFilterTraits, InvoiceIndividualClientTrait;

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(received_on)'), array_values($date));
        });
    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->whereHas('invoice', function (Builder $builder) use ($search) {
                $builder->where('invoice_number', 'LIKE', "%$search%");
            });
        });
    }
}