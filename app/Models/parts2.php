<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class parts2 extends Model
{
    use HasFactory;
    protected $table = 'parts';
    use SoftDeletes;
    protected $dates=['deleted_at'];
    protected $fillable = [
        'name',
        'seller_id',
        'model_id',
        'category_id',
        'amount',
        'price',
        'description',


    ];
}

