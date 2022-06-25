<?php

namespace Modules\Finance\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Kirschbaum\PowerJoins\PowerJoins;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Modules\Finance\Dao\Enums\PaymentMethod;
use Modules\Finance\Dao\Enums\PaymentModel;
use Modules\Finance\Dao\Enums\PaymentStatus;
use Modules\Finance\Dao\Enums\PaymentType;
use Modules\Finance\Events\PaymentApproveEvent;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Models\Po;
use Modules\System\Dao\Facades\TeamFacades;
use Modules\System\Plugins\Helper;
use Modules\Transaction\Dao\Facades\SoFacades;
use Modules\Transaction\Dao\Models\Sales;
use Modules\Transaction\Dao\Models\So;
use Wildside\Userstamps\Userstamps;

class Payment extends Model
{
    use SoftDeletes, Userstamps, PowerJoins, FilterQueryString;

    protected $table = 'payment';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'payment_id',
        'payment_model',
        'payment_status',
        'payment_bank_from',
        'payment_bank_to',
        'payment_reference',
        'payment_person',
        'payment_file',
        'payment_voucher',
        'payment_account',
        'payment_notes_user',
        'payment_notes_approve',
        'payment_value_user',
        'payment_value_approve',
        'payment_deleted_by',
        'payment_created_by',
        'payment_updated_by',
        'payment_created_at',
        'payment_updated_at',
        'payment_approved_at',
        'payment_deleted_at',
        'payment_date',
        'payment_type',
        'payment_method',
    ];

    protected $filters = [
        'payment_status',
        'payment_type',
        'payment_model',
        'payment_created_by',
    ];

    // public $with = ['has_order'];

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'payment_date' => 'required',
        'payment_bank_from' => 'required',
        'payment_bank_to' => 'required',
        'payment_model' => 'required',
        'payment_status' => 'required',
        'payment_person' => 'required',
        'payment_value_approve' => 'required',
    ];

    protected $casts = [
        'payment_status' => 'integer',
        'payment_model' => 'integer',
        'payment_type' => 'integer',
        'payment_method' => 'integer',
    ];

    const CREATED_AT = 'payment_created_at';
    const UPDATED_AT = 'payment_updated_at';
    const APPROVED_AT = 'payment_approved_at';
    const DELETED_AT = 'payment_deleted_at';
    const CREATED_BY = 'payment_created_by';
    const UPDATED_BY = 'payment_updated_by';
    const DELETED_BY = 'payment_deleted_by';

    public $searching = 'payment_person';
    public $datatable = [
        'payment_id' => [true => 'Code', 'width' => 30],
        'payment_model' => [true => 'Model', 'width' => 100, 'class' => 'text-center'],
        'payment_bank_from' => [true => 'From', 'width' => 50],
        'payment_bank_to' => [true => 'To', 'width' => 50],
        'payment_date' => [true => 'Date', 'width' => 60, 'class' => 'text-center'],
        'payment_reference' => [true => 'Reference', 'width' => 100],
        'payment_person' => [true => 'Person'],
        'payment_value_user' => [true => 'Value', 'width' => 50],
        'payment_value_approve' => [true => 'Approve', 'width' => 50],
        'payment_type' => [false => 'Type', 'width' => 50, 'class' => 'text-center'],
        'payment_created_by' => [false => 'Type', 'width' => 50, 'class' => 'text-center'],
        'payment_voucher' => [false => 'Type', 'width' => 50, 'class' => 'text-center'],
        'payment_status' => [true => 'Status', 'width' => 70, 'class' => 'text-center'],
    ];

    public function mask_model()
    {
        return 'payment_model';
    }

    public function setMaskModelAttribute($value)
    {
        $this->attributes[$this->mask_model()] = $value;
    }

    public function getMaskModelAttribute()
    {
        return $this->{$this->mask_model()};
    }

    public function mask_method()
    {
        return 'payment_method';
    }

    public function setMaskMethodAttribute($value)
    {
        $this->attributes[$this->mask_method()] = $value;
    }

    public function getMaskMethodAttribute()
    {
        return $this->{$this->mask_method()};
    }

    public function getMaskMethodNameAttribute()
    {
        return PaymentMethod::getDescription($this->{$this->mask_method()});
    }

    public function mask_from()
    {
        return 'payment_bank_from';
    }

    public function setMaskFromAttribute($value)
    {
        $this->attributes[$this->mask_from()] = $value;
    }

    public function getMaskFromAttribute()
    {
        return $this->{$this->mask_from()};
    }

    public function mask_to()
    {
        return 'payment_bank_to';
    }

    public function setMaskToAttribute($value)
    {
        $this->attributes[$this->mask_to()] = $value;
    }

    public function getMaskToAttribute()
    {
        return $this->{$this->mask_to()};
    }

    public function mask_reference()
    {
        return 'payment_reference';
    }

    public function setMaskReferenceAttribute($value)
    {
        $this->attributes[$this->mask_reference()] = $value;
    }

    public function getMaskReferenceAttribute()
    {
        return $this->{$this->mask_reference()};
    }

    public function mask_person()
    {
        return 'payment_person';
    }

    public function setMaskPersonAttribute($value)
    {
        $this->attributes[$this->mask_person()] = $value;
    }

    public function getMaskPersonAttribute()
    {
        return $this->{$this->mask_person()};
    }

    public function mask_account()
    {
        return 'payment_account';
    }

    public function setMaskAccountAttribute($value)
    {
        $this->attributes[$this->mask_account()] = $value;
    }

    public function getMaskAccountAttribute()
    {
        return $this->{$this->mask_account()};
    }

    public function mask_type()
    {
        return 'payment_type';
    }

    public function setMaskTypeAttribute($value)
    {
        $this->attributes[$this->mask_type()] = $value;
    }

    public function getMaskTypeAttribute()
    {
        return $this->{$this->mask_type()};
    }

    public function mask_status()
    {
        return 'payment_status';
    }

    public function setMaskStatusAttribute($value)
    {
        $this->attributes[$this->mask_status()] = $value;
    }

    public function getMaskStatusAttribute()
    {
        return $this->{$this->mask_status()};
    }

    public function mask_amount()
    {
        return 'payment_value_user';
    }

    public function setMaskAmountAttribute($value)
    {
        $this->attributes[$this->mask_amount()] = $value;
    }

    public function getMaskAmountAttribute()
    {
        return $this->{$this->mask_amount()};
    }

    public function getMaskAmountFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_amount()});
    }

    public function mask_approve()
    {
        return 'payment_value_approve';
    }

    public function setMaskApproveAttribute($value)
    {
        $this->attributes[$this->mask_approve()] = $value;
    }

    public function getMaskApproveAttribute()
    {
        return $this->{$this->mask_approve()};
    }

    public function getMaskApproveFormatAttribute()
    {
        return Helper::createRupiah($this->{$this->mask_approve()});
    }

    public function mask_file()
    {
        return 'payment_file';
    }

    public function setMaskFileAttribute($value)
    {
        $this->attributes[$this->mask_file()] = $value;
    }

    public function getMaskFileAttribute()
    {
        return $this->{$this->mask_file()};
    }

    public function mask_voucher()
    {
        return 'payment_voucher';
    }

    public function setMaskVoucherAttribute($value)
    {
        $this->attributes[$this->mask_voucher()] = $value;
    }

    public function getMaskVoucherAttribute()
    {
        return $this->{$this->mask_voucher()};
    }

    public function mask_date()
    {
        return 'payment_date';
    }

    public function setMaskDateAttribute($value)
    {
        $this->attributes[$this->mask_date()] = $value;
    }

    public function getMaskDateAttribute()
    {
        return $this->{$this->mask_date()};
    }

    public function mask_notes_user()
    {
        return 'payment_notes_user';
    }

    public function setMaskNotesUserAttribute($value)
    {
        $this->attributes[$this->mask_notes_user()] = $value;
    }

    public function getMaskNotesUserAttribute()
    {
        return $this->{$this->mask_notes_user()};
    }

    public function mask_notes_approve()
    {
        return 'payment_notes_approve';
    }

    public function setMaskNotesApproveAttribute($value)
    {
        $this->attributes[$this->mask_notes_approve()] = $value;
    }

    public function getMaskNotesApproveAttribute()
    {
        return $this->{$this->mask_notes_approve()};
    }


    public function mask_created_by()
    {
        return 'payment_notes_approve';
    }

    public function setMaskCreatedByAttribute($value)
    {
        $this->attributes[$this->mask_created_by()] = $value;
    }

    public function getMaskCreatedByAttribute()
    {
        return $this->{$this->mask_created_by()};
    }

    public function getMaskCreatedNameAttribute()
    {
        return $this->{$this->mask_created_by()};
    }

    public function has_po()
    {
        return $this->hasOne(Po::class, PoFacades::getKeyName(), $this->mask_reference());
    }

    public function has_user()
    {
        return $this->hasOne(User::class, TeamFacades::getKeyName(), $this->getCreatedByColumn());
    }

    public static function boot()
    {
        parent::saving(function ($model) {
            $file_name = 'file';
            if (request()->has($file_name)) {
                $file = request()->file($file_name);
                $name = Helper::uploadImage($file, Helper::getTemplate(__CLASS__));
                $model->mask_file = $name;
            }

            if ($model->mask_model == PaymentModel::PaymentPurchase && $model->mask_reference) {
                $po = PoFacades::find($model->mask_reference);
                if ($po) {

                    $po->mask_payment = PurchasePayment::instalment;

                    $total = $po->mask_total;
                    $payment = $po->has_payment->sum($model->mask_approve());

                    if (($payment + $model->mask_approve) >= $total) {

                        $po->mask_payment = PurchasePayment::Paid;
                    }

                    $po->save();

                    $po->update([
                        PoFacades::getUpdatedAtColumn() => date('Y-m-d H:i:s')
                    ]);
                }


            }
        });

        parent::creating(function ($model) {

            $model->payment_value_approve = Helper::filterInput($model->payment_value_approve);
            $model->mask_voucher = Helper::autoNumber($model->getTable(), $model->mask_voucher(), 'V' . date('Ym'), env('WEBSITE_AUTONUMBER'));
        });

        parent::boot();
    }
}
