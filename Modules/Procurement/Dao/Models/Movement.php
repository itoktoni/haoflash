<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;
use Modules\Procurement\Dao\Facades\BranchFacades;
use Modules\Procurement\Dao\Facades\MovementFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\Procurement\Dao\Presenteres\MovementPresenter;
use Wildside\Userstamps\Userstamps;

class Movement extends Model
{
    use SoftDeletes, Userstamps, PowerJoins;

    protected $table = 'procurement_movement';
    protected $primaryKey = 'procurement_movement_id';

    protected $fillable = [
        'procurement_movement_id',
        'procurement_movement_created_at',
        'procurement_movement_created_by',
        'procurement_movement_updated_at',
        'procurement_movement_updated_by',
        'procurement_movement_deleted_at',
        'procurement_movement_deleted_by',
        'procurement_movement_date',
        'procurement_movement_status',
        'procurement_movement_notes',
        'procurement_movement_from_id',
        'procurement_movement_to_id',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = false;
    public $rules = [
        'procurement_movement_id' => 'required|min:3',
    ];

    const CREATED_AT = 'procurement_movement_created_at';
    const UPDATED_AT = 'procurement_movement_updated_at';
    const DELETED_AT = 'procurement_movement_deleted_at';

    const CREATED_BY = 'procurement_movement_created_by';
    const UPDATED_BY = 'procurement_movement_updated_by';
    const DELETED_BY = 'procurement_movement_deleted_by';

    public $searching = 'procurement_movement_id';
    public $datatable = [
        'procurement_movement_id' => [false => 'Code'],
        'procurement_movement_created_at' => [true => 'Created At'],
        'procurement_movement_date' => [true => 'Movement Date'],
        'procurement_movement_from_id' => [true => 'Movement From'],
        'procurement_movement_to_id' => [true => 'Movement to'],
        'procurement_movement_status' => [true => 'Status', 'width' => 100, 'class' => 'text-center'],
    ];

    protected $casts = [
        'procurement_movement_date' => 'datetime:Y-m-d',
        'procurement_movement_created_at' => 'datetime:Y-m-d',
    ];

    public $status    = [
        '1' => ['Create', 'info'],
        '2' => ['Transfer', 'default'],
        '3' => ['Finish', 'success'],
        '4' => ['Cancel', 'danger'],
    ];

    public function detail_id()
    {
        return 'procurement_movement_detail_id';
    }

    public function flag()
    {
        return 'procurement_movement_status';
    }

    public function setFlagAttribute($value)
    {
        $this->attributes[$this->flag()] = $value;
    }

    public function getFlagAttribute()
    {
        return $this->{$this->flag()};
    }

    public function branch_from()
    {
        return 'procurement_movement_from_id';
    }

    public function setBranchFromAttribute($value)
    {
        $this->attributes[$this->branch_from()] = $value;
    }

    public function getBranchFromAttribute()
    {
        return $this->{$this->branch_from()};
    }

    public function getBranchFromNameAttribute()
    {
        return $this->from->procurement_branch_name ?? '';
    }

    public function branch_to()
    {
        return 'procurement_movement_to_id';
    }

    public function setBranchToAttribute($value)
    {
        $this->attributes[$this->branch_to()] = $value;
    }

    public function getBranchToAttribute()
    {
        return $this->{$this->branch_to()};
    }
    
    public function getBranchToNameAttribute()
    {
        return $this->to->procurement_branch_name ?? '';
    }

    public function detail()
    {
        return $this->hasMany(MovementDetail::class, $this->detail_id(), MovementFacades::getKeyName());
    }

    public function from()
    {
        return $this->hasOne(Branch::class, BranchFacades::getKeyName(), $this->branch_from());
    }

    public function to()
    {
        return $this->hasOne(Branch::class, BranchFacades::getKeyName(), $this->branch_to());
    }
}
