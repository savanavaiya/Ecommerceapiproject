<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function store()
    {
        $product_id = Request()->product_id;

        if($product_id == null){
            return response()->json(['success'=>'false','message'=>'Enter Product Id'],200);
        }

        $uid = auth('sanctum')->user()->id;

        $data = Wishlist::create([
            'user_id' => $uid,
            'product_id' => $product_id,
        ]);

        return response()->json(['success'=>'true','message'=>'Add Product Wishlist Successfully'],200);
    }

    public function getwish()
    {
        $uid = auth('sanctum')->user()->id;

        $data = Wishlist::with('prod')->where('user_id',$uid)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
    }

    public function removewish()
    {
        $product_id = Request()->product_id;

        if($product_id == null){
            return response()->json(['success'=>'false','message'=>'Enter Product Id'],200);
        }

        $uid = auth('sanctum')->user()->id;

        $data = Wishlist::where('product_id',$product_id)->where('user_id',$uid)->first();
        $data->delete();

        return response()->json(['success'=>'true','message'=>'Remove Product Wishlist Successfully'],200);

    }
}
