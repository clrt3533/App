<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Packages implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $returnedValue = '';
        if($value == 1){
            $returnedValue = 'None';
        }elseif($value == 2){
            $returnedValue = 'Bubble';
        }elseif ($value == 3){
            $returnedValue = 'Corrugated';
        }elseif ($value == 4){
            $returnedValue = 'Packing';
        }else{
            $returnedValue = 'Foam';
        }

        return $returnedValue;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
