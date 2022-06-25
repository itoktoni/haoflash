<?php

namespace Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Facades\CategoryFacades;
use Modules\System\Http\Requests\GeneralRequest;
use Modules\System\Plugins\Helper;

class ProductRequest extends GeneralRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    private $rules;
    private $model;

    public function prepareForValidation()
    {
        $this->merge([
            'product_buy' => Helper::filterInput($this->product_buy),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $category = CategoryFacades::find($this->product_category_id);
            if(!empty($category) && $category->category_type == CategoryType::Accesories && empty($this->product_sku)){

                $validator->errors()->add('product_sku', 'Accesories Part number tidak boleh kosong !');
            }
        });
    }

    public function rules()
    {
        return [
            'product_name' => 'required|min:3',
            'product_category_id' => 'required',
            'product_buy' => 'required|integer',
            'product_min' => 'required|integer|min:1',
            'product_description' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'product_category_id' => 'Category',
        ];
    }

    public function messages()
    {
        return [
            'product_category_id.required' => 'Please input Category product !'
        ];
    }
}
