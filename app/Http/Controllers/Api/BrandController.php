<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Validator;
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
        $message = 'Brands retrieved successfully.';
        $status = 200;

        $response = [
            'status'  => $status,
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200);
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
            $message = 'Validation Error.';
            $status = 400;

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
                'data'    => $validator->errors(),
            ];

            return response()->json($response, 400);
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
            

            $status = 200;
            $result = new BrandResource($brand);
            $message = 'Brand created successfully.';

            $response = [
                'status'  => $status,
                'success' => true,
                'message' => $message,
                'data'    => $result,
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
        $brand = Brand::find($id);

        if (is_null($brand)) {

            $status = 404;
            $message = 'Brand not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);

        }
        else{
            $status = 200;
            $result = new BrandResource($brand);
            $message = 'Brand retrieved successfully.';

            $response = [
                'status'  => $status,
                'success' => true,
                'message' => $message,
                'data'    => $result,
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
        $brand = Brand::find($id);

        if (is_null($brand)) {

            $status = 404;
            $message = 'Brand not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);

        }
        else{

            $validator = Validator::make($request->all(), [ 
                'name' => 'sometimes|string|max:255',
                'logo' => 'sometimes|mimes:jpeg,bmp,png,jpg'
            ]);

            if ($validator->fails()) {
                $message = 'Validation Error.';
                $status = 400;

                $response = [
                    'status'  => $status,
                    'success' => false,
                    'message' => $message,
                    'data'    => $validator->errors(),
                ];

                return response()->json($response, 400);
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
                

                $status = 200;
                $result = new BrandResource($brand);
                $message = 'Brand updated successfully.';

                $response = [
                    'status'  => $status,
                    'success' => true,
                    'message' => $message,
                    'data'    => $result,
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
        $brand = Brand::find($id);

        if (is_null($brand)) {

            $status = 404;
            $message = 'Brand not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);
        }
        else{

            $oldphoto = $brand->logo;

            // if(\File::exists(public_path($oldphoto))){
            //     \File::delete(public_path($oldphoto));
            // }

            $brand->delete();

            $status = 200;
            $message = 'Brand deleted successfully.';

            $response = [
                'status'  => $status,
                'success' => true,
                'message' => $message,
            ];


            return response()->json($response, 200);
        }
    }
}
