<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Certificate extends Model
{

    protected $fillable = ['stream_name','property_id','issue_date','next_due_date'];



    public function property()
    {
        return $this->belongsTo(Property::class,'property_id');
    }
 

    public function notes()
    {
        return $this->morphMany(Note::class,'model');
    }
}
