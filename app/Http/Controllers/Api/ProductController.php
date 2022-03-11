<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         $this->middleware('auth:api')->only(['index', 'show']);
     }
    
    public function index()
    {
        //
        $products = Product::all();
        $result = ProductResource::collection($products);

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Products retrieved successfully.',
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
        $validator = Validator::make($request->all(), [ 
            'codeno' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'photo' => 'required|mimes:jpeg,bmp,png,jpg',
            'price' =>'required|regex:/^\d+(\.\d{1,2})?$/',
            'min_qty' => 'required|string',
            'instock' => 'required|string',
            'brand_id'       =>  'required',
            'subcategory_id' =>  'required',
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

            $codeno = $request->codeno;
            $name = $request->name;
            $photo = $request->photo;
            $description = $request->description;
            $price = $request->price;
            $min_qty = $request->min_qty;
            $instock = $request->instock;
            $product_status = $request->status;
            $subcategory_id = $request->subcategory_id;
            $brand_id = $request->brand_id;
            
            // File Upoload
            $photo_ext = time().'.'.$photo->extension();  
       
            $photo->move(public_path('images/product'), $photo_ext);

            $filepath = '/images/product/'.$photo_ext;

            // Data insert
            $product = new Product();
            $product->codeno = $codeno;
            $product->name = $name;
            $product->photo = $filepath;
            $product->description = $description;
            $product->price = $price;
            $product->min_qty = $min_qty;
            $product->instock = $instock;
            $product->status = $product_status;
            $product->subcategory_id = $subcategory_id;
            $product->brand_id = $brand_id;

            $product->save();
            
            $result = new ProductResource($product);
            
            return response()->json([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Product created successfully.',
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
        $product = Product::find($id);

        if (is_null($product)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Product not found.'
            ]);

        }
        else{
            $result = new ProductResource($product);
            
            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Product retrieved successfully.',
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
        $product = Product::find($id);

        if (is_null($product)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Product not found.'
            ]);

        }
        else{

            $validator = Validator::make($request->all(), [ 
                'codeno' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'photo' => 'required',
                'photo.*' => 'required|mimes:jpeg,bmp,png',
                'price' =>'required|regex:/^\d+(\.\d{1,2})?$/',
                'min_qty' => 'required|string',
                'instock' => 'required|string',
                'brand_id'       =>  'required',
                'subcategory_id' =>  'required',
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

                $codeno = $request->codeno;
                $name = $request->name;
                $photo = $request->photo;
                $description = $request->description;
                $price = $request->price;
                $min_qty = $request->min_qty;
                $instock = $request->instock;
                $product_status = $request->status;
                $subcategory_id = $request->subcategory_id;
                $brand_id = $request->brand_id;
                
                // File Upoload
                if ($request->hasFile('photo')) {
                    $photo_ext = time().'.'.$photo->extension();  
            
                    $photo->move(public_path('images/product'), $photo_ext);

                    $filepath = '/images/brand/'.$photo_ext;

                    $photo_logo = $product->photo;

                    // if(\File::exists(public_path($photo_logo))){
                    //     \File::delete(public_path($photo_logo));
                    // }
                }
                else{
                    $filepath = $product->photo;

                }
                // Data insert
                $product->codeno = $codeno;
                $product->name = $name;
                $product->photo = $filepath;
                $product->description = $description;
                $product->price = $price;
                $product->min_qty = $min_qty;
                $product->instock = $instock;
                $product->status = $product_status;
                $product->subcategory_id = $subcategory_id;
                $product->brand_id = $brand_id;

                $product->save();
                
                $result = new ProductResource($product);
                
                return response()->json([
                    'status'  => 200,
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'data'    => $result,
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
        $product = Product::find($id);

        if (is_null($product)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Product not found.'
            ]);

        }
        else{

            $product->delete();

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Product deleted successfully.',
            ]);

        }
    }
}
