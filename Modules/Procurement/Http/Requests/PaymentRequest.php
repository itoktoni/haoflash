<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Finance\Dao\Enums\PaymentMethod;
use Modules\Finance\Dao\Enums\PaymentModel;
use Modules\Finance\Dao\Models\Payment;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Facades\PoFacades;
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
        $this->payment_value_approve =  Helper::filterInput($this->payment_value_approve);
        $this->merge([
            'payment_reference' => $this->code,
            'payment_model' => PaymentModel::PaymentPurchase,
            'payment_value_approve' => $this->payment_value_approve
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $purchase = PoFacades::with(['has_payment'])->find($this->code);
            if ($purchase) {

                $get = !empty($this->payment_value_approve) ? $this->payment_value_approve : 0;
                $total = $purchase->mask_total;
                $instalment = $purchase->has_payment->sum('payment_value_approve') ?? 0;
                $payment = $instalment + Helper::formatNumber($get);

                if ($payment > $total) {
                    $validator->errors()->add('payment_value_approve', 'Pembayaran tidak boleh lebih dari Total PO');
                }
            }

            if($this->payment_method == PaymentMethod::Cash && !empty($this->payment_from)){

                $validator->errors()->add('payment_from', 'Jika Pembayaran Cash, Bank tidak boleh dipilih !');
            }

            if($this->payment_method == PaymentMethod::Transfer && empty($this->payment_from)){

                $validator->errors()->add('payment_from', 'Jika Pembayaran Transfer, Bank harus dipilih !');
            }

        });
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'payment_date' => 'required',
                'payment_value_approve' => 'required|numeric|min:1',
                'payment_person' => 'required',
                'payment_method' => 'required',
                'payment_notes_approve' => 'required',
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
