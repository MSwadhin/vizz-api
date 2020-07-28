<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = "sliders";

    protected $fillable = [
        'name','trashed'
    ];
    public function slides(){
        $this->hasMany('App\Slide','slider_id')->where('trashed',0);
    }

}
