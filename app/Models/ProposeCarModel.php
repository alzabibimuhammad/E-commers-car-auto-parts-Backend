<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposeCarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'type',
        'seller_id',
    ];
}
