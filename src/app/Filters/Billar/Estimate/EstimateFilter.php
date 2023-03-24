<?php

namespace App\Filters\Billar\Estimate;

use App\Filters\Billar\Traits\ClientFilterTraits;
use App\Filters\Billar\Traits\InvoiceNumberFilterTrait;
use App\Filters\Billar\Traits\NameFilterTrait;
use App\Filters\Billar\Traits\SelectedFilterTraits;
use App\Filters\Billar\Traits\StatusFilterTrait;
use App\Filters\Core\BaseFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Models\Billar\Traits\IndividualClientTrait;
use Illuminate\Database\Eloquent\Builder;

class EstimateFilter extends BaseFilter
{
    use StatusFilterTrait,
        ClientFilterTraits,
        IndividualClientTrait;

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(date)'), array_values($date));
        });
    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('estimate_number', 'LIKE', "%$search%");
        });
    }




}