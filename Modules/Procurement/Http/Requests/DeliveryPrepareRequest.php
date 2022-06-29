<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Procurement\Dao\Enums\DeliveryStatus;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Enums\PurchaseStatus;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Models\DePrepare;
use Modules\System\Plugins\Helper;

class DeliveryPrepareRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(DePrepare $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $autonumber = Helper::autoNumber(self::$model->getTable(), self::$model->getKeyName(), 'PRE' . date('Ym'), 20);
        $selisih = $complete = 0;
        $data = [];

        try {
            $selisih = ($this->do_prepare_end - $this->do_prepare_start) + 1;
            $data = range($this->do_prepare_start, $this->do_prepare_end);
        } catch (\Throwable $th) {
        }

        $this->merge([
            DePrepareFacades::getKeyName() => $autonumber,
            'selisih' => $selisih,
            'complete' => $complete,
            'serial' => $data,
            'do_prepare_buy' => Helper::filterInput($this->do_prepare_buy),
            'do_prepare_sell' => Helper::filterInput($this->do_prepare_sell),
        ]);

    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'do_prepare_qty' => 'required|integer',
                'do_prepare_prepare' => 'required|integer|min:1',
                'do_prepare_buy' => 'required',
                'do_prepare_sell' => 'required|numeric',
                'do_prepare_branch_id' => 'required',
                'serial' => 'required',
            ];
        }
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $prepare = !empty($this->do_prepare_prepare) ? $this->do_prepare_prepare : 0;
            $qty = !empty($this->do_prepare_qty) ? $this->do_prepare_qty : 0;

            if ($this->do_prepare_type != CategoryType::Accesories) {

                if (empty($this->do_prepare_start)) {

                    $validator->errors()->add('do_prepare_start', 'Start number harus diisi ');
                }
                if (empty($this->do_prepare_end)) {

                    $validator->errors()->add('do_prepare_end', 'Start number harus diisi ');
                }
                try {
                    $calculate = $this->do_prepare_end - $this->do_prepare_start;
                    if ($calculate < 0) {
                        $validator->errors()->add('do_prepare_end', 'Number End harus lebih besar dari Start');
                    }
                    if ($calculate + 1 != $prepare) {
                        $validator->errors()->add('do_prepare_prepare', 'Jumlah Qty Receive tidak sama dengan Jumlah Serial Number');
                    }
                } catch (\Throwable $th) {
                    $validator->errors()->add('do_prepare_start', 'Format tidak valid');
                    $validator->errors()->add('do_prepare_end', 'Format tidak valid');
                }

                $detail = DB::table('view_summary_stock')->where('id', $this->do_prepare_key)->first();
                $stock = StockFacades::whereIn(StockFacades::mask_code(), $this->serial)
                    ->where(StockFacades::mask_product_id(), $this->do_prepare_product_id)
                    ->where(StockFacades::mask_branch_id(), env('BRANCH_ID'))
                    ->where(StockFacades::mask_supplier_id(), $this->do_prepare_supplier_id)
                    ->where(StockFacades::mask_expired(), $this->do_prepare_expired)
                    ->where(StockFacades::mask_buy(), $this->do_prepare_buy)
                    ->where(StockFacades::mask_transfer(), 0)
                    ->sum(StockFacades::mask_qty());

                if ($stock != $prepare) {

                    $validator->errors()->add('do_prepare_prepare', 'Qty Stock tidak tersedia');
                }
            }
            if ($prepare > $qty) {

                $validator->errors()->add('do_prepare_prepare', 'Qty prepare tidak boleh lebih dari Qty');
            }

            $remaining = StockFacades::where(StockFacades::mask_primary_code(), $this->do_prepare_do_code)
                ->where(StockFacades::mask_product_id(), $this->do_prepare_product_id)
                ->where(StockFacades::mask_branch_id(), env('BRANCH_ID'))
                ->where(StockFacades::mask_supplier_id(), $this->do_prepare_supplier_id)
                ->where(StockFacades::mask_expired(), $this->do_prepare_expired)
                ->where(StockFacades::mask_buy(), $this->do_prepare_buy)
                ->where(StockFacades::mask_transfer(), 1)
                ->sum(StockFacades::mask_qty());

            if (($remaining + $prepare) > $qty) {

                $validator->errors()->add('do_prepare_prepare', 'Qty Receive Sudah Melebihi Qty Pesanan');
            }

            if ($this->status != DeliveryStatus::Create && $this->status != DeliveryStatus::Prepare) {

                $validator->errors()->add('status', 'Status Harus Create atau Prepare');
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
