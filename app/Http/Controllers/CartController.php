<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Cart;

class CartController extends Controller
{
    public function addToCart($id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product,$id);
        Session::put('cart' , $cart);

        return back();

    }


    public function update_qty(Request $request,$id){

            //print('the product id is '.$request->id.' And the product qty is '.$request->quantity);
            $oldCart = Session::has('cart')? Session::get('cart'):null;
            $cart = new Cart($oldCart);
            $cart->updateQty($id, $request->quantity);
            Session::put('cart', $cart);

            //dd(Session::get('cart'));
            return redirect('/cart');
    }


    public function removeItem($product_id){
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($product_id);

        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        //dd(Session::get('cart'));
        return redirect('/cart');
    }


}
