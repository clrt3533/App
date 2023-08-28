<?php

namespace App\Models\Billar\BillHistory;

use App\Models\Billar\Invoice\Invoice;
use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillHistory extends BaseModel
{
    use BootTrait, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'packing',
        'unloading',
        'local',
        'gst',
        'transport',
        'unpacking',
        'car_transport',
        'loading',
        'ac',
        'insuarance'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
