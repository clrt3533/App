<?php

namespace App\Models\Billar\Invoice;

use App\Casts\Packages;
use App\Models\Billar\Product\Product;
use App\Models\Billar\Tax\Tax;
use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceDetail extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'tax_id',
        'packages',
    ];

//    protected $casts = [
//        'packages' => Packages::class
//      ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}
