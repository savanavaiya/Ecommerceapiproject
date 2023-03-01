<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','product_title','product_weight','product_mrp','product_discount','product_sellprice','product_image','status'];

    public function cat()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
}
