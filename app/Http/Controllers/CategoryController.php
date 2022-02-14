<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function addCategory()
    {
        return view('admin.categories.addcategory');
    }

    public function categories()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.categories')->with('categories' , $categories);
    }


    public function saveCategory(Request $request)
    {
        
         $validated = $request->validate([
            'category_name' => 'required|unique:categories',
        ]);

        $category = new Category;
        $category->category_name = $request->input('category_name');
        $category->save();
        return redirect()->back()->with('success' , 'The category has been added successfully !!');

    }

    public function editCategory($id)
    {
        $category = Category::find($id);
        return view('admin.categories.edit_category')->with('category' , $category);
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ]);
        $category = Category::find($request->input('id'));
        $category->category_name = $request->input('category_name');
        $category->update();
        return redirect()->back()->with('success' , 'The category name has been updated !!');

    }


    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back()->with('success' , 'The category name has been deleted !!');

    }


    

    

    
}
