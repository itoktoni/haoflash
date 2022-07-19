<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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

class DeliveryRequest extends FormRequest
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
        $autonumber = Helper::autoNumber(self::$model->getTable(), self::$model->getKeyName(), 'DO' . date('Ym'), env('WEBSITE_AUTONUMBER'));
        if (!empty($this->code)) {
            $autonumber = $this->code;
        }

        $input = $this->detail;
        $map = collect($this->detail)->map(function ($item) use ($autonumber) {
            $data_product = DB::table('view_summary_stock')->where('id', $item['temp_id'])->first();
            $total = Helper::filterInput($item['temp_qty']) * Helper::filterInput($item['temp_price']) ?? 0;
            $data[DeDetailFacades::mask_do_code()] = $autonumber;
            $data[DeDetailFacades::mask_key()] = $item['temp_id'];
            $data[DeDetailFacades::mask_product_id()] = $data_product->stock_product_id ?? null;
            $data[DeDetailFacades::mask_notes()] = $data_product->product_description ?? null;
            $data[DeDetailFacades::mask_expired()] = $data_product->stock_expired ?? null;
            $data[DeDetailFacades::mask_product_price()] = $data_product->stock_buy ?? null;
            $data[DeDetailFacades::mask_qty()] = Helper::filterInput($item['temp_qty']);
            $data[DeDetailFacades::mask_sell()] = Helper::filterInput($item['temp_price']) ?? 0;
            $data[DeDetailFacades::mask_total()] = $total;
            return $data;
        });

        $total_value = Helper::filterInput($map->sum(DeDetailFacades::mask_total())) ?? 0;
        $total_discount = Helper::filterInput($this->{DeFacades::mask_discount()}) ?? 0;

        $total_summary = $total_value;

        $this->merge([
            DeFacades::getKeyName() => $autonumber,
            DeFacades::mask_value() => $total_value,
            DeFacades::mask_discount() => $total_discount,
            DeFacades::mask_total() => $total_summary,
            'detail' => array_values($map->toArray()),
            'input' => $input,
        ]);

        // dd($this->all());
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (empty($this->code) && !empty($this->input)) {
                foreach ($this->input as $detail) {
                    $id_product = $detail['temp_id'];
                    $name_product = $detail['temp_product'];
                    $qty = $detail['temp_qty'] ?? 0;
                    $stock = DB::table('view_summary_stock')->where('id', $id_product)->first();
                    if (is_null($stock) || $qty > $stock->stock_qty) {
                        $validator->errors()->add($id_product, 'Stock ' . $name_product . ' tinggal = ' . $stock->stock_qty);
                    }
                }
            }
        });
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                DeFacades::mask_branch_id() => 'required',
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
