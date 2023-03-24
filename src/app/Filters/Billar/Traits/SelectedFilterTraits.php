<?php

namespace App\Filters\Billar\Traits;

trait SelectedFilterTraits
{
    public function selected($selected = null)
    {
        $this->builder->when($selected, function ($query) use ($selected) {
            if (!request()->get('search')) {
                $query->where('id', $selected);
            }
        });
    }
}