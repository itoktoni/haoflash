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
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Modules\System\Plugins\Helper;
use Wildside\Userstamps\Userstamps;

class Po extends Model
{
    use SoftDeletes, Userstamps, PowerJoins, FilterQueryString;

    protected $table = 'po';
    protected $primaryKey = 'po_code';
    protected $primaryType = 'string';

    protected $fillable = [
        'po_code',
        'po_created_at',
        'po_updated_at',
        'po_invoiced_at',
        'po_received_at',
        'po_deleted_at',
        'po_created_by',
        'po_invoiced_by',
        'po_received_by',
        'po_updated_by',
        'po_deleted_by',
        'po_supplier_id',
        'po_invoice',
        'po_date_order',
        'po_status',
        'po_payment',
        'po_notes',
        'po_discount_name',
        'po_discount_value',
        'po_sum_value',
        'po_sum_discount',
        'po_sum_total',
        'po_sum_tax',
        'po_sum_dpp',
        'po_sum_ppn',
        'po_sum_pph',
        'po_last_status',
    ];

    // public $with = ['has_detail', 'has_supplier'];

    protected $filters = [
        'po_supplier_id',
        'po_customer_id'
    ];

    public $timestamps = true;
    public $incrementing = false;
    public $rules = [
        'po_code' => 'required|min:3',
    ];

    const CREATED_AT = 'po_created_at';
    const UPDATED_AT = 'po_updated_at';
    const DELETED_AT = 'po_deleted_at';

    const CREATED_BY = 'po_created_by';
    const UPDATED_BY = 'po_updated_by';
    const DELETED_BY = 'po_deleted_by';

    public $searching = 'po_code';
    public $datatable = [
        'po_code' => [true => 'Purchase Code', 'width' => 100],
        'supplier_name' => [true => 'Supplier Name'],
        'po_date_order' => [true => 'Date', 'width' => 60],
        'po_updated_at' => [false => 'Last At', 'width' => 50],
        'name' => [true => 'Last By', 'width' => 60],
        'po_sum_value' => [false => 'Value', 'width' => 80],
        'po_sum_dpp' => [true => 'DPP', 'width' => 50],
        'po_sum_ppn' => [true => 'PPN', 'width' => 50],
        'po_sum_pph' => [true => 'PPH', 'width' => 50],
        'po_sum_total' => [true => 'Total', 'width' => 70],
        'po_payment' => [true => 'Payment', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
        'po_status' => [true => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
    ];

    protected $casts = [
        'po_created_at' => 'datetime:Y-m-d H:i:s',
        'po_updated_at' => 'datetime:Y-m-d H:i:s',
        'po_status' => 'integer',
        'po_payment' => 'integer',
    ];

    public function mask_status()
    {

        return 'po_status';
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
        return 'po_last_status';
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
        return 'po_payment';
    }

    public function setMaskPaymentAttribute($value)
    {
        $this->attributes[$this->mask_payment()] = $value;
    }

    public function getMaskPaymentAttribute()
    {
        return $this->{$this->mask_payment()};
    }

    public function mask_supplier_id()
    {
        return 'po_supplier_id';
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
        return $this->has_supplier->supplier_name ?? '';
    }

    public function mask_total()
    {
        return 'po_sum_total';
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
        return 'po_notes';
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
        return 'po_sum_value';
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
        return 'po_sum_discount';
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
        return 'po_sum_tax';
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
        return 'po_sum_dpp';
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
        return 'po_sum_ppn';
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
        return 'po_sum_pph';
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
        return $this->hasMany(PoDetail::class, PoDetailFacades::mask_po_code(), PoFacades::getKeyName());
    }

    public function has_user()
    {
        return $this->hasone(User::class, TeamFacades::getKeyName(), self::UPDATED_BY);
    }

    public function has_supplier()
    {
        return $this->hasone(Supplier::class, SupplierFacades::getKeyName(), $this->mask_supplier_id());
    }

    public function has_payment()
    {
        return $this->hasMany(Payment::class, PaymentFacades::mask_reference(), $this->getKeyName());
    }

    public static function boot()
    {
        parent::creating(function ($model) {
            $model->mask_payment = PurchasePayment::Unpaid;
        });

        parent::updated(function($model){
            // $model-> = $model->mask_status;
        });

        parent::boot();
    }


}
