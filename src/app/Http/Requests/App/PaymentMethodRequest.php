<?php


namespace App\Http\Requests\App;


use App\Models\App\PaymentMethods\PaymentMethod;

class PaymentMethodRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules(new PaymentMethod());
    }

    public function messages()
    {
        if (request()->get('type') === 'razorpay') {
            return [
                'client_id.required' => 'The key id field is required.',
            ];
        }
        return [];
    }

}