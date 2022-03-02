<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Finance\Dao\Enums\PaymentModel;
use Modules\Finance\Dao\Models\Payment;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Models\Movement;
use Modules\System\Plugins\Helper;

class PaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(Payment $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'payment_reference' => $this->code,
            'payment_model' => PaymentModel::PaymentPurchase,
        ]);

    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'payment_date' => 'required',
                'payment_value_approve' => 'required|numeric',
                'payment_person' => 'required',
            ];
        }
        return [];
    }


    public function attributes()
    {
        return [
            'procurement_po_from_id' => 'Company',
        ];
    }

    public function messages()
    {
        return [
            'detail.required' => 'Please input detail product !'
        ];
    }
}
