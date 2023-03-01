<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','product_id','quntity','totalmrp','totleprice','status'];

    public function prod()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
}
