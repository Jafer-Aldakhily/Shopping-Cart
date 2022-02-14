<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\RedirectController;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function addProduct()
    {
        $categories = Category::all()->pluck('category_name' , 'category_name');
        return view('admin.products.addproduct')->with('categories',$categories);
    }

    public function products()
    {
        $products = Product::all();
        return view('admin.products.products')->with('products' , $products);
    }

    public function saveProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category' => 'required',
            'product_image' => 'image|required|max:1999'
        ]);

        if($request->hasFile('product_image'))
        {

            // 1: get image name with extension
            $fileNameWithExe = $request->file('product_image')->getClientOriginalName();
            // 2: get image name without extension
            $fileName = \pathinfo($fileNameWithExe , \PATHINFO_FILENAME);
            // 3: get image extension
            $fileExe = $request->file('product_image')->getClientOriginalExtension();
            // 4: file name to store
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExe;
            // 5: upload file
           $path = $request->file('product_image')->storeAs('public/product_images',$fileNameToStore);
        }else
        {
            $fileNameToStore = 'noimage.jpg';
        }

         $product = new Product();
         $product->product_name = $request->input('product_name');
         $product->product_price = $request->input('product_price');
         $product->product_category = $request->input('product_category');
         $product->product_image = $fileNameToStore;
         $product->status = 1;
         $product->save();

         return \redirect()->back()->with('success' , 'The product has been added successfully !!');


    }


    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all()->pluck('category_name' , 'category_name');
        return view('admin.products.editproduct')->with('product',$product)->with('categories' , $categories);
    }



    public function updateProduct(Request $request , $id)
    {
        $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category' => 'required',
            // 'product_image' => 'image|required|max:1999'
        ]);

        $oldImage = $request->input('privouse_image');

        if($request->hasFile('product_image'))
        {
            $product = Product::find($id);
            // 1: get image name with extension
            $fileNameWithExe = $request->file('product_image')->getClientOriginalName();
            // 2: get image name without extension
            $fileName = \pathinfo($fileNameWithExe , \PATHINFO_FILENAME);
            // 3: get image extension
            $fileExe = $request->file('product_image')->getClientOriginalExtension();
            // 4: file name to store
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExe;
            // 5: upload file
           $path = $request->file('product_image')->storeAs('public/product_images',$fileNameToStore);
           // 6: delete previous file
           if($product->product_image != 'noimage.png')
           {
               Storage::delete('/storage/product_images/' . $oldImage);
           }
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_category = $request->input('product_category');
            $product->product_image = $fileNameToStore;
            $product->update();

            return \redirect()->back()->with('success' , 'The product has been updated successfully !!');

        }else
        {
            $product = Product::find($id);
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_category = $request->input('product_category');
            $product->product_image = $oldImage;
            $product->update();

            return \redirect()->back()->with('success' , 'The product has been updated successfully !!');
        }


    }


    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if($product->product_image != 'noimage.png')
        {
            Storage::delete('/public/product_images/' . $product->product_image);

        }
        $product->delete();
        return redirect()->back()->with('success' , 'The product has been deleted successfully !!');
    }


    public function active_product($id)
    {
        $product = Product::find($id);
        $product->status = 1;
        $product->update();
        return \redirect()->back()->with('success' , 'The product activted successfully !!');
    }

    public function unactive_product($id)
    {
        $product = Product::find($id);
        $product->status = 0;
        $product->update();
        return \redirect()->back()->with('success' , 'The product unactivted successfully !!');
    }

}
