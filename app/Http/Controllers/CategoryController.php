<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Index
    public function index()
    {
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    // Create
    public function create()
    {
        return view('pages.categories.create');
    }

    //store
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'name'        => 'required',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // store category
        $category = new Category();
        $category->name        = $request->name;
        $category->description = $request->description;
        $category->save();

        // save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->storeAs(
                'public/categories',
                $category->id . '.' . $image->getClientOriginalExtension()
            );
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    //show
    public function show($id)
    {
        return view('pages.categories.show');
    }

    // Edit
    public function edit($id)
    {
        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    // Update
    public function update(Request $request, $id)
    {
        //validate the request
        $request->validate([
            'name'        => 'required',
            // 'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // update the request
        $category = Category::find($id);
        $category->name        = $request->name;
        $category->description = $request->description;

        // save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->storeAs(
                'public/categories',
                $category->id . '.' . $image->getClientOriginalExtension()
            );
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();  
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }
    // Destroy
    public function destroy($id){
        $category = Category::find($id);
        $category->delete();
        // hapus file gambar kalau ada
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
