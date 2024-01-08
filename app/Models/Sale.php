<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'seller_id',
        'car_id',
        'part_id',
        'category_id',
        'price',
        'amount',
        'totalprice',
    ];
    public function seller(){
        return $this->hasOne(User::class,'id','seller_id');
    }
    public function customer(){
        return $this->hasOne(User::class,'id','customer_id');
    }
    public function parts(){
        return $this->hasOne(Part::class,'id','part_id');
    }
    public function category(){
        return $this->hasOne(category::class,'id','category_id');
    }
}
