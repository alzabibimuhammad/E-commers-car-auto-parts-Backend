<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class validationseller extends Model
{
    use HasFactory;
    protected $table = 'validationsellers';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'utype',
        'address',
        'password',

    ];

}
