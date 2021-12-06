<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Product[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image_url' => 'required|string',
            'expiration_date' => 'required|date',
            'price' => 'required|numeric',
            'periods' => 'required|JSON',
            'quantity' => 'required|numeric',
            'user_id' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        Product::create([
            'name' => $fields['name'],
            'image_url' => $fields['image_url'],
            'category_id' => $fields['category_id'],
            'expiration_date' => $fields['expiration_date'],
            'price' => $fields['price'],
            'periods' => $fields['periods'],
            'quantity' => $fields['quantity'],
            'user_id' => $fields['user_id'],
            'description' => $fields['description'],
        ]);

        return response(true, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response(Product::query()->find($product)->get(), 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image_url' => 'required|string',
            'expiration_date' => 'required|date',
            'price' => 'required|numeric',
            'periods' => 'required|JSON',
            'quantity' => 'required|numeric',
            'user_id' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        Product::query()->find($product)->update($fields);
        return response(true, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Product::destroy($product);
        return response(true, 200);
    }
}