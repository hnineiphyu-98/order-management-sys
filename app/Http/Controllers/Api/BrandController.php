<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //
        $brands = Brand::all();
        $result = BrandResource::collection($brands);

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Brands retrieved successfully.',
            'data'    => $result,
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
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255|unique:brands',
            'logo' => 'required|mimes:jpeg,bmp,png,jpg'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'  => 400,
                'success' => false,
                'data'    => $validator->errors(),
                'message' => 'Validation Error.',
            ], 400);

        }
        else
        {
            $name = $request->name;
            $logo = $request->logo;

            // File Upoload
            $logo_ext = time().'.'.$logo->extension();  
       
            $logo->move(public_path('images/brand'), $logo_ext);

            $filepath = '/images/brand/'.$logo_ext;

            // Data insert
            $brand = new Brand;
            $brand->name = $name;
            $brand->logo = $filepath;

            $brand->save();
            
            $result = new BrandResource($brand);

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Brand created successfully.',
                'data'    => $result,
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
        $brand = Brand::find($id);

        if (is_null($brand)) {

            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'Brand not found.'
            ], 404);

        }
        else{

            $result = new BrandResource($brand);
            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Brand retrieved successfully.',
                'data'    => $result,
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
        $brand = Brand::find($id);

        if (is_null($brand)) {

            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'Brand not found.'
            ], 404);

        }
        else{

            $validator = Validator::make($request->all(), [ 
                'name' => 'sometimes|string|max:255',
                'logo' => 'sometimes|mimes:jpeg,bmp,png,jpg'
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status'  => 400,
                    'success' => false,
                    'data'    => $validator->errors(),
                    'message' => 'Validation Error.',
                ], 400);
            }
            else
            {
            
                $name = $request->name;
                $logo = $request->logo;

                // File Upoload
                if ($request->hasFile('logo')) {
                    $logo_ext = time().'.'.$logo->extension();  
                
                    $logo->move(public_path('images/brand'), $logo_ext);

                    $filepath = '/images/brand/'.$logo_ext;

                    $old_logo = $brand->logo;

                    // if(\File::exists(public_path($old_logo))){
                    //     \File::delete(public_path($old_logo));
                    // }
                }
                else{
                    $filepath = $brand->logo;

                }

                // Data insert
                $brand->name = $name;
                $brand->logo = $filepath;
                $brand->save();
                
                $result = new BrandResource($brand);

                return response()->json([
                    'status'  => 200,
                    'success' => true,
                    'message' => 'Brand updated successfully.',
                    'data'    => $result,
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
        $brand = Brand::find($id);

        if (is_null($brand)) {

            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'Brand not found.'
            ], 404);
        }
        else{

            $oldphoto = $brand->logo;

            // if(\File::exists(public_path($oldphoto))){
            //     \File::delete(public_path($oldphoto));
            // }
            $brand->delete(); 

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Brand deleted successfully.',
            ], 200);
        
        }
    }
}
