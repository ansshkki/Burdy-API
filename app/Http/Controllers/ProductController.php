<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
            'expiration_date' => 'required|date',
            'price' => 'required|numeric',
            'periods' => 'required|JSON',
            'quantity' => 'required|numeric',
            'category_id' => 'required|Numeric',
            'image' => 'required|file',
        ]);
        $images_url = '/storage/app/';
        $fields['user_id'] = $request->user()->id;
        $path = $request->file('image')->
        storeAs('images', time() . '.' . $request->file('image')->getClientOriginalExtension());
        $fields['image_url'] = $images_url . $path;
        $product = Product::create($fields);

        return response($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        $product->update(["views" => $product->views + 1]);
        $product['user']=$product->user;
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
        if ($request->user()->id != $product->user_id) {
            return response(['message' => 'Unauthorized'], 401);
        }
        $fields = $request->validate([
            'name' => 'string',
            'price' => 'numeric',
            'periods' => 'JSON',
            'quantity' => 'numeric',
        ]);
        $request->validate(['image'=>'File']);

        $product->update($fields);
        return response($product, 200);
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
        if ($request->user()->id != $product->user_id) {
            return response(['message' => 'Unauthorized'], 401);
        }
        Product::destroy($product->id);
        return response("true", 200);
    }

    /**
     * Display the filtered resources.
     * 
     * @param Request $request
     * @param  Product $product
     * @return Response
     */
    public function search(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'category_id' => 'numeric',
            'upPrice' => 'numeric',
            'downPrice' => 'numeric',
            'expiration_date' => 'Date',
        ]);
        $products = Product::query();
        if($request->name){
            $products = $products->where('name','like','%'.$request->name.'%');
        }
        if($request->category_id){
            $products = $products->where('category_id',$request->category_id);
        }
        if($request->upPrice){
            $products = $products->where('price','<=',$request->upPrice);
        }
        if($request->downPrice){
            $products = $products->where('price','>=',$request->downPrice);
        }
        
        if($request->expiration_date){
            $products = $products->whereDate('expiration_date','<=',$request->expiration_date);
        }
        return ($products->get());

    }
}
