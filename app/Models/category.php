<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description'

    ];
    protected $dates=['deleted_at'];

    public function parts(){
        return $this->hasMany(parts::class,'category_id','id');
    }
    public function cart(){
        return $this->hasMany(Cart::class,'category_id');
    }
    public function sale(){
        return $this->belongsTo(Sale::class,'category_id');

    }
}
