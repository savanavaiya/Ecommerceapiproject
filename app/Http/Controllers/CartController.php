<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class CartController extends Controller
{
    public function adtcrt(Request $request)
    {
        $validate = $request->validate([
            'product_id' => 'required',
        ]);

        $id = auth('sanctum')->user()->id;

        $exipro = Cart::where('user_id', $id)->where('product_id', $request->product_id)->first();

        if ($exipro == null) {
            $product = Product::find($request->product_id);

            $data = Cart::create([
                'user_id' => $id,
                'product_id' => $request->product_id,
                'totalmrp' => $product->product_mrp,
                'totleprice' => $product->product_sellprice,
            ]);
        } else {
            $exipro->quntity = $exipro->quntity + 1;
            $exipro->save();

            $exipro2 = Cart::where('user_id', $id)->where('product_id', $request->product_id)->first();

            $produ = Product::find($request->product_id);

            $exipro2->totalmrp = $exipro2->quntity * $produ->product_mrp;
            $exipro2->totleprice = $exipro2->quntity * $produ->product_sellprice;
            $exipro2->save();
        }

        return response()->json(['success' => 'true', 'message' => 'Add To Cart Successfully'], 200);
    }

    public function mycart()
    {
        $userid = auth('sanctum')->user()->id;

        $data = Cart::with('prod')->where('user_id', $userid)->get();

        $backet_value = Cart::where('user_id', $userid)->sum('totalmrp');

        $totalpayable = Cart::where('user_id', $userid)->sum('totleprice');

        $totalsavings = $backet_value - $totalpayable;


        return response()->json(['success' => 'true', 'backet_value' => $backet_value, 'totalpayable' => $totalpayable, 'totalsavings' => $totalsavings, 'message' => $data], 200);
    }

    public function mycartmodi(Request $request)
    {

        $dt = Cart::find($request->cart_id);

        $userid = auth('sanctum')->user()->id;

        if ($request->quntity == 0) {
            $dt->delete();
        } else {
            if ($dt->user_id == $userid) {
                $dt->quntity = $request->quntity;

                $pro = Product::find($dt->product_id);

                $dt->totalmrp = $dt->quntity * $pro->product_mrp;
                $dt->totleprice = $dt->quntity * $pro->product_sellprice;

                $dt->save();
            }else{
                return response()->json(['success'=>'false','message'=>'Something Wrong'],200);
            }
        }



        $data = Cart::with('prod')->where('user_id', $userid)->get();

        $backet_value = Cart::where('user_id', $userid)->sum('totalmrp');

        $totalpayable = Cart::where('user_id', $userid)->sum('totleprice');

        $totalsavings = $backet_value - $totalpayable;


        return response()->json(['success' => 'true', 'backet_value' => $backet_value, 'totalpayable' => $totalpayable, 'totalsavings' => $totalsavings, 'message' => $data], 200);
    }
}
