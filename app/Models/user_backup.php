<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_backup extends Model
{
    use HasFactory;
    protected $table = 'user_backups';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'utype',
        'address',
        'image',
        'password',

    ];
}
