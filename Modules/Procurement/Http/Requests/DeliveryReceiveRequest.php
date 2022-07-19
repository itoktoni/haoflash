<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Enums\PurchaseStatus;
use Modules\Procurement\Dao\Enums\SupplierPph;
use Modules\Procurement\Dao\Enums\SupplierPpn;
use Modules\Procurement\Dao\Enums\SupplierType;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\Procurement\Dao\Models\De;
use Modules\Procurement\Dao\Models\Po;
use Modules\Procurement\Dao\Models\Ro;
use Modules\System\Plugins\Helper;

class DeliveryReceiveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(De $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $this->merge([]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->detail as $detail) {
                if ($detail['do_detail_prepare'] != $detail['do_detail_qty']) {
                    $validator->errors()->add($detail['do_detail_key'], 'Qty tidak sama dengan yang di receive');
                }
            }
        });
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'detail' => 'required',
            ];
        }
        return [];
    }

    public function attributes()
    {
        return [
            DeFacades::mask_branch_id() => 'Branch',
        ];
    }

    public function messages()
    {
        return [
            'detail.required' => 'Please input detail product !'
        ];
    }
}
