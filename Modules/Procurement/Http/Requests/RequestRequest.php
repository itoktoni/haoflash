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
use Modules\Procurement\Dao\Facades\RoDetailFacades;
use Modules\Procurement\Dao\Facades\RoFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\Procurement\Dao\Models\Po;
use Modules\Procurement\Dao\Models\Ro;
use Modules\System\Plugins\Helper;

class RequestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(Ro $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $autonumber = Helper::autoNumber(self::$model->getTable(), self::$model->getKeyName(), 'RO' . date('Ym'), env('WEBSITE_AUTONUMBER'));
        if (!empty($this->code)) {
            $autonumber = $this->code;
        }

        $map = collect($this->detail)->map(function ($item) use ($autonumber) {
            $data_product = ProductFacades::singleRepository($item['temp_id']);
            $total = Helper::filterInput($item['temp_qty']) * Helper::filterInput($item['temp_price']) ?? 0;
            $data[RoDetailFacades::mask_ro_code()] = $autonumber;
            $data[RoDetailFacades::mask_product_id()] = $item['temp_id'];
            $data[RoDetailFacades::mask_notes()] = $item['temp_desc'];
            $data[RoDetailFacades::mask_product_price()] = $data_product->mask_buy ?? '';
            $data[RoDetailFacades::mask_qty()] = Helper::filterInput($item['temp_qty']);
            $data[RoDetailFacades::mask_price()] = Helper::filterInput($item['temp_price']) ?? 0;
            $data[RoDetailFacades::mask_total()] = $total;
            return $data;
        });

        $total_value = Helper::filterInput($map->sum(RoDetailFacades::mask_total())) ?? 0;
        $total_discount = Helper::filterInput($this->{RoFacades::mask_discount()}) ?? 0;

        $total_summary = $total_value;

        $this->merge([
            RoFacades::getKeyName() => $autonumber,
            RoFacades::mask_value() => $total_value,
            RoFacades::mask_discount() => $total_discount,
            RoFacades::mask_total() => $total_summary,
            'detail' => array_values($map->toArray()),
        ]);
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                RoFacades::mask_branch_id() => 'required',
                'detail' => 'required',
            ];
        }
        return [];
    }

    public function attributes()
    {
        return [
            RoFacades::mask_branch_id() => 'Customer',
        ];
    }

    public function messages()
    {
        return [
            'detail.required' => 'Please input detail product !'
        ];
    }
}
