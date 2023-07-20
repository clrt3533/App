<?php

namespace App\Filters\Billar\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PaymentFilterTraits
{
    public function payments($ids = null)
    {
        $paymentIds = explode(',', $ids);

        $this->builder->when($ids, function (Builder $query) use ($paymentIds) {
            $query->whereHas('payment', function (Builder $query) use ($paymentIds) {
                $query->whereIn('payment_id', $paymentIds);
            });
        });
    }
}