<?php

namespace App\Http\Controllers\App\PaymentMethod;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\PaymentMethodRequest;
use App\Models\App\PaymentMethods\PaymentMethod;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\App\PaymentMethod\PaymentMethodService;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function __construct(PaymentMethodService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->latest()
            ->with(['status'])
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(PaymentMethodRequest $request)
    {
        $status = resolve(StatusRepository::class)->payment_methodActive();

        $attributes = array_merge($request->only('name', 'type', 'is_default', 'mode'), [
            'status_id' => $status,
            'alias' => $request->type,
            'client_key' => $this->paymentMethodType(),
            'secret_key' => $request->secret_key,
            'created_by' => auth()->id(),
        ]);


        $paymentMethod = $this->service
            ->setAttributes($attributes)
            ->save();

        return created_responses('payment_method', ['payment_method' => $paymentMethod]);

    }


    public function show(PaymentMethod $paymentMethod)
    {
        return $this->service
            ->getDataWithFormattedSetting($paymentMethod);
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $attributes = array_merge($request->only('name', 'type', 'is_default', 'mode'), [
            'status_id' => $request->status_id,
            'alias' => $request->type,
            'client_key' => $this->paymentMethodType(),
            'secret_key' => $request->secret_key,
            'created_by' => auth()->id(),
        ]);

        $this->service
            ->setModel($paymentMethod)
            ->save($attributes);

        return updated_responses('payment_method');

    }


    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->settings()->delete();
        $paymentMethod->delete();

        return deleted_responses('payment_method');
    }

    private function paymentMethodType()
    {
        if (request()->get('type') == 'stripe') {
            return request()->get('public_key');

        } elseif (request()->get('type') == 'paypal') {
            return request()->get('client_id');

        } elseif (request()->get('type') == 'razorpay') {
            return request()->get('client_id');
        }

        return null;
    }

    public function paymentMethodStatus()
    {
        return $this->service
            ->getPaymentMethodStatus();
    }
}
