<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'part_id',
        'amount',
        'seller_id',
        'category_id',
        'car_id',
        'price',
    ];

    public function customer(){
        return $this->belongsTo(User::class,'customer_id');
    }
    public function seller(){
        return $this->belongsTo(User::class,'seller_id');
    }
    public function category(){
        return $this->belongsTo(category::class,'category_id');
    }
    public function part(){
        return $this->belongsTo(Part::class ,'part_id')->where('deleted_at');
    }
}
