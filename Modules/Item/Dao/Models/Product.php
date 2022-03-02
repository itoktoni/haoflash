<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Dao\Facades\CategoryFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\Procurement\Dao\Models\Supplier;
use Modules\System\Plugins\Helper;

class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_description',
        'product_sku',
        'product_buy',
        'product_sell',
        'product_min',
        'product_max',
        'product_price',
        'product_image',
        'product_category_id',
        'product_supplier_id',
        'product_created_at',
        'product_updated_at',
        'product_deleted_at',
        'product_created_by',
        'product_updated_by',
        'product_deleted_by',
    ];

    // public $with = ['has_supplier'];

    public $timestamps = false;
    public $incrementing = true;
    public $rules = [
        'product_name' => 'required|min:3',
        'product_category_id' => 'required',
        'product_buy' => 'required|integer',
        'product_min' => 'required|integer|min:1',
    ];

    const CREATED_AT = 'product_created_at';
    const UPDATED_AT = 'product_updated_at';
    const DELETED_AT = 'product_deleted_at';

    const CREATED_BY = 'product_created_by';
    const UPDATED_BY = 'product_updated_by';
    const DELETED_BY = 'product_deleted_by';

    protected $casts = [
        'product_buy' => 'integer',
        'product_sell' => 'integer',
        'product_price' => 'integer',
        'product_tax_code' => 'string',
    ];

    public $searching = 'product_name';
    public $datatable = [
        'product_id' => [true => 'Code', 'width' => 50],
        'product_sku' => [false => 'SKU', 'width' => 100],
        'product_supplier_id' => [false => 'Supplier'],
        'product_name' => [true => 'Name'],
        'product_buy' => [true => 'Buy', 'width' => 100],
        'product_image' => [false => 'Image', 'width' => 100, 'class' => 'text-center'],
        'product_description' => [false => 'Image'],
    ];

    public function mask_name()
    {
        return 'product_name';
    }

    public function setMaskNameAttribute($value)
    {
        $this->attributes[$this->mask_name()] = $value;
    }

    public function getMaskNameAttribute()
    {
        return $this->{$this->mask_name()};
    }

    public function mask_price()
    {
        return 'product_price';
    }

    public function setMaskPriceAttribute($value)
    {
        $this->attributes[$this->mask_price()] = $value;
    }

    public function getMaskPriceAttribute()
    {
        return $this->{$this->mask_price()};
    }

    public function getMaskPriceFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_price()});
    }

    public function mask_buy()
    {
        return 'product_buy';
    }

    public function setMaskBuyAttribute($value)
    {
        $this->attributes[$this->mask_buy()] = $value;
    }

    public function getMaskBuyAttribute()
    {
        return $this->{$this->mask_buy()};
    }

    public function getMaskBuyFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_buy()});
    }

    public function mask_sell()
    {
        return 'product_sell';
    }

    public function setMaskSellAttribute($value)
    {
        $this->attributes[$this->mask_sell()] = $value;
    }

    public function getMaskSellAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_sell()});
    }

    public function getMaskSellFormatAttribute()
    {
        return $this->{$this->mask_sell()};
    }

    public function mask_category_id()
    {
        return 'product_category_id';
    }

    public function setMaskCategoryIdAttribute($value)
    {
        $this->attributes[$this->mask_category_id()] = $value;
    }

    public function getMaskCategoryIdAttribute()
    {
        return $this->{$this->mask_category_id()};
    }

    public function mask_supplier_id()
    {
        return 'product_supplier_id';
    }

    public function setMaskSupplierIdAttribute($value)
    {
        $this->attributes[$this->mask_supplier_id()] = $value;
    }

    public function getMaskSupplierIdAttribute()
    {
        return $this->{$this->mask_supplier_id()};
    }

    public function getMaskSupplierNameAttribute()
    {
        return $this->has_supplier->mask_name ?? '';
    }

    public function mask_description()
    {
        return 'product_description';
    }

    public function setMaskDescriptionAttribute($value)
    {
        $this->attributes[$this->mask_description()] = $value;
    }

    public function getMaskDescriptionAttribute()
    {
        return $this->{$this->mask_description()};
    }

    public static function boot()
    {
        parent::saving(function ($model) {
            $file_name = 'file';
            if (request()->has($file_name)) {
                $file = request()->file($file_name);
                $name = Helper::uploadImage($file, Helper::getTemplate(__CLASS__));
                $model->product_image = $name;
            }
        });

        parent::boot();
    }

    public function has_category()
    {
        return $this->hasOne(Category::class, CategoryFacades::getKeyName(), $this->mask_category_id());
    }

    public function has_supplier()
    {
        return $this->hasOne(Supplier::class, SupplierFacades::getKeyName(), $this->mask_supplier_id());
    }
}
