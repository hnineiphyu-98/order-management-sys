<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'orderdate', 'voucher_no', 'total', 'status', 'read_at', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function products(){
        return $this->belongsToMany('App\Models\Product', 'order_history','order_id','product_id')
        ->withPivot('qty')
        ->withTimestamps();
    }
}
