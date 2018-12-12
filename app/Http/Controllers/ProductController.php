<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Category;

// use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return view('pages.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *'public/stylesheets/styles.css
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('pages.create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        // Validate form (backend)
        $request->validate([
            "productName" => "required|max:50",
            "categoryName" => "required",
            "description" => "max:255",
            "productPrice" => "required",
            "productStock" => "required",
            "coverCategory" => "image|nullable|max:2999"
        ]);

        // Handle file upload
        if ($request->hasFile('coverProduct')) {
            // Get filename w/ ext
            $filenameWithExt = $request->file('coverProduct')->getClientOriginalName();
            // Filename wo/ ext
            $filename = \pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Ext
            $ext = $request->file('coverProduct')->extension();
            // Filename to store
            $filenameToStore = $filename . '_' . time() . '_' . $ext;
            // Upload
            $path = $request->file('coverProduct')->storeAs('public/coverProducts', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        $product->create([
            "productName" => $request->productName,
            "category_id" => $request->categoryName,
            "description" => $request->description,
            "price" => $request->productPrice,
            "stock" => $request->productStock,
            "coverProduct" => $filenameToStore
        ]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = Product::findOrFail($id);
        return view('pages.show')->with('products', $products);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::findOrFail($id);
        return view('pages.edit')->with('products', $products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // Validate form (backend)
        $request->validate([
            "productName" => "required|max:50",
            "categoryName" => "required",
            "description" => "max:255",
            "productPrice" => "required",
            "productStock" => "required",
            "coverCategory" => "image|nullable|max:2999"
        ]);

        // Handle file upload
        if ($request->hasFile('coverProduct')) {
            // Get filename w/ ext
            $filenameWithExt = $request->file('coverProduct')->getClientOriginalName();
            // Filename wo/ ext
            $filename = \pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Ext
            $ext = $request->file('coverProduct')->extension();
            // Filename to store
            $filenameToStore = $filename . '_' . time() . '_' . $ext;
            // Upload
            $path = $request->file('coverProduct')->storeAs('public/coverProducts', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        // Product update
        $product->productName = $request->productName;
        $product->category_id = $request->categoryName;
        $product->description = $request->description;
        $product->price = $request->productPrice;
        $product->stock = $request->productStock;

        if ($request->hasFile('coverProduct')) {
            $product->coverProduct = $filenameToStore;
        }

        $product->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($category->coverProduct != 'noimage.jpg') {
            Storage::delete('public/coverProducts/' . $category->coverProduct);
        }

        $product->delete();
        return redirect('/');
    }
}
