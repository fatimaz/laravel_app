<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Note extends Model
{

    protected $fillable = ['note'];

    public function model(){
        return $this->morphTo();
      }

}
