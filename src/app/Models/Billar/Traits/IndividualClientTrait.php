<?php

namespace App\Models\Billar\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IndividualClientTrait
{
    public function clientRoleAccess($value = 'no')
    {
        $user = auth()->user();
        if ($user->hasRole(['Client'])) {
            $this->builder->when($value == 'no', function (Builder $query) {
                $query->where('client_id', auth()->id());
            });
        } else {
            $this->builder->when(!auth()->user()->can('show_all_data'), function (Builder $query) {
                $query->where('created_by', auth()->id());
            });
        }


    }
}