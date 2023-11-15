<?php

namespace App\Filters\Billar\Inventory;

use App\Filters\Core\BaseFilter;
use App\Filters\Billar\Traits\InvoiceClientFilterTraits;
use App\Filters\Billar\Traits\InvoiceNumberFilterTrait;
use App\Models\Billar\Traits\InvoiceIndividualClientTrait;
use Illuminate\Database\Eloquent\Builder;

class InventoryFilter extends BaseFilter
{
    use InvoiceNumberFilterTrait, InvoiceClientFilterTraits, InvoiceIndividualClientTrait;

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
}