<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Cart;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function home()
    {
        $sliders = Slider::all()->where('status' , 1);
        $products = Product::all()->where('status' , 1);
        return view('client.home')->with('sliders' , $sliders)->with('products' , $products);
    }

    public function shop()
    {
        $categories = Category::all();
        $products = Product::all()->where('status' , 1);
        return view('client.shop')->with('categories' , $categories)->with('products' , $products);
    }

    public function cart()
    {
        if(!Session::has('cart'))
        {
            return view('client.cart');
        }
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
         return view('client.cart')->with('products' , $cart->items);
    }

    public function checkout()
    {
        if(!Session::has('client'))
        {
            return \redirect('/login');
        }
        return view('client.checkout');
    }

    public function login()
    {
        return view('client.auth.login');
    }

    public function signup()
    {
        return view('client.auth.signup');
    }


    public function get_product_by_category($category_name)
    {
        $categories = Category::all();
        $products = Product::where('product_category' , $category_name)->where('status' , 1)->get();
        return view('client.shop')->with('products' , $products)->with('categories' , $categories);
    }


     public function create_account(Request $request)
     {
         $request->validate([
             'email' => 'email|required|unique:clients',
             'password' => 'required|min:8'
         ]);

         $client = new Client();
         $client->email = $request->email;
         $client->password = bcrypt($request->password);
         $client->save();

         Session::put('client' , $client);
         return \redirect('/shop');





     }


     public function access_account(Request $request)
     {
         $request->validate([
             'email' => 'email|required',
             'password' => 'required|min:8'
         ]);

         $client = Client::where('email' , $request->email)->first();
        //  dd($client->password);
         if($client)
         {
             if (Hash::check($request->password, $client->password))
            {
                Session::put('client' , $client);
                return \redirect('/shop');
            }else
            {
                return back()->with('status' , 'Bad Email or Password !!');
            }
         }else{
             return back()->with('status' , 'You do not have account with this email !!');
         }




     }


     public function logout()
     {
         Session::forget('client');
         Session::forget('cart');
         return \redirect('/login');
     }


}
