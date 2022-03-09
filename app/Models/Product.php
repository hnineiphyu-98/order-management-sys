<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'codeno', 'name', 'photo', 'price', 'min_qty', 'status', 'description', 'subcategory_id', 'brand_id'
    ];

    public function subcategory(){
    	return $this->belongsTo('App\Models\Subategory');
    }

    public function brand(){
    	return $this->belongsTo('App\Models\Brand');
    }

    public function percentages()
    {
        return $this->hasMany('App\Models\Percentage');
    }

    public function orders(){
        return $this->belongsToMany('App\Models\Order', 'order_history','order_id','product_id')
        ->withPivot('qty')
        ->withTimestamps();
    }
}
