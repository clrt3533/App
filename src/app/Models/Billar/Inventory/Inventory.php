<?php

namespace App\Models\Billar\Inventory;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\Billar\Invoice\Invoice;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends BaseModel
{
    use BootTrait;

    protected $fillable = [
        'invoice_id',
        'notes',
        'signature'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function inventoryDetails(): HasMany
    {
        return $this->hasMany(InventoryDetail::class);
    }

    public function getImageAttribute()
    {
        return asset('storage/' . $this->signature);
    }
}
