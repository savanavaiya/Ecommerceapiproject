<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','final_bucket_payable','final_total_payable','final_saving_payable','slot_date','slot_time','payment_method','contact_detail_name','contact_detail_number','contact_detail_address','status'];

    public function item()
    {
        return $this->hasMany(Orderitem::class,'order_id','id');
    }
}
