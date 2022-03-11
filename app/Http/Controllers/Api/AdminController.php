<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function admin_create(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'name' 		=> 'required',
            'phone'    	=> 'required|unique:admins',
            'email' 	=> 'required|email|unique:admins',
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

            $admin = Admin::create([
                'name'      =>  $name,
                'email'     =>  $email,
                'phone'     =>  $phone,
                'password'  =>  Hash::make($password)
            ]);

            
            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Admin user created successfully.',
                'data'    => $admin
            ], 200);

        }
    }

    public function admin_update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (is_null($admin)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Admin user not found.'
            ], 404);
            
        }else{

            if ($id == Auth::guard('api')->user()->id) {

                $admin->update($request->all());
                    
                
                return response()->json([
                    'status'  => 200,
                    'success' => true,
                    'message' => 'Admin user updated successfully.',
                    'data'    => $admin
                ], 200);

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
