<?php

namespace App\Filters\Billar\ReceiptHistory;

use App\Filters\Billar\Traits\PaymentMethodFilterTraits;
use App\Filters\Billar\Traits\PaymentFilterTraits;
use App\Filters\Core\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class ReceiptHistoryFilter extends BaseFilter
{
    use PaymentMethodFilterTraits, PaymentFilterTraits;

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->whereHas('payment', function (Builder $builder) use ($search) {
                $builder->where('payment_id', 'LIKE', "%$search%");
            });
        });
    }

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        return $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(date)'), array_values($date));
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
