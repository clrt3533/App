<?php

namespace App\Models\Billar\Estimates;

use App\Models\Billar\Product\Product;
use App\Models\Billar\Tax\Tax;
use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimateDetails extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'estimate_id',
        'product_id',
        'quantity',
        'price',
        'tax_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

}
