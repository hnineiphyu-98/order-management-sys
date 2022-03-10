<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $subcategories = Subcategory::all();
        $result = SubcategoryResource::collection($subcategories);
        
        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Subcategories retrieved successfully.',
            'data'    => $result
        ]);

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
            'name'  => 'required|string|max:255|unique:subcategories',
            'category_id' => 'required|numeric|min:0|not_in:0'
        ]);

        if ($validator->fails()) {
            
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ]);

        }
        else{
            $name = $request->name;
            $category_id = $request->category_id;

            // Data insert
            $subcategory = new Subcategory();
            $subcategory->name = $name;
            $subcategory->category_id = $category_id;
            $subcategory->save();

            $result = new SubcategoryResource($subcategory);
            
            return response()->json([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Subcatogory created successfully.',
                'data'      => $result
            ]);   
              
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
        $subcategory = Subcategory::find($id);

        if (is_null($subcategory)) {
            
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Subcategory not found.'
            ]);

        }
        else{
            
            $result = new SubcategoryResource($subcategory);

            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Subcategory retrieved successfully.',
                'data'      =>  $result
            ]);
        
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
        $subcategory = Subcategory::find($id);

        if (is_null($subcategory)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Subcategory not found.'
            ]);

        }
        else{

            $validator = Validator::make($request->all(),[
                'name'  => 'required|string|max:255|unique:subcategories',
                'category_id' => 'required|numeric|min:0|not_in:0'
            ]);

            if ($validator->fails()) {
               
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'Validation Error.',
                    'data' => $validator->errors()
                ]);

            }
            else{
                $name = $request->name;
                $category_id = $request->category_id; 

                // Data insert
                $subcategory = Subcategory::find($id);
                $subcategory->name = $name;
                $subcategory->category_id = $category_id;
                $subcategory->save();

                
                $result = new SubcategoryResource($subcategory);
                
                return response()->json([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'Subcatogory updated successfully.',
                    'data'      => $result
                ]);   
                
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
        $subcategory = Subcategory::find($id);
        if (is_null($subcategory)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Subcategory not found.'
            ]);

        }
        else{

            $subcategory->delete();
            
            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Category deleted successfully.'
            ]);

        }
    }
}
