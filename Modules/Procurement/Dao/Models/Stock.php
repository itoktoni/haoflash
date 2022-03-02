<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kirschbaum\PowerJoins\PowerJoins;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Product;
use Modules\Procurement\Dao\Facades\BranchFacades;

class Stock extends Model
{
    use PowerJoins;
    protected $table = 'stocks';
    protected $primaryKey = 'stock_id';

    protected $fillable = [
        'stock_id',
        'stock_code',
        'stock_po_code',
        'stock_po_receive_code',
        'stock_branch_id',
        'stock_product_id',
        'stock_qty',
        'stock_sell',
        'stock_buy',
        'stock_expired',
        'stock_created_by',
        'stock_updated_by',
        'stock_created_at',
        'stock_updated_at',
    ];

    const CREATED_AT = 'stock_created_at';
    const UPDATED_AT = 'stock_updated_at';
    const DELETED_AT = 'stock_deleted_at';

    const CREATED_BY = 'stock_created_by';
    const UPDATED_BY = 'stock_updated_by';
    const DELETED_BY = 'stock_deleted_by';

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'stock_branch_id' => 'required',
        'stock_product_id' => 'required',
        'stock_qty' => 'required',
    ];

    public $searching = 'stock_id';

    public $datatable = [
        'stock_id' => [false => 'Code', 'width' => 50],
        'stock_branch_id' => [false => 'Code', 'width' => 50],
        'branch_name' => [true => 'Branch', 'width' => 100],
        'product_name' => [true => 'Product'],
        'stock_qty' => [true => 'Qty', 'width' => 50],
        'stock_sell' => [true => 'Sell Price'],
        'stock_expired' => [true => 'Expired'],
    ];

    public function mask_code()
    {
        return 'stock_code';
    }

    public function setMaskCodeAttribute($value)
    {
        $this->attributes[$this->mask_code()] = $value;
    }

    public function getMaskCodeAttribute()
    {
        return $this->{$this->mask_code()};
    }

    public function mask_po_code()
    {
        return 'stock_po_code';
    }

    public function setMaskPoCodeAttribute($value)
    {
        $this->attributes[$this->mask_po_code()] = $value;
    }

    public function getMaskPoCodeAttribute()
    {
        return $this->{$this->mask_po_code()};
    }

    public function mask_receive_code()
    {
        return 'stock_po_receive_code';
    }

    public function setMaskReceiveCodeAttribute($value)
    {
        $this->attributes[$this->mask_receive_code()] = $value;
    }

    public function getMaskReceiveCodeAttribute()
    {
        return $this->{$this->mask_receive_code()};
    }

    public function mask_branch_id()
    {
        return 'stock_branch_id';
    }

    public function setMaskBranchIdAttribute($value)
    {
        $this->attributes[$this->mask_branch_id()] = $value;
    }

    public function getMaskBranchIdAttribute()
    {
        return $this->{$this->mask_branch_id()};
    }

    public function mask_product_id()
    {
        return 'stock_product_id';
    }

    public function setMaskProductIdAttribute($value)
    {
        $this->attributes[$this->mask_product_id()] = $value;
    }

    public function getMaskProductIdAttribute()
    {
        return $this->{$this->mask_product_id()};
    }

    public function mask_qty()
    {
        return 'stock_qty';
    }

    public function setMaskQtyAttribute($value)
    {
        $this->attributes[$this->mask_qty()] = $value;
    }

    public function getMaskQtyAttribute()
    {
        return $this->{$this->mask_qty()};
    }

    public function has_branch()
    {
        return $this->hasOne(Branch::class, BranchFacades::getKeyName(), $this->mask_branch_id());
    }

    public function has_product()
    {
        return $this->hasOne(Product::class, ProductFacades::getKeyName(), $this->mask_product_id());
    }

    public static function boot()
    {
        parent::creating(function ($model) {
            $model->stock_created_by = auth()->user()->id;
        });

        parent::boot();
    }
}
