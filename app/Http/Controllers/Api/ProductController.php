<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     $this->middleware('role:admin');
    //     $this->middleware('role:admin');
    //     $this->middleware('subscribed')->except('store');
    // }
    public function index()
    {
        //
        $products = Product::all();
        $result = ProductResource::collection($products);

        $message = 'Products retrieved successfully.';
        $status = 200;

        $response = [
            'status'    =>  $status,
            'success'   =>  true,
            'message'   =>  $message,
            'data'      =>  $result,
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
            'codeno' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'photo' => 'required|mimes:jpeg,bmp,png,jpg',
            'price' =>'required|regex:/^\d+(\.\d{1,2})?$/',
            'min_qty' => 'required|string',
            'brand_id'       =>  'required',
            'subcategory_id' =>  'required',
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
            $codeno = $request->codeno;
            $name = $request->name;
            $photo = $request->photo;
            $description = $request->description;
            $price = $request->price;
            $min_qty = $request->min_qty;
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
            $product->status = $product_status;
            $product->subcategory_id = $subcategory_id;
            $product->brand_id = $brand_id;

            $product->save();
            

            $status = 200;
            $result = new ProductResource($product);
            $message = 'Product created successfully.';

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
        $product = Product::find($id);

        if (is_null($product)) {

            $status = 404;
            $message = 'Product not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);

        }
        else{
            $status = 200;
            $result = new ProductResource($product);
            $message = 'Product retrieved successfully.';

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
        $product = Product::find($id);

        if (is_null($product)) {

            $status = 404;
            $message = 'Product not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);
        }
        else{
            $validator = Validator::make($request->all(), [ 
                'codeno' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'photo' => 'required',
                'photo.*' => 'required|mimes:jpeg,bmp,png',
                'price' =>'required|regex:/^\d+(\.\d{1,2})?$/',
                'min_qty' => 'required|string',
                'brand_id'       =>  'required',
                'subcategory_id' =>  'required',
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

                $codeno = $request->codeno;
                $name = $request->name;
                $photo = $request->photo;
                $description = $request->description;
                $price = $request->price;
                $min_qty = $request->min_qty;
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
                $product->status = $product_status;
                $product->subcategory_id = $subcategory_id;
                $product->brand_id = $brand_id;

                $product->save();
                

                $status = 200;
                $result = new ProductResource($product);
                $message = 'Product created successfully.';

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
        $product = Product::find($id);

        if (is_null($product)) {

            $status = 404;
            $message = 'Product not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);
        }
        else{

            $product->delete();

            $status = 200;
            $message = 'Product deleted successfully.';

            $response = [
                'status'  => $status,
                'success' => true,
                'message' => $message,
            ];


            return response()->json($response, 200);
        }
    }
}
