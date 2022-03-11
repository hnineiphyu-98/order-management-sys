<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::all();
        $result = CategoryResource::collection($categories);
       

        return response()->json([
            'stasus'  => 200,
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data'    => $result
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'name'  => 'required|string|max:255|unique:categories'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'    =>  400,
                'success'   =>  false,
                'message'   =>  'Validation Error.',
                'data'      =>  $validator->errors(),
            ], 400);

        }
        else{

            $name = $request->name;;

            // Data Insert
            $category = new Category;
            $category->name = $name;
            $category->save(); 

            $result = new CategoryResource($category);

            return response()->json([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Category created successfully.',
                'data'      => $result,
            ], 200);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $category = Category::find($id);

        if (is_null($category)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Category not found.'
            ], 404);
            
        }else{
            
            $result = new CategoryResource($category);

            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Category retrieved successfully.',
                'data'      =>  $result
            ], 200);

        }
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
        $category = Category::find($id);

        if (is_null($category)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Category not found.'
            ], 404);

        }
        else{

            $validator = Validator::make($request->all(),[
                'name'  => 'required|string|max:255|unique:categories'
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status'    =>  400,
                    'success'   =>  false,
                    'message'   =>  'Validation Error.',
                    'data'      =>  $validator->errors()
                ], 400);

            }
            else{

                $name = $request->name;
                
                $category = Category::find($id);

                $category->name = $name;
                $category->save();

                $result = new CategoryResource($category);

                return response()->json([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'Category updated successfully.',
                    'data'      => $result
                ], 200);

            }
        }
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
        $category = Category::find($id);
        if (is_null($category)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Category not found.'
            ], 404);

        }
        else{

            $category->delete();

            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Category deleted successfully.'
            ], 200);

        }
    }
}
