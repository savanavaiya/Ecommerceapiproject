<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Promocode;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addorder(Request $request)
    {

        $validate = $request->validate([
            'total_item' => 'required|array',
            'promocode_id' => 'required',
            'total_bucket_value' => 'required',
            'total_payable_amount' => 'required',
            'total_saving_amount' => 'required',
            'slot_date' => 'required',
            'slot_time' => 'required',
            'payment_method' => 'required',
            'contact_detail_name' => 'required',
            'contact_detail_number' => 'required',
            'contact_detail_address' => 'required',
        ]);

        $uid = auth('sanctum')->user()->id;

        $data = Order::create([
            'user_id' => $uid,
            'final_bucket_payable' => $request->total_bucket_value,
            'final_total_payable' => $request->total_payable_amount,
            'final_saving_payable' => $request->total_saving_amount,
            'slot_date' => $request->slot_date,
            'slot_time' => $request->slot_time,
            'payment_method' => $request->payment_method,
            'contact_detail_name' => $request->contact_detail_name,
            'contact_detail_number' => $request->contact_detail_number,
            'contact_detail_address' => $request->contact_detail_address,
            'status' => "Pending",
        ]);

        $ord = Order::where('user_id',$uid)->orderBy('created_at','DESC')->first();

        $cnt = count($request->total_item);
        
        for($i=0;$i<$cnt;$i++){
            $cart = Cart::where('id',$request->total_item[$i])->first();

            $data2 = Orderitem::create([
                'order_id' => $ord->id,
                'product_id' => $cart->product_id,
                'quntity' => $cart->quntity,
                'totalmrp' => $cart->totalmrp,
                'totleprice' => $cart->totleprice,
            ]);

            $cart->delete();
        }

        if($request->promocode_id != '0'){
            $delpromo = Promocode::find($request->promocode_id);
            $delpromo->delete();
        }
        

        return response()->json(['success'=>'true','message'=>'Confirm Order Successfully'],200);

    }

    public function getorder()
    {
        $uid = auth('sanctum')->user()->id;

        $data = Order::with('item.prod')->where('user_id',$uid)->get();


        return response()->json(['success'=>'true','message'=>'get order successfully','order'=>$data],200);
    }
    
    public function getorderseparatedata()
    {
        $uid = auth('sanctum')->user()->id;
        $order_id = Request()->order_id;

        $data = Order::with('item.prod')->where('user_id',$uid)->where('id',$order_id)->first();


        return response()->json(['success'=>'true','message'=>'get order successfully','order'=>$data],200);
    }

    public function canorder()
    {
        $order_id = Request()->order_id;

        $data = Order::find($order_id);
        $data->status = 'Cancel';
        $data->save();

        return response()->json(['success'=>'true','message'=>'Cancel Order Successsfully'],200);

    }
}
