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
use Modules\System\Plugins\Helper;

class PurchaseReceiveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(PoReceive $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $autonumber = Helper::autoNumber(self::$model->getTable(), self::$model->getKeyName(), 'RCV' . date('Ym'), 20);
        $selisih = $complete = 0;
        // $complete = ($this->remaining + $this->po_receive_receive) == $this->po_receive_qty ? 1 : 0;

        try {
            $selisih = ($this->po_receive_end - $this->po_receive_start) + 1;
        } catch (\Throwable $th) {
        }

        $this->merge([
            PoReceiveFacades::getKeyName() => $autonumber,
            'selisih' => $selisih,
            'complete' => $complete,
            'po_receive_buy' => Helper::filterInput($this->po_receive_buy)
        ]);
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'po_receive_qty' => 'required|integer',
                'po_receive_receive' => 'required|integer|min:1',
                'po_receive_buy' => 'required',
                // 'po_receive_sell' => 'required|numeric',
                'po_receive_branch_id' => 'required',
            ];
        }
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $receive = !empty($this->po_receive_receive) ? $this->po_receive_receive : 0;
            $qty = !empty($this->po_receive_qty) ? $this->po_receive_qty : 0;
            $remaining = !empty($this->remaining) ? $this->remaining : 0;

            if ($this->po_receive_type != CategoryType::Accesories) {

                if (empty($this->po_receive_expired_date)) {

                    $validator->errors()->add('po_receive_expired_date', 'Expired Date Harus diisi');
                }

                if (empty($this->po_receive_start)) {

                    $validator->errors()->add('po_receive_start', 'Start number harus diisi ');
                }
                if (empty($this->po_receive_end)) {

                    $validator->errors()->add('po_receive_end', 'Start number harus diisi ');
                }
                try {
                    $calculate = $this->po_receive_end - $this->po_receive_start;
                    if ($calculate < 0) {
                        $validator->errors()->add('po_receive_end', 'Number End harus lebih besar dari Start');
                    }
                    if($calculate+1 != $receive){
                        $validator->errors()->add('po_receive_receive', 'Jumlah Qty Receive tidak sama dengan Jumlah Serial Number');
                    }
                } catch (\Throwable $th) {
                    $validator->errors()->add('po_receive_start', 'Format tidak valid');
                    $validator->errors()->add('po_receive_end', 'Format tidak valid');
                }
            }
            if ($receive > $qty) {

                $validator->errors()->add('po_receive_receive', 'Qty receive tidak boleh lebih dari Qty');
            }
            if (($remaining + $receive) > $qty) {

                $validator->errors()->add('po_receive_receive', 'Qty Receive Sudah Melebihi Qty Pesanan');
            }

            if ($this->purchase_status == PurchaseStatus::Create || $this->purchase_status == PurchaseStatus::Cancel) {

                $validator->errors()->add('purchase_status', 'Status Harus Sudah Di proses atau di receive');
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
