<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getcat()
    {
        $data = Category::all();

        return response()->json(['success'=>'true','message'=>$data],200);
    }
}
