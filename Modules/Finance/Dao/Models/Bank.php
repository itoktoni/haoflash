<?php

namespace Modules\Finance\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'bank_id';

    protected $fillable = [
        'bank_id',
        'bank_name',
        'bank_description',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = true;
    public $rules = [
        'bank_name' => 'required|min:3',
    ];

    public $searching = 'bank_name';
    public $datatable = [
        'bank_id' => [false => 'Code', 'width' => 50],
        'bank_name' => [true => 'Name'],
        'bank_description' => [true => 'Description'],
    ];
}
