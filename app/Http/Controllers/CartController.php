<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $cart = new Cart;
        $cart->user_id = auth()->user()->id;
        $cart->product_id = $request->productId;
        $cart->quantity = $request->quant;
        $product = Product::findOrFail($cart->product_id);
        if ($cart->quantity > $product->stock) {
            $cart->quantity = $product->stock;
        }
        $cart->save();
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->quantity += $request->quant;
        $product = Product::findOrFail($cart->product_id);
        if ($cart->quantity > $product->stock) {
            $cart->quantity = $product->stock;
        }
        $cart->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();

        return redirect()->back();
    }
}
