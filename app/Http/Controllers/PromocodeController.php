<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function getpromo()
    {
        $uid = auth('sanctum')->user()->id;

        $data = Promocode::where('user_id',$uid)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
    }

    public function addpromo()
    {
        $prom_id = Request()->prom_id;
        $total_payable_amount = Request()->total_payable_amount;

        $data = Promocode::find($prom_id);
        if($total_payable_amount >= $data->condition){
            $total_payable_amount = $total_payable_amount - $data->discount;
            return response()->json(['success'=>'true','total_payable_amount'=>$total_payable_amount,'promocode_discount'=>$data->discount],200);
        }else{
            return response()->json(['success'=>'false'],403);
        }
    }
}
