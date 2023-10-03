<?php

namespace App\Models\Billar\Inventory;

use App\Models\Billar\Product\Product;
use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryDetail extends BaseModel
{
    use BootTrait;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'quantity',
        'condition',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
}
