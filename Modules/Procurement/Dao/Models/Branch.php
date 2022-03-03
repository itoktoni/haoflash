<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Branch extends Model
{
    use SoftDeletes, Userstamps;
    
    protected $table = 'branch';
    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'branch_id',
        'branch_name',
        'branch_description',
        'branch_address',
        'branch_phone',
        'branch_email',
    ];

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'branch_name' => 'required|min:3',
    ];

    const CREATED_AT = 'branch_created_at';
    const UPDATED_AT = 'branch_updated_at';
    const DELETED_AT = 'branch_deleted_at';

    const CREATED_BY = 'branch_created_by';
    const UPDATED_BY = 'branch_updated_by';
    const DELETED_BY = 'branch_deleted_by';

    public $searching = 'branch_name';

    public $datatable = [
        'branch_id' => [false => 'Code', 'width' => 50],
        'branch_name' => [true => 'Name'],
        'branch_phone' => [true => 'Phone'],
        'branch_email' => [true => 'Email'],
    ];
    
    public $status    = [
        '1' => ['Enable', 'info'],
        '0' => ['Disable', 'default'],
    ];

    public function mask_name()
    {
        return 'branch_name';
    }

    public function setMaskNameAttribute($value)
    {
        $this->attributes[$this->mask_name()] = $value;
    }

    public function getMaskNameAttribute()
    {
        return $this->{$this->mask_name()};
    }
}
