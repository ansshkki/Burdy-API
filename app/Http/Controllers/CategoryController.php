<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Category::all();
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
            'name' => 'required|string'
        ]);

        Category::create([
            'name' => $fields['name'],
        ]);

        return response(true, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return Response
     */
    public function show(Category $category)
    {
        return response(Product::query()->findOrFail($category)->get(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function update(Request $request, Category $category)
    {
        $fields = $request->validate([
            'name' => 'required|string'
        ]);

        Product::query()->find($category)->update($fields);
        return response(true, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return Response
     */
    public function destroy(Category $category)
    {
        Category::destroy($category);
        return response(true, 200);
    }
}
