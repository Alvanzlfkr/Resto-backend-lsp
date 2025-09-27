<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Index
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('pages.products.index', compact('products'));
    }

    // Create
    public function create()
    {
        $categories = Category::all();
        return view('pages.products.create', compact('categories'));
    }

    // Store
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric',
            'stock'        => 'required|integer',
            'is_available' => 'required|boolean',
            'is_favorite'  => 'required|boolean',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // store product
        $product = new Product();
        $product->name         = $request->name;
        $product->description  = $request->description;
        $product->price        = $request->price;
        $product->stock        = $request->stock;
        $product->category_id  = $request->category_id;
        $product->is_available = $request->is_available;
        $product->is_favorite  = $request->is_favorite;
        $product->save();

        // save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->storeAs(
                'public/products',
                $product->id . '.' . $image->getClientOriginalExtension()
            );
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    // Show
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pages.products.show', compact('product'));
    }

    // Edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        // validate
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric',
            'stock'        => 'required|integer',
            'is_available' => 'required|boolean',
            'is_favorite'  => 'required|boolean',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // update product
        $product = Product::findOrFail($id);
        $product->name         = $request->name;
        $product->description  = $request->description;
        $product->price        = $request->price;
        $product->stock        = $request->stock;
        $product->category_id  = $request->category_id;
        $product->is_available = $request->is_available;
        $product->is_favorite  = $request->is_favorite;
        $product->save();

        // update image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->storeAs(
                'public/products',
                $product->id . '.' . $image->getClientOriginalExtension()
            );
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    // Destroy
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        // hapus file gambar kalau ada
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
