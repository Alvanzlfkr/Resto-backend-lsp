<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //index api
    public function index()
    {
        //get all categories
        $categories = Category::all();
        // $categories = Category::paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 200);}
}
