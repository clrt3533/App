<?php

namespace App\Models\Billar\Product;

use App\Models\Billar\Category\Category;
use App\Models\Billar\Invoice\InvoiceDetail;
use App\Models\Core\BaseModel;
use App\Models\Core\File\File;
use App\Models\Core\Traits\BootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

}
