<?php

namespace Modules\Procurement\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Modules\Finance\Dao\Facades\PaymentFacades;
use Modules\Finance\Dao\Models\Payment;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Facades\BranchFacades;
use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\DeReceiveFacades;
use Modules\Procurement\Dao\Facades\RoDetailFacades;
use Modules\Procurement\Dao\Facades\RoFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Modules\System\Plugins\Helper;
use Wildside\Userstamps\Userstamps;

class De extends Model
{
    use SoftDeletes, Userstamps, PowerJoins, FilterQueryString;

    protected $table = 'do';
    protected $primaryKey = 'do_code';
    protected $primaryType = 'string';

    protected $fillable = [
        'do_code',
        'do_created_at',
        'do_updated_at',
        'do_invoiced_at',
        'do_received_at',
        'do_deleted_at',
        'do_created_by',
        'do_invoiced_by',
        'do_received_by',
        'do_updated_by',
        'do_deleted_by',
        'do_branch_id',
        'do_request_id',
        'do_date_order',
        'do_status',
        'do_notes',
        'do_sum_qty',
        'do_sum_value',
        'do_sum_tax',
        'do_sum_discount',
        'do_sum_total',
    ];

    // public $with = ['has_detail', 'has_supplier'];

    protected $filters = [
        'do_branch_id',
    ];

    public $timestamps = true;
    public $incrementing = false;
    public $rules = [
        'do_code' => 'required|min:3',
    ];

    const CREATED_AT = 'do_created_at';
    const UPDATED_AT = 'do_updated_at';
    const DELETED_AT = 'do_deleted_at';

    const CREATED_BY = 'do_created_by';
    const UPDATED_BY = 'do_updated_by';
    const DELETED_BY = 'do_deleted_by';

    public $searching = 'do_code';
    public $datatable = [
        'do_code' => [true => 'Delivery Code', 'width' => 100],
        'do_date_order' => [true => 'Date', 'width' => 60],
        'branch_name' => [true => 'Branch'],
        'do_updated_at' => [true => 'Last At', 'width' => 130],
        'do_sum_value' => [false => 'Value', 'width' => 80],
        'do_sum_total' => [true => 'Total', 'width' => 80],
        'do_status' => [true => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
    ];

    protected $casts = [
        'do_created_at' => 'datetime:Y-m-d H:i:s',
        'do_updated_at' => 'datetime:Y-m-d H:i:s',
        'do_status' => 'integer',
        'do_payment' => 'integer',
    ];

    public function mask_status()
    {
        return 'do_status';
    }

    public function setMaskStatusAttribute($value)
    {
        $this->attributes[$this->mask_status()] = $value;
    }

    public function getMaskStatusAttribute()
    {
        return $this->{$this->mask_status()};
    }

    public function mask_last_status()
    {
        return 'do_last_status';
    }

    public function setLastMaskStatusAttribute($value)
    {
        $this->attributes[$this->mask_last_status()] = $value;
    }

    public function getLastMaskStatusAttribute()
    {
        return $this->{$this->mask_last_status()};
    }

    public function mask_payment()
    {
        return 'do_payment';
    }

    public function setMaskPaymentAttribute($value)
    {
        $this->attributes[$this->mask_payment()] = $value;
    }

    public function getMaskPaymentAttribute()
    {
        return $this->{$this->mask_payment()};
    }

    public function mask_branch_id()
    {
        return 'do_branch_id';
    }

    public function setMaskBranchIdAttribute($value)
    {
        $this->attributes[$this->mask_branch_id()] = $value;
    }

    public function getMaskBranchIdAttribute()
    {
        return $this->{$this->mask_branch_id()};
    }

    public function getMaskBranchNameAttribute()
    {
        return $this->has_branch->branch_name ?? '';
    }

    public function mask_total()
    {
        return 'do_sum_total';
    }

    public function setMaskTotalAttribute($value)
    {
        $this->attributes[$this->mask_total()] = $value;
    }

    public function getMaskTotalAttribute()
    {
        return $this->{$this->mask_total()};
    }

    public function getMaskTotalRupiahAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_total()});
    }

    public function mask_notes()
    {
        return 'do_notes';
    }

    public function setMaskNotesAttribute($value)
    {
        $this->attributes[$this->mask_notes()] = $value;
    }

    public function getMaskNotesAttribute()
    {
        return $this->{$this->mask_notes()};
    }

    public function getMaskTotalFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_total()});
    }

    public function mask_value()
    {
        return 'do_sum_value';
    }

    public function setMaskValueAttribute($value)
    {
        $this->attributes[$this->mask_value()] = $value;
    }

    public function getMaskValueAttribute()
    {
        return $this->{$this->mask_value()};
    }

    public function getMaskValueFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_value()});
    }

    public function mask_discount()
    {
        return 'do_sum_discount';
    }

    public function setMaskDiscountAttribute($value)
    {
        $this->attributes[$this->mask_discount()] = $value;
    }

    public function getMaskDiscountAttribute()
    {
        return $this->{$this->mask_discount()};
    }

    public function getMaskDiscountFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_discount()});
    }

    public function mask_tax()
    {
        return 'do_sum_tax';
    }

    public function setMaskTaxAttribute($value)
    {
        $this->attributes[$this->mask_tax()] = $value;
    }

    public function getMaskTaxAttribute()
    {
        return $this->{$this->mask_tax()};
    }

    public function getMaskTaxFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_tax()});
    }

    public function mask_dpp()
    {
        return 'do_sum_dpp';
    }

    public function setMaskDppAttribute($value)
    {
        $this->attributes[$this->mask_dpp()] = $value;
    }

    public function getMaskDppAttribute()
    {
        return $this->{$this->mask_dpp()};
    }

    public function getMaskDppFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_dpp()});
    }

    public function mask_ppn()
    {
        return 'do_sum_ppn';
    }

    public function setMaskPpnAttribute($value)
    {
        $this->attributes[$this->mask_ppn()] = $value;
    }

    public function getMaskPpnAttribute()
    {
        return $this->{$this->mask_ppn()};
    }

    public function getMaskPpnFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_ppn()});
    }

    public function mask_pph()
    {
        return 'do_sum_pph';
    }

    public function setMaskPphAttribute($value)
    {
        $this->attributes[$this->mask_pph()] = $value;
    }

    public function getMaskPphAttribute()
    {
        return $this->{$this->mask_pph()};
    }

    public function getMaskPphFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_pph()});
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
        return $this->hasMany(DeDetail::class, DeDetailFacades::mask_do_code(), DeFacades::getKeyName());
    }

    public function has_user()
    {
        return $this->hasone(User::class, TeamFacades::getKeyName(), self::UPDATED_BY);
    }

    public function has_branch()
    {
        return $this->hasone(Branch::class, BranchFacades::getKeyName(), $this->mask_branch_id());
    }

    public function has_payment()
    {
        return $this->hasMany(Payment::class, PaymentFacades::mask_reference(), $this->getKeyName());
    }

    public function has_prepare()
    {
        return $this->hasMany(DePrepare::class, DePrepareFacades::mask_do_code(), $this->getKeyName());
    }

    public function has_stock_prepare()
    {
        return $this->hasMany(Stock::class, StockFacades::mask_primary_code(), $this->getKeyName());
    }

    public function has_receive()
    {
        return $this->hasMany(DeReceive::class, DeReceiveFacades::mask_do_code(), $this->getKeyName());
    }

    public static function boot()
    {
        parent::boot();
    }
}
