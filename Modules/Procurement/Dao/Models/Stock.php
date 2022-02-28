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
    ];

    public function mask_code()
    {
        return 'stock_code';
    }

    public function setCodeAttribute($value)
    {
        $this->attributes[$this->mask_code()] = $value;
    }

    public function getCodeAttribute()
    {
        return $this->{$this->mask_code()};
    }

    public function mask_receive_code()
    {
        return 'stock_po_receive_code';
    }

    public function setReceiveCodeAttribute($value)
    {
        $this->attributes[$this->mask_receive_code()] = $value;
    }

    public function getReceiveCodeAttribute()
    {
        return $this->{$this->mask_receive_code()};
    }

    public function branch_id()
    {
        return 'stock_branch_id';
    }

    public function setBranchIdAttribute($value)
    {
        $this->attributes[$this->branch_id()] = $value;
    }

    public function getBranchIdAttribute()
    {
        return $this->{$this->branch_id()};
    }

    public function product_id()
    {
        return 'stock_product_id';
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
        return 'stock_qty';
    }

    public function setQtyAttribute($value)
    {
        $this->attributes[$this->qty()] = $value;
    }

    public function getQtyAttribute()
    {
        return $this->{$this->qty()};
    }

    public function has_branch()
    {
        return $this->hasOne(Branch::class, BranchFacades::getKeyName(), $this->branch_id());
    }

    public function has_product()
    {
        return $this->hasOne(Product::class, ProductFacades::getKeyName(), $this->product_id());
    }

    public static function boot()
    {
        parent::creating(function ($model) {
            $model->stock_created_by = auth()->user()->id;
        });

        parent::boot();
    }
}
