<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Product[]
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'image_url' => 'required|string',
            'expiration_date' => 'required|date',
            'price' => 'required|double',
            'periods' => 'required|JSON',
            'quantity' => 'required|numeric',
            'category_id' => 'required|Numeric',
        ]);
        $fields['user_id']= $request->user()->id;
        //$fields['category_id']= ;
        //return response($fields['periods']);
        Product::create($fields);

        return response(true, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Product $product
     * @return Response
     */
    public function show(Product $product)
    {

        return response($product, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Product $product)
    {
        //return $request->user();
        if($request->user()->id!=$product->user_id){
            return response()->json(['message'=> 'Unauthorized'],401);
        }
        $fields = $request->validate([
            'name' => 'string',
            'image_url' => 'string',
            'price' => 'numeric',
            'periods' => 'JSON',
            'quantity' => 'numeric',
        ]);

        $product->update($fields);
        return response(Product::find($product->id), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function destroy(Product $product, Request $request)
    {
        if($request->user()->id!=$product->user_id){
            return response()->json(['message'=> 'Unauthorized'],401);
        }
        //$product->destroy();
        Product::destroy($product->id);
        return response(true, 200);
    }
}
