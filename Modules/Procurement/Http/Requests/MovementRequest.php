<?php

namespace Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Models\Movement;
use Modules\System\Plugins\Helper;

class MovementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private static $model;

    public function __construct(Movement $models)
    {
        self::$model = $models;
    }

    public function prepareForValidation()
    {
        $autonumber = Helper::autoNumber(self::$model->getTable(), self::$model->getKeyName(), 'MT' . date('Ym'), config('website.autonumber'));
        if (!empty($this->code)) {
            $autonumber = $this->code;
        }
        $map = collect($this->detail)->map(function ($item) use ($autonumber) {
            $product = new ProductRepository();
            $data_product = $product->singleRepository($item['temp_id'])->first();
            $data['procurement_movement_detail_id'] = $autonumber;
            $data['procurement_movement_detail_item_product_id'] = $item['temp_id'];
            $data['procurement_movement_detail_qty'] = Helper::filterInput($item['temp_qty']);
            return $data;
        });

        $this->merge([
            'procurement_movement_id' => $autonumber,
            'detail' => array_values($map->toArray()),
        ]);

    }

    public function rules()
    {
        if (request()->isMethod('POST')) {
            return [
                'procurement_movement_id' => 'required',
                'detail' => 'required',
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
