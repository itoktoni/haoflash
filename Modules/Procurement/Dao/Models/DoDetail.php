<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Product;
use Modules\Procurement\Dao\Facades\DeliveryFacades;
use Modules\Procurement\Dao\Facades\DoFacades;

class DoDetail extends Model
{
    protected $table = 'ro_detail';
    protected $primaryKey = 'ro_detail_ro_code';

    protected $fillable = [
        'ro_detail_ro_code',
        'ro_detail_notes',
        'ro_detail_product_id',
        'ro_detail_product_price',
        'ro_detail_qty',
        'ro_detail_price',
        'ro_detail_total',
        'ro_detail_receive',
        'ro_detail_remaining',
    ];

    // public $with = ['has_product'];

    public $timestamps = false;
    public $incrementing = false;

    public function mask_ro_code()
    {
        return 'ro_detail_ro_code';
    }

    public function setMaskPoCodeAttribute($value)
    {
        $this->attributes[$this->mask_ro_code()] = $value;
    }

    public function getMaskPoCodeAttribute()
    {
        return $this->{$this->mask_ro_code()};
    }

    public function mask_product_id()
    {
        return 'ro_detail_product_id';
    }

    public function setMaskProductIdAttribute($value)
    {
        $this->attributes[$this->mask_product_id()] = $value;
    }

    public function getMaskProductIdAttribute()
    {
        return $this->{$this->mask_product_id()};
    }

    public function getMaskProductNameAttribute()
    {
        return $this->has_product->product_name;
    }

    public function mask_product_price()
    {
        return 'ro_detail_product_price';
    }

    public function setMaskProductPriceAttribute($value)
    {
        $this->attributes[$this->mask_product_price()] = $value;
    }

    public function getMaskProductPriceAttribute()
    {
        return $this->{$this->mask_product_price()};
    }

    public function mask_receive_price()
    {
        return 'ro_detail_receive_price';
    }

    public function setMaskReceivePriceAttribute($value)
    {
        $this->attributes[$this->mask_receive_price()] = $value;
    }

    public function getMaskReceivePriceAttribute()
    {
        return $this->{$this->mask_receive_price()};
    }


    public function mask_qty()
    {
        return 'ro_detail_qty';
    }

    public function setMaskQtyAttribute($value)
    {
        $this->attributes[$this->mask_qty()] = $value;
    }

    public function getMaskQtyAttribute()
    {
        return $this->{$this->mask_qty()};
    }

    public function mask_price()
    {
        return 'ro_detail_price';
    }

    public function setMaskPriceAttribute($value)
    {
        $this->attributes[$this->mask_price()] = $value;
    }

    public function getMaskPriceAttribute()
    {
        return $this->{$this->mask_price()};
    }

    public function mask_sent()
    {
        return 'ro_detail_sent';
    }

    public function setMaskSentAttribute($value)
    {
        $this->attributes[$this->mask_deliver()] = $value;
    }

    public function getMaskSentAttribute()
    {
        return $this->{$this->mask_sent()};
    }

    public function mask_receive()
    {
        return 'ro_detail_receive';
    }

    public function setMaskReceiveAttribute($value)
    {
        $this->attributes[$this->mask_receive()] = $value;
    }

    public function getMaskReceiveAttribute()
    {
        return $this->{$this->mask_receive()};
    }

    public function mask_notes()
    {
        return 'ro_detail_notes';
    }

    public function setMaskNotesAttribute($value)
    {
        $this->attributes[$this->mask_notes()] = $value;
    }

    public function getMaskNotesAttribute()
    {
        return $this->{$this->mask_notes()};
    }

    public function mask_total()
    {
        return 'ro_detail_total';
    }

    public function setMaskTotalAttribute($value)
    {
        $this->attributes[$this->mask_total()] = $value;
    }

    public function getMaskTotalAttribute()
    {
        return $this->{$this->mask_total()};
    }

    public function has_product()
    {
        return $this->hasOne(Product::class, ProductFacades::getKeyName(), $this->mask_product_id());
    }

    public function has_master()
    {
        return $this->hasOne(Delivery::class, DeliveryFacades::getKeyName(), $this->mask_ro_code());
    }
}
