<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use App\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;
use Srmklive\PayPal\Services\ExpressCheckout;
class OrderController extends Controller
{

    public function orders()
    {
        $orders = Order::all();

        $orders->transform(function ($order,$key){
            $order->cart = \unserialize($order->cart);
            return $order;
        });
        return view('admin.orders.orders')->with('orders' , $orders);
    }


    public function post_checkout(Request $request)
    {

        try{

        $oldCart = Session::has('cart') ? Session::get('cart') : 'null';
        $cart = new Cart($oldCart);
        $order = new Order();
        $order->name = $request->input('name');
        $order->address = $request->input('address');
        $order->cart = \serialize($cart);

        Session::put('order' , $order);


        $checkoutData = $this->checkoutData();

        $provider = new ExpressCheckout();

        $response = $provider->setExpressCheckout($checkoutData);

        return redirect($response['paypal_link']);


        }catch(\Exception $e)
        {
            return redirect('/checkout')->with('error' , $e->getMessage());
        }




    }


    private function checkoutData()
{
    $oldCart = Session::has('cart') ? Session::get('cart') : 'null';
    $cart = new Cart($oldCart);

    $data['items'] = [];

    foreach($cart->items as $item)
    {
        $itemDetails = [
            'name' => $item['product_name'],
            'price' => $item['product_price'],
            'qty' => $item['qty'],
            ];

            $data['items'][] = $itemDetails;
    }

    $checkoutData = [
        'items' => $data['items'],
        'return_url' => url('/payment-success'),
        'cancel_url' => url('/checkout'),
        'invoice_id' => uniqid(),
        'invoice_description' => 'order description',
        'total' => Session::get('cart')->totalPrice

        ];


        return $checkoutData;
}



public function payment_success(Request $request)
{

    try{
        $token = $request->get('token');
        $payerID = $request->get('PayerID');
        $checkoutData = $this->checkoutData();

        $provider = new ExpressCheckout();
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($checkoutData , $token ,$payerID);

        $payer_id = $payerID.'_'.time();

        Session::get('order')->payer_id = $payer_id;

        Session::get('order')->save();


        $orders = Order::where('payer_id' , $payer_id)->get();
        $orders->transform(function($order,$key){
            $order->cart = \unserialize($order->cart);
            return $order;
        });

        $email = Session::get('client')->email;

        // send purshced product
        Mail::to($email)->send(new sendMail($orders));

        Session::forget('cart');

        return \redirect('cart')->with('status' , 'Your purchase has been accomplished successfully !!!');


    }catch(\Exception $e)
    {
        return redirect('/checkout')->with('error' , $e->getMessage());
    }

}



}
