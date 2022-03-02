<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Procurement\Dao\Enums\SupplierType;
use Wildside\Userstamps\Userstamps;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'supplier_id',
        'supplier_name',
        'supplier_description',
        'supplier_phone',
        'supplier_contact',
        'supplier_email',
        'supplier_ppn',
        'supplier_npwp',
        'supplier_pkp',
        'supplier_bank_name',
        'supplier_bank_account',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = true;
    public $rules = [
        'supplier_name' => 'required|min:3',
        'supplier_phone' => 'required',
        'supplier_ppn' => 'required',
        'supplier_email' => 'required',
        'supplier_npwp' => 'integer|required_if:supplier_ppn,1',
        'supplier_pkp' => 'integer|required_if:supplier_ppn,1',
    ];

    public $searching = 'supplier_name';
    public $datatable = [
        'supplier_id' => [false => 'Code', 'width' => 50],
        'supplier_name' => [true => 'Name'],
        'supplier_phone' => [true => 'Phone'],
        'supplier_email' => [true => 'Email'],
        'supplier_ppn' => [true => 'PKP/Non PKP'],
    ];

    public function mask_name()
    {
        return 'supplier_name';
    }

    public function setMaskNameAttribute($value)
    {
        $this->attributes[$this->mask_name()] = $value;
    }

    public function getMaskNameAttribute()
    {
        return $this->{$this->mask_name()};
    }

    public function mask_address()
    {
        return 'supplier_address';
    }

    public function setMaskAddressAttribute($value)
    {
        $this->attributes[$this->mask_address()] = $value;
    }

    public function getMaskAddressAttribute()
    {
        return $this->{$this->mask_address()};
    }

    public function mask_ppn()
    {
        return 'supplier_ppn';
    }

    public function setMaskPpnAttribute($value)
    {
        $this->attributes[$this->mask_ppn()] = $value;
    }

    public function getMaskPpnAttribute()
    {
        return $this->{$this->mask_ppn()};
    }

    public function getMaskPpnNameAttribute()
    {
        return SupplierType::getDescription($this->{$this->mask_ppn()}) ?? '';
    }

}
