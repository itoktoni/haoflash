<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Product;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\DeliveryFacades;
use Modules\Procurement\Dao\Facades\DoFacades;

class DeDetail extends Model
{
    protected $table = 'do_detail';
    protected $primaryKey = 'do_detail_do_code';

    protected $fillable = [
        'do_detail_do_code',
        'do_detail_notes',
        'do_detail_product_id',
        'do_detail_product_price',
        'do_detail_qty',
        'do_detail_price',
        'do_detail_total',
        'do_detail_receive',
        'do_detail_prepare',
        'do_detail_key',
        'do_detail_expired',
    ];

    // public $with = ['has_product'];

    public $timestamps = false;
    public $incrementing = false;

    public function mask_do_code()
    {
        return 'do_detail_do_code';
    }

    public function setMaskPoCodeAttribute($value)
    {
        $this->attributes[$this->mask_do_code()] = $value;
    }

    public function getMaskPoCodeAttribute()
    {
        return $this->{$this->mask_do_code()};
    }

    public function mask_key()
    {
        return 'do_detail_key';
    }

    public function setMaskKeyAttribute($value)
    {
        $this->attributes[$this->mask_key()] = $value;
    }

    public function getMaskKeyAttribute()
    {
        return $this->{$this->mask_key()};
    }

    public function mask_expired()
    {
        return 'do_detail_expired';
    }

    public function setMaskExpiredAttribute($value)
    {
        $this->attributes[$this->mask_expired()] = $value;
    }

    public function getMaskExpiredAttribute()
    {
        return $this->{$this->mask_expired()};
    }

    public function mask_product_id()
    {
        return 'do_detail_product_id';
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
        return 'do_detail_product_price';
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
        return 'do_detail_receive_price';
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
        return 'do_detail_qty';
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
        return 'do_detail_product_price';
    }

    public function setMaskPriceAttribute($value)
    {
        $this->attributes[$this->mask_price()] = $value;
    }

    public function getMaskPriceAttribute()
    {
        return $this->{$this->mask_price()};
    }

    public function mask_sell()
    {
        return 'do_detail_price';
    }

    public function setMaskSellAttribute($value)
    {
        $this->attributes[$this->mask_sell()] = $value;
    }

    public function getMaskSellAttribute()
    {
        return $this->{$this->mask_sell()};
    }

    public function mask_sent()
    {
        return 'do_detail_sent';
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
        return 'do_detail_receive';
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
        return 'do_detail_notes';
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
        return 'do_detail_total';
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
        return $this->hasOne(De::class, DeFacades::getKeyName(), $this->mask_do_code());
    }
}
