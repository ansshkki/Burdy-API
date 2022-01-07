<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return response($product->likes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        if ($product->likes()->where('user_id', Auth::id())->exists()) {
            $product->likes()->where('user_id', Auth::id())->delete();
            return response()->json('removed');
        } else {
            $product->likes()->create(['user_id' => Auth::id()]);
        }
        return response('created', 201);
    }


}
