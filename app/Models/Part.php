<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


class Part extends Model
{
    use HasFactory;
    protected $table = 'parts';
    protected $fillable = [
        'name',
        'image',
        'seller_id',
        'model_id',
        'category_id',
        'amount',
        'price',
        'description',
        ];
    protected $searchable=[
        'price',
        'name',
        'description',
        'seller.name',
        'seller.email',
        'category.name',
        'category.description',
        'model.model',
    ];

    public function seller(){
        return $this->hasOne(User::class,'id','seller_id')->where('deleted_at');
    }
    public function category(){
        return $this->belongsTo(category::class,'category_id')->where('deleted_at');
    }
    public function cart(){
        return $this->hasMany(Cart::class,'part_id');
    }

    public function sale(){
        return $this->belongsTo(category::class,'category_id');
    }
    public function model(){
        return $this->belongsTo(Car::class,'model_id');
    }


    public function scopeSearch(Builder $builder , string $term=''){
        foreach($this->searchable as $searchable ){
            if(str_contains($searchable,'price')){
                $builder->where($searchable,'<=',$term)->where('deleted_at');
                continue;
            }
            if(str_contains($searchable,'.')){
                $relation=Str::beforeLast($searchable, '.');
                $column=Str::afterLast($searchable, '.');
                $builder->orWhereRelation($relation,$column,'like',"%$term%")->where('deleted_at');
                continue;
            }

            $builder->orWhere($searchable,'like',"%$term%")->where('deleted_at');
        }
        return $builder;
    }
}
