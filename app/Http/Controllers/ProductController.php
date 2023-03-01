<?php

namespace App\Http\Controllers;

use App\Models\Orderitem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getprod()
    {
        $category_id = Request()->category_id;

        $data = Product::where('category_id',$category_id)->get();

        return response()->json(['message'=>$data],200);
    }

    public function popproduct()
    {
        $data = Orderitem::with('prod','prod.cat')->select('product_id', DB::raw('COUNT(product_id) AS magnitude'))->groupBy('product_id')->orderBy('magnitude', 'DESC')->limit(10)->get();
        
        return response()->json(['message'=>$data],200);
    }
}
