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
use Modules\Procurement\Dao\Facades\RoDetailFacades;
use Modules\Procurement\Dao\Facades\RoFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Modules\System\Plugins\Helper;
use Wildside\Userstamps\Userstamps;

class Delivery extends Model
{
    use SoftDeletes, Userstamps, PowerJoins, FilterQueryString;

    protected $table = 'ro';
    protected $primaryKey = 'ro_code';
    protected $primaryType = 'string';

    protected $fillable = [
        'ro_code',
        'ro_created_at',
        'ro_updated_at',
        'ro_invoiced_at',
        'ro_received_at',
        'ro_deleted_at',
        'ro_created_by',
        'ro_invoiced_by',
        'ro_received_by',
        'ro_updated_by',
        'ro_deleted_by',
        'ro_branch_id',
        'ro_date_order',
        'ro_status',
        'ro_notes',
        'ro_sum_qty',
        'ro_sum_value',
        'ro_sum_tax',
        'ro_sum_discount',
        'ro_sum_total',
    ];

    // public $with = ['has_detail', 'has_supplier'];

    protected $filters = [
        'ro_branch_id',
    ];

    public $timestamps = true;
    public $incrementing = false;
    public $rules = [
        'ro_code' => 'required|min:3',
    ];

    const CREATED_AT = 'ro_created_at';
    const UPDATED_AT = 'ro_updated_at';
    const DELETED_AT = 'ro_deleted_at';

    const CREATED_BY = 'ro_created_by';
    const UPDATED_BY = 'ro_updated_by';
    const DELETED_BY = 'ro_deleted_by';

    public $searching = 'ro_code';
    public $datatable = [
        'ro_code' => [true => 'Request Code', 'width' => 100],
        'ro_date_order' => [true => 'Date', 'width' => 60],
        'branch_name' => [true => 'Branch'],
        'ro_updated_at' => [true => 'Last At', 'width' => 130],
        'ro_sum_value' => [false => 'Value', 'width' => 80],
        'ro_sum_total' => [true => 'Total', 'width' => 80],
        'ro_status' => [true => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
    ];

    protected $casts = [
        'ro_created_at' => 'datetime:Y-m-d H:i:s',
        'ro_updated_at' => 'datetime:Y-m-d H:i:s',
        'ro_status' => 'integer',
        'ro_payment' => 'integer',
    ];

    public function mask_status()
    {

        return 'ro_status';
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
        return 'ro_last_status';
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
        return 'ro_payment';
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
        return 'ro_branch_id';
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
        return 'ro_sum_total';
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
        return 'ro_notes';
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
        return 'ro_sum_value';
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
        return 'ro_sum_discount';
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
        return 'ro_sum_tax';
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
        return 'ro_sum_dpp';
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
        return 'ro_sum_ppn';
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
        return 'ro_sum_pph';
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
        return $this->hasMany(RoDetail::class, RoDetailFacades::mask_ro_code(), RoFacades::getKeyName());
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

    public static function boot()
    {
        parent::boot();
    }
}
