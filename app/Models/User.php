<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Master\Dao\Facades\CompanyFacades;
use Modules\Master\Dao\Models\Company;
use Modules\System\Dao\Enums\ActiveStatus;
use Modules\System\Dao\Facades\TeamFacades;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    protected $table      = 'users'; //nama table
    protected $primaryKey = 'id'; //nama primary key
    protected $fillable    = [
        'id',
        'name',
        'email',
        'password',
        'username',
        'photo',
        'group_user',
        'remember_token',
        'address',
        'site',
        'birth',
        'place_birth',
        'notes',
        'phone',
        'deleted_at',
        'created_at',
        'updated_at',
        'active',
        'api_token',
        'token',
        'email_verified_at',
        'company',
        'supplier',
    ];

    // public $with = ['has_company'];

    protected $guarded = [];

    public $rules = [
        'username'  => 'required|min:3|unique:users',
        'email'      => 'required|email|unique:users',
        'group_user' => 'required',
    ];

    public $timestamps    = true; //timestamp will true
    public $incrementing  = true; //make creating id use lastcode

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $searching     = 'name'; //searching default
    public $status = [
        '1' => ['Active', 'primary'],
        '0' => ['Pasive', 'danger'],
    ];

    public $datatable = [
        'id'            => [false => 'ID User'],
        'username'      => [true => 'Username'],
        'name'          => [true => 'Name'],
        'email'         => [false => 'Email'],
        'group_user'    => [true => 'Group User'],
        'company'       => [true => 'Company'],
        'active'        => [true => 'Active', 'width' => '100', 'class' => 'text-center'],
    ];

    public function scopeById($query, $id)
    {
        return $query->where($this->primaryKey, $id);
    }

    //mask enable

    public function mask_active()
    {
        return 'active';
    }

    public function setMaskActiveAttribute($value)
    {
        $this->attributes[$this->mask_active()] = $value;
    }

    public function getMaskActiveAttribute()
    {
        return $this->{$this->mask_active()};
    }

    public function getMaskActiveName($value)
    {
        return ActiveStatus::getDescription($this->mask_active);
    }

    public function mask_group_user()
    {
        return 'group_user';
    }

    public function setMaskGroupUserAttribute($value)
    {
        $this->attributes[$this->mask_group_user()] = $value;
    }

    public function getMaskGroupUserAttribute()
    {
        return $this->{$this->mask_group_user()};
    }

    public function mask_company()
    {
        return 'company';
    }

    public function setMaskCompanyAttribute($value)
    {
        $this->attributes[$this->mask_company()] = $value;
    }

    public function getMaskCompanyAttribute()
    {
        return $this->{$this->mask_company()};
    }

    public function getMaskCompanyNameAttribute()
    {
        return $this->has_company->mask_name ?? '';
    }
}
