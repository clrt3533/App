<?php

namespace App\Models\Billar\ReceiptHistory;

use App\Models\App\PaymentMethods\PaymentMethod;
use App\Models\Billar\PaymentHistory\PaymentHistory;
use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptHistory extends BaseModel
{
    use BootTrait, SoftDeletes;

    protected $fillable = ['payment_id', 'date', 'client_name', 'client_phone', 'payment_method_id', 'amount', 'amount_words', 'from', 'to', 'created_by'];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentHistory::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
