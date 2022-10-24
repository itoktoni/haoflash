<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Procurement\Dao\Enums\SupplierPph;
use Modules\Procurement\Dao\Enums\SupplierPpn;
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
        'supplier_address',
        'supplier_pph',
        'supplier_npwp',
        'supplier_pkp',
        'supplier_bank_name',
        'supplier_bank_account',
    ];

    protected $casts = [
        'supplier_ppn' => 'integer',
        'supplier_pph' => 'integer',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = true;
    public $rules = [
        'supplier_name' => 'required|min:3',
        'supplier_phone' => 'required',
        'supplier_ppn' => 'required',
        'supplier_email' => 'required|email',
        'supplier_contact' => 'required',
        'supplier_address' => 'required',
        'supplier_description' => 'required',
        'supplier_pph' => 'required_if:supplier_ppn,1',
        'supplier_npwp' => 'numeric|required_if:supplier_ppn,1',
        'supplier_pkp' => 'numeric|required_if:supplier_ppn,1',
    ];

    public $searching = 'supplier_name';
    public $datatable = [
        'supplier_id' => [false => 'Code', 'width' => 50],
        'supplier_name' => [true => 'Nama Supplier'],
        'supplier_contact' => [true => 'PIC'],
        'supplier_phone' => [true => 'Phone'],
        'supplier_email' => [true => 'Email'],
        'supplier_ppn' => [true => 'PPN'],
        'supplier_pph' => [true => 'PPH'],
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
        $value = intval($this->{$this->mask_ppn()});
        return strtoupper(SupplierPpn::getDescription($value)) ?? '';
    }

    public function mask_pph()
    {
        return 'supplier_pph';
    }

    public function setMaskPphAttribute($value)
    {
        $this->attributes[$this->mask_pph()] = $value;
    }

    public function getMaskPphAttribute()
    {
        return $this->{$this->mask_pph()};
    }

    public function getMaskPphNameAttribute()
    {
        $value = intval($this->{$this->mask_pph()});
        return strtoupper(SupplierPph::getDescription($value)) ?? '';
    }

}
