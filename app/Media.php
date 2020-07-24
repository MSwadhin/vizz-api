<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = "media";
    protected $fillable = [
       'name','title','alt','type','path','occurence'
    ];
    protected $hidden = [];
}
