<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Dao\Enums\CategoryType;
use Wildside\Userstamps\Userstamps;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_id',
        'category_name',
        'category_type',
        'category_description',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = true;
    public $rules = [
        'category_name' => 'required|min:3',
    ];

    public $searching = 'category_name';
    public $datatable = [
        'category_id' => [false => 'Code', 'width' => 50],
        'category_name' => [true => 'Name'],
        'category_type' => [true => 'Type'],
        'category_description' => [true => 'Description'],
    ];

    public function mask_type()
    {
        return 'category_type';
    }

    public function setMaskTypeAttribute($value)
    {
        $this->attributes[$this->mask_type()] = $value;
    }

    public function getMaskTypeAttribute()
    {
        return CategoryType::getDescription($this->{$this->mask_type()});
    }

    public function mask_name()
    {
        return 'category_name';
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
