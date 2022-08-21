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
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\Procurement\Dao\Models\Po;
use Modules\System\Plugins\Helper;

class PurchaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(Po $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $autonumber = Helper::autoNumber(self::$model->getTable(), self::$model->getKeyName(), 'PO' . date('Ym'), env('WEBSITE_AUTONUMBER'));
        if (!empty($this->code)) {
            $autonumber = $this->code;
        }

        $map = collect($this->detail)->map(function ($item) use ($autonumber) {
            $data_product = ProductFacades::singleRepository($item['temp_id']);
            $total = Helper::filterInput($item['temp_qty']) * Helper::filterInput($item['temp_price']) ?? 0;
            $data[PoDetailFacades::mask_po_code()] = $autonumber;
            $data[PoDetailFacades::mask_product_id()] = $item['temp_id'];
            $data[PoDetailFacades::mask_notes()] = $item['temp_desc'];
            $data[PoDetailFacades::mask_product_price()] = $data_product->mask_buy ?? '';
            $data[PoDetailFacades::mask_qty()] = Helper::filterInput($item['temp_qty']);
            $data[PoDetailFacades::mask_price()] = Helper::filterInput($item['temp_price']) ?? 0;
            $data[PoDetailFacades::mask_total()] = $total;
            return $data;
        });

        $discount_value = Helper::filterInput($this->{PoFacades::mask_discount_value()}) ?? 0;
        $total_discount = Helper::filterInput($this->{PoFacades::mask_discount()}) ?? 0;
        $total_value = Helper::filterInput($map->sum(PoDetailFacades::mask_total())) ?? 0;

        $supplier = SupplierFacades::find($this->po_supplier_id);

        $percent_pph = env('TAX_PPH', 0.5) / 100;
        $percent_ppn = env('TAX_PPN', 10) / 100;

        $total_tax = $total_dpp = $total_pph = $total_ppn = 0;
        if ($supplier && $supplier->mask_ppn == SupplierPpn::PPN) {

            $total_dpp = round($total_discount / ((env('TAX_PPN', 10) + 100) / 100));
            $total_ppn = round($total_dpp * $percent_ppn);
            $total_tax = $total_ppn;

            if ($supplier->mask_pph == SupplierPph::PPH) {
                $dpp_ppn = ((env('TAX_PPN', 10) + 100) / 100);
                $total_dpp = round($total_discount / ($dpp_ppn + $percent_pph));
                $total_ppn = round($total_dpp * $percent_ppn);
                $total_pph = round($total_dpp * $percent_pph);
                $total_tax = $total_pph + $total_pph;
            }
        }

        $total_summary = $total_value - $discount_value;

        $this->merge([
            PoFacades::getKeyName() => $autonumber,
            PoFacades::mask_value() => $total_value,
            PoFacades::mask_tax() => $total_tax,
            PoFacades::mask_pph() => $total_pph,
            PoFacades::mask_ppn() => $total_ppn,
            PoFacades::mask_dpp() => $total_dpp,
            PoFacades::mask_discount() => $total_discount,
            PoFacades::mask_discount_value() => $discount_value,
            PoFacades::mask_total() => $total_summary,
            'detail' => array_values($map->toArray()),
        ]);
    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                PoFacades::mask_supplier_id() => 'required',
                'detail' => 'required',
            ];
        }
        return [];
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
