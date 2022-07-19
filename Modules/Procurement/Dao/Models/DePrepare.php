<?php

namespace Modules\Procurement\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Modules\Finance\Dao\Facades\PaymentFacades;
use Modules\Finance\Dao\Models\Payment;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Product;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Facades\BranchFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Modules\System\Plugins\Helper;
use PhpParser\Node\Stmt\Do_;
use Wildside\Userstamps\Userstamps;

class DePrepare extends Model
{
    use SoftDeletes, Userstamps, PowerJoins, FilterQueryString;

    protected $table = 'do_prepare';
    protected $primaryKey = 'do_prepare_code';
    protected $primaryType = 'string';

    protected $fillable = [
        'do_prepare_code',
        'do_prepare_do_code',
        'do_prepare_date',
        'do_prepare_expired_date',
        'do_prepare_created_at',
        'do_prepare_updated_at',
        'do_prepare_deleted_at',
        'do_prepare_created_by',
        'do_prepare_updated_by',
        'do_prepare_qty',
        'do_prepare_prepare',
        'do_prepare_buy',
        'do_prepare_sell',
        'do_prepare_start',
        'do_prepare_end',
        'do_prepare_type',
        'do_prepare_product_id',
        'do_prepare_supplier_id',
        'do_prepare_branch_id',
        'do_prepare_expired',
    ];

    // public $with = ['has_detail', 'has_supplier'];

    protected $filters = [
        'do_prepare_supplier_id',
    ];

    public $timestamps = true;
    public $incrementing = false;

    const CREATED_AT = 'do_prepare_created_at';
    const UPDATED_AT = 'do_prepare_updated_at';
    const DELETED_AT = 'do_prepare_deleted_at';

    const CREATED_BY = 'do_prepare_created_by';
    const UPDATED_BY = 'do_prepare_updated_by';
    const DELETED_BY = 'do_prepare_deleted_by';

    public $searching = 'do_prepare_code';
    public $datatable = [
        'do_prepare_code' => [true => 'Purchase Code'],
        'supplier_name' => [true => 'Supplier Name'],
        'do_prepare_date_order' => [true => 'Date', 'width' => 60],
        'do_prepare_sum_value' => [false => 'Value', 'width' => 60],
        'do_prepare_sum_discount' => [false => 'Discount', 'width' => 60],
        'do_prepare_sum_total' => [true => 'Total', 'width' => 60],
        'do_prepare_payment' => [true => 'Payment', 'width' => 60, 'class' => 'text-center', 'status' => 'status'],
        'do_prepare_status' => [true => 'Status', 'width' => 60, 'class' => 'text-center', 'status' => 'status'],
    ];

    protected $casts = [
        'do_prepare_created_at' => 'datetime:Y-m-d',
        'do_prepare_status' => 'integer',
        'do_prepare_payment' => 'integer',
    ];

    public function mask_supplier_id()
    {
        return 'do_prepare_supplier_id';
    }

    public function setMaskSupplierIdAttribute($value)
    {
        $this->attributes[$this->mask_supplier_id()] = $value;
    }

    public function getMaskSupplierIdAttribute()
    {
        return $this->{$this->mask_supplier_id()};
    }

    public function mask_branch_id()
    {
        return 'do_prepare_branch_id';
    }

    public function setMaskBranchIDAttribute($value)
    {
        $this->attributes[$this->mask_branch_id()] = $value;
    }

    public function getMaskBranchIDAttribute()
    {
        return $this->{$this->mask_branch_id()};
    }

    public function mask_product_id()
    {
        return 'do_prepare_product_id';
    }

    public function setMaskProductIdAttribute($value)
    {
        $this->attributes[$this->mask_product_id()] = $value;
    }

    public function getMaskProductIdAttribute()
    {
        return $this->{$this->mask_product_id()};
    }

    public function mask_do_code()
    {
        return 'do_prepare_do_code';
    }

    public function setMaskPoCodeAttribute($value)
    {
        $this->attributes[$this->mask_do_code()] = $value;
    }

    public function getMaskPoCodeAttribute()
    {
        return $this->{$this->mask_do_code()};
    }

    public function mask_qty()
    {
        return 'do_prepare_qty';
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
        return 'do_prepare_buy';
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
        return 'do_prepare_sell';
    }

    public function setMaskSellAttribute($value)
    {
        $this->attributes[$this->mask_sell()] = $value;
    }

    public function getMaskSellAttribute()
    {
        return $this->{$this->mask_sell()};
    }

    public function mask_expired()
    {
        return 'do_prepare_expired';
    }

    public function setMaskExpiredAttribute($value)
    {
        $this->attributes[$this->mask_expired()] = $value;
    }

    public function getMaskExpiredAttribute()
    {
        return $this->{$this->mask_expired()};
    }

    public function mask_prepare()
    {
        return 'do_prepare_prepare';
    }

    public function setMaskReceiveAttribute($value)
    {
        $this->attributes[$this->mask_prepare()] = $value;
    }

    public function getMaskReceiveAttribute()
    {
        return $this->{$this->mask_prepare()};
    }

    public function getMaskSupplierNameAttribute()
    {
        return $this->has_supplier->supplier_name ?? '';
    }

    public function mask_created_at()
    {
        return self::CREATED_AT;
    }

    public function setMaskCreatedAtAttribute($value)
    {
        $this->attributes[$this->mask_created_at()] = $value;
    }

    public function getMaskCreatedAtAttribute()
    {
        return $this->{$this->mask_created_at()};
    }

    public function mask_created_by()
    {
        return self::CREATED_BY;
    }

    public function setMaskCreatedByAttribute($value)
    {
        $this->attributes[$this->mask_created_by()] = $value;
    }

    public function getMaskCreatedByAttribute()
    {
        return $this->{$this->mask_created_by()};
    }

    public function has_detail()
    {
        return $this->hasMany(Stock::class, StockFacades::mask_reference_code(), $this->getKeyName());
    }

    public function has_supplier()
    {
        return $this->hasone(Supplier::class, SupplierFacades::getKeyName(), $this->mask_supplier_id());
    }

    public function has_branch()
    {
        return $this->hasone(Branch::class, BranchFacades::getKeyName(), $this->mask_branch_id());
    }

    public function has_product()
    {
        return $this->hasone(Product::class, ProductFacades::getKeyName(), $this->mask_product_id());
    }

    public function has_master()
    {
        return $this->hasone(De::class, DeFacades::getKeyName(), $this->mask_do_code());
    }

    public static function boot()
    {
        parent::saving(function ($model) {
            $model->do_prepare_expired = !empty($model->do_prepare_expired) ? $model->do_prepare_expired : null;
        });

        parent::boot();
    }
}
