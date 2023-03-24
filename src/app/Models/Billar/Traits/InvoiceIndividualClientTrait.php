<?php

namespace App\Models\Billar\Traits;

trait InvoiceIndividualClientTrait
{
    public function clientRoleAccess($value = 'no')
    {
        $user = auth()->user();
        if ($user->hasRole(['Client'])) {
            $this->builder->whereHas('invoice', function ($query) {
                $query->where('client_id', auth()->id());
            });
        } else {
            $this->builder->whereHas('invoice', function ($query) {
                $query->when(!auth()->user()->can('show_all_data'), function ($q) {
                    $q->where('created_by', auth()->id());
                });
            });
        }
    }

}