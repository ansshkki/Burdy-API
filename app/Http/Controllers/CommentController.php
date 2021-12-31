<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return response($product->comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCommentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'body' => 'required|string',
        ]);
        $comment = $product->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);
        return response($comment, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Comment $comment)
    {
        if (Auth::id() != $comment->user_id) {
            return response(['message' => 'Unauthorized'], 401);
        }
        Comment::destroy($comment->id);
        return response("true", 200);
    }
}
