<?php

namespace App\Filters\Billar\BillHistory;

use App\Filters\Billar\Traits\InvoiceClientFilterTraits;
use App\Filters\Billar\Traits\InvoiceNumberFilterTrait;
use App\Filters\Billar\Traits\PaymentFilterTraits;
use App\Filters\Core\BaseFilter;
use App\Models\Billar\Traits\InvoiceIndividualClientTrait;
use Illuminate\Database\Eloquent\Builder;

class BillHistoryFilter extends BaseFilter
{
    use InvoiceNumberFilterTrait, InvoiceClientFilterTraits, InvoiceIndividualClientTrait, PaymentFilterTraits;

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where(function (Builder $builder) use ($search) {
                $builder->whereHas('invoice', function (Builder $builder) use ($search) {
                    $builder->where('invoice_number', 'LIKE', "%$search%");
                })
                    ->orWhereHas('invoice', function (Builder $builder) use ($search) {
                        $builder->where('client_name', 'LIKE', "%$search%");
                    });
            });
        });
    }

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        return $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });
    }

    public function amountRange($value = null)
    {
        $value = json_decode(htmlspecialchars_decode($value), true);
        return $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereBetween('amount', array_values($value));
        });
    }
}