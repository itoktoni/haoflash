<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Product;

class MovementDetail extends Model
{
    protected $table = 'procurement_movement_detail';
    protected $primaryKey = 'procurement_movement_detail_id';

    protected $fillable = [
        'procurement_movement_detail_id',
        'procurement_movement_detail_item_product_id',
        'procurement_movement_detail_qty',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = false;
    
    public function detail_id()
    {
        return 'procurement_movement_detail_id';
    }

    public function setDetailIdAttribute($value)
    {
        $this->attributes[$this->detail_id()] = $value;
    }

    public function getDetailIdAttribute()
    {
        return $this->{$this->detail_id()};
    }    

    public function product_id()
    {
        return 'procurement_movement_detail_item_product_id';
    }

    public function setProductIdAttribute($value)
    {
        $this->attributes[$this->product_id()] = $value;
    }

    public function getProductIdAttribute()
    {
        return $this->{$this->product_id()};
    }

    public function qty()
    {
        return 'procurement_movement_detail_qty';
    }

    public function setQtyAttribute($value)
    {
        $this->attributes[$this->qty()] = $value;
    }

    public function getQtyAttribute()
    {
        return $this->{$this->qty()};
    }

    public function product()
    {
        return $this->hasOne(Product::class, ProductFacades::getKeyName(), $this->product_id());
    }
}
