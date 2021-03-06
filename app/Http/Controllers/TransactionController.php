<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Cart;
use App\User;
use App\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(Auth::user());
        $user = Auth::user();
        $carts = $user->cart;
        // dd($carts);
        // dd(count($carts));
        // for ($i = 0; $i < count($carts); $i++) {
        foreach ($carts as $cart) {
            $transaction = new Transaction;

            $transaction->product_id = $cart->product_id;
            $transaction->user_id = $cart->user_id;
            $transaction->quantity = $cart->quantity;
            $transaction->save();
            $cart->delete();
            $product = Product::findOrFail($transaction->product_id);
            $product->stock -= $transaction->quantity;
            $product->save();
        }

        return view('transaction.payment');
    }

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
        $cart->quantity = 1;
        $product = Product::findOrFail($cart->product_id);
        if ($cart->quantity > $product->stock) {
            $cart->quantity = $product->stock;
        }
        $cart->save();

        return view('transaction.show');

        // User $id
        // $user = User::find($id);
        // $carts = $user->cart;
        
        // $transaction = new Transaction;
        // foreach ($carts as $cart) {
        //     $transaction->product_id = $cart->product_id;
        // }
        // $transaction->user_id = $id;
        // $transaction->quantity = count($carts);

        // $transaction->save();

        // return redirect('/')->with('Please pay immediately.');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('transaction.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
