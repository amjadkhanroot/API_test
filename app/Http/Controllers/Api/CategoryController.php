<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = auth()->user()->categories()->with('tasks', 'comments', 'files')->paginate(100);

        return CategoryResource::collection($categories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request-> validate([
            'title' => 'required',
        ]);

        return auth()->user()->categories()->create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        if(Auth::id() != $category->user_id){
            return response()->json(['message'=>'Unauthorized!'], '401');
        }

        return new CategoryResource($category->load('tasks', 'comments', 'files'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validateData = $request-> validate([
            'title' => 'required',
        ]);

        if(Auth::id() != $category->user_id){
            return response()->json(['message'=>'Unauthorized!'], '401');
        }

        if($category->update($request->all())){
            return response()->json(['message'=>'successfully Updated!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {

        if(Auth::id() != $category->user_id){
            return response()->json(['message'=>'Unauthorized!'], '401');
        }

        if($category->delete()){
            return response()->json(['message'=>'successfully Deleted!']);
        }

    }

    /**
     * Restore a specified resource from storage.
     *
     */
    public function restore($categoryId){

        $category = Category::onlyTrashed()->findOrFail($categoryId);

        if(Auth::id() != $category->user_id){
            return response()->json(['message'=>'Unauthorized!'], '401');
        }

        if ($category->restore()){
            return response()->json(['message'=>'Resource restored successfully!']);
        }

        return response()->json(['message'=>'Resource not found!'], 500);
    }

    /**
     * force delete a specified resource from storage.
     *
     */
    public function forceDelete($categoryId){

        $category = Category::withTrashed()->findOrFail($categoryId);

        if(Auth::id() != $category->user_id){
            return response()->json(['message'=>'Unauthorized!'], '401');
        }

        if ($category->forceDelete()){
            return response()->json(['message'=>'Resource force-deleted successfully!']);
        }

        return response()->json(['message'=>'Resource not found!'], 500);
    }
}
