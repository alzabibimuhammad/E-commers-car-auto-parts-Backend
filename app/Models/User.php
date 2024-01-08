<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends  Authenticatable implements JWTSubject,MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;
    protected $table = 'Users';
    protected $primaryKey='id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'utype',
        'address',
        'image',
        'password',

    ];
    public function parts()
    {
        return $this->hasMany(parts::class,'seller_id','id');
    }

    public function customer(){
        return $this->hasMany(Cart::class,'customer_id');
    }

    public function seller(){
        return $this->hasMany(Cart::class,'seller_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sale_customer(){
        return $this->belongsTo(Sale::class,'custmore_id');
    }

    public function sale_seller(){
        return $this->belongsTo(Sale::class,'seller_id');
    }
    public function getJWTIdentifier (){
        return $this->getKey();
    }
    public function getJWTCustomClaims (){
        return [];
    }
}
