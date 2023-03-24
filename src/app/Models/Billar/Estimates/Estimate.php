<?php

namespace App\Models\Billar\Estimates;

use App\Models\Core\BaseModel;
use App\Models\Core\Status;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends BaseModel
{
    use BootTrait, HasFactory, CreatedByRelationship;

    protected $fillable = [
        'id',
        'client_id',
        'currency_id',
        'estimate_number',
        'date',
        'due_date',
        'status_id',
        'sub_total',
        'discount_type',
        'discount',
        'total',
        'notes',
        'terms',
        'created_by',
        'discount_amount',
        'is_clone'
    ];
    protected $casts = [
        'due_amount' => 'double',
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = (new Carbon($value))->format('y-m-d');
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = (new Carbon($value))->format('y-m-d');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(config('biller.client'));
    }

    public function estimateDetails(): HasMany
    {
        return $this->hasMany(EstimateDetails::class);
    }
}
