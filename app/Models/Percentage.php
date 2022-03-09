<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percentage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'percent', 'product_id', 'grade_id'
    ];

    public function product(){
    	return $this->belongsTo('App\Models\Product');
    }

    public function grade(){
    	return $this->belongsTo('App\Models\Grade');
    }
}
