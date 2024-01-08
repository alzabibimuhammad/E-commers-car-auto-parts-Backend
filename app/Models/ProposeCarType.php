<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposeCarType extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'seller_id',
    ];

}
