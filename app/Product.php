<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class Product extends Model
{
    //
    public function brand()
    {
        return $this->belongsTo('App\Brand','brand_id','id');
    }

    public function area()
    {
        return $this->belongsTo('App\Area','area_id','id');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory','subcategory_id','id');
    }
}
