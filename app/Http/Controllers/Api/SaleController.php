<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    //
    public function sale_create(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'name' 		=> 'required',
            'phone'    	=> 'required|unique:sales',
            'email' 	=> 'required|email|unique:sales',
            'password' 	=> 'required',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 400);      
        
        }
        else{

            $name = $request->name;
            $email = $request->email;
            $phone = $request->phone;
            $password = $request->password;
            $admin_id = Auth::guard('admin-api')->user()->id;

            $sale = Sale::create([
                'name'      =>  $name,
                'email'     =>  $email,
                'phone'     =>  $phone,
                'password'  =>  Hash::make($password),
                'admin_id'  =>  $admin_id
            ]);

            $result = new SaleResource($sale);
            
            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Sale user created successfully.',
                'data'    => $result
            ], 200);

        }
    }

    public function sale_update(Request $request, $id)
    {
        $sale = Sale::find($id);
        // dd(Auth::guard('sale-api')->user()->id);
        if (is_null($sale)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Sale user not found.'
            ], 404);
            
        }else{

            if ($id == Auth::guard('sale-api')->user()->id) {

                $sale->update($request->all());
                    
                $result = new SaleResource($sale);
                
                return response()->json([
                    'status'  => 200,
                    'success' => true,
                    'message' => 'Sale user updated successfully.',
                    'data'    => $result
                ], 200);
                // $validator = Validator::make($request->all(), [
                //     'name' 		=> 'required',
                //     'phone'    	=> 'required|unique:sales',
                //     'email' 	=> 'required|email|unique:sales',
                //     'password' 	=> 'required',
                // ]);
        
                // if($validator->fails()){
        
                //     return response()->json([
                //         'status' => 400,
                //         'success' => false,
                //         'message' => 'Validation Error.',
                //         'data' => $validator->errors()
                //     ], 400);      
                
                // }
                // else{
        
                //     $sale->name = $request->name;
                //     $sale->email = $request->email;
                //     $sale->phone = $request->phone;
                //     $sale->password = $request->password;
                //     $sale->admin_id = $sale->admin_id;
                //     $sale->save();

                //     $result = new SaleResource($sale);
                    
                //     return response()->json([
                //         'status'  => 200,
                //         'success' => true,
                //         'message' => 'Sale user updated successfully.',
                //         'data'    => $result
                //     ], 200);
                // }

            }
            else{

                return response()->json([
                    'status' => 403,
                    'success' => false,
                    'message' => 'Forbidden!'
                ], 403);
    
            }

        }
    }
}
