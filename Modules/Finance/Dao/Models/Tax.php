<?php

namespace Modules\Finance\Dao\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'tax';
    protected $primaryKey = 'tax_code';
    protected $keyType = 'string';

    protected $fillable = [
        'tax_code',
        'tax_name',
        'tax_description',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = false;
    public $rules = [
        'tax_name' => 'required',
    ];

    public $searching = 'tax_name';
    public $datatable = [
        'tax_code' => [true => 'Code', 'width' => 50],
        'tax_name' => [true => 'Name','width' => 150],
        'tax_description' => [true => 'Description'],
    ];
}
