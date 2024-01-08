<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class report extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'seller_id',
        'part_id',
        'description',
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

}
