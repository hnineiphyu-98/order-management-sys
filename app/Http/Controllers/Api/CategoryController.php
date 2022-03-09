<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Validator;

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
       

        return response()->json($result, 200);
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
            $status = 400;
            $message = 'Validation Error.';

            $response = [
                'status'    =>  $status,
                'success'   =>  false,
                'message'   =>  $message,
                'data'      =>  $validator->errors(),
            ];

            return response()->json($response, 400);
        }
        else{
            $name = $request->name;;

            // Data Insert
            $category = new Category;
            $category->name = $name;
            $category->save(); 

            $status = 200;
            $message = 'Category created successfully.';
            $result = new CategoryResource($category);

            $response = [
                'success'   => true,
                'status'    => $status,
                'message'   => $message,
                'data'      => $result,
            ];

            return response()->json($response, 200);       
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
            # 404
            $status = 404;
            $message = 'Category not found.';

            $response = [
                'status'    => $status,
                'success'   => false,
                'message'   => $message
            ];

            return response()->json($response,404);
        }else{
            #200
            $status = 200;
            $message = 'Category retrieved successfully.';
            $result = new CategoryResource($category);

            $response = [
                'status'    =>  $status,
                'success'   =>  true,
                'message'   =>  $message,
                'data'      =>  $result
            ];

            return response()->json($response, 200);
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

            $status = 404;
            $message = 'Category not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);

        }
        else{

            $validator = Validator::make($request->all(),[
                'name'  => 'required|string|max:255|unique:categories'
            ]);

            if ($validator->fails()) {
                $status = 400;
                $message = 'Validation Error.';

                $response = [
                    'status'    =>  $status,
                    'success'   =>  false,
                    'message'   =>  $message,
                    'data'      =>  $validator->errors(),
                ];

                return response()->json($response, 400);
            }
            else{
                $name = $request->name;
                
                $category = Category::find($id);
                // Data update
                
                $category->name = $name;
                $category->save();

                $status = 200;
                $result = new CategoryResource($category);
                $message = 'Category updated successfully.';

                $response = [
                    'success'   => true,
                    'status'    => $status,
                    'message'   => $message,
                    'data'      => $result
                ];

                return response()->json($response, 200);
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

            $status = 404;
            $message = 'Category not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);
        }
        else{

            $category->delete();

                $status = 200;
                $message = 'Category deleted successfully.';

                $response = [
                    'success'   =>  true,
                    'status'    =>  $status,
                    'message'   =>  $message
                ];

                return response()->json($response, 200);
        }
    }
}
