<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //index api
    public function index()
    {
        $products = Product::all();
        // $products = Product::paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }
}
