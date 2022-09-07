<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Property extends Model
{

    protected $fillable = ['organisation','property_type','parent_property_id','uprn','address','town','postcode','live'];

   

    public function certificates()
    {
        return $this->hasMany(Certificate::class,'property_id');
    }

    public function notes()
    {
        return $this->morphMany(Note::class,'model');
    }
}
