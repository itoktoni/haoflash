<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Enums\PurchaseStatus;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Models\Po;
use Modules\Procurement\Dao\Models\PoReceive;
use Modules\Procurement\Dao\Models\StockBdp;
use Modules\System\Plugins\Helper;

class BdpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(StockBdp $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $activate = self::$model
            ->select('stock_id')
            ->where(self::$model->mask_product_id(), $this->stock_refer_product_id)
            ->whereBetween(self::$model->mask_code(), [$this->start, $this->end])
            ->count();

        $this->merge([
            'activate' => $activate,
        ]);
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'qty' => 'required|integer|min:1',
                'start' => 'required|integer|min:1',
                'end' => 'required|integer|min:1',
                'stock_refer_product_id' => 'required',
                'stock_product_id' => 'required',
            ];
        }
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $calculate = $this->end - $this->start;
            $qty = !empty($this->qty) ? $this->qty : 0;
            $activate = $this->activate ?? 0;

            if ($calculate < 0) {
                $validator->errors()->add('end', 'Number End harus lebih besar dari Start');
            }

            if ($activate == 0) {

                $validator->errors()->add('start', 'Serial Number tidak ditemukan');
                $validator->errors()->add('end', 'Serial Number tidak ditemukan');
            }

            if ($activate != $qty) {

                $validator->errors()->add('qty', 'Jumlah Serial number = ' . $activate . ' dan Estimasi Qty tidak sama');
            }
        });
    }

    public function attributes()
    {
        return [
            PoFacades::mask_supplier_id() => 'Customer',
        ];
    }

    public function messages()
    {
        return [
            'detail.required' => 'Please input detail product !'
        ];
    }
}
