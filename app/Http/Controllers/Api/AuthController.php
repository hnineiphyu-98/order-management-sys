<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    //admin login and logout
    public function admin_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 400);

        }
        else{

            config(['auth.guards.admin-api.driver' => 'session']);
            if (Auth::guard('admin-api')->attempt([ 'phone' => request('phone'), 'password' => request('password') ])) {
                // dd(Auth::guard('admin')->user());
                $admin = Admin::select('admins.*')->find(Auth::guard('admin-api')->user()->id);
                $token = $admin->createToken('App',['admin'])->accessToken;

                return response()->json([
                    'result' => 1,
                    'status' => 200,
                    'success' => true,
                    'message' => 'Successful',
                    'data' => $admin,
                    'token' => $token
                ], 200);

            }
            else{

                return response()->json([
                    'status' => 401,
                    'success' => false,
                    'message' => 'Phone or Password Invalid!!'
                ], 401);
            
            }
        }
    }
    public function admin_logout()
    {
        // dd(Auth::guard('sale-api')->user());

        Auth::guard('admin-api')->user()->token()->revoke();

        Auth::guard('admin-api')->user()->token()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    
    }
    //sale login and logout
    public function sale_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 400,
                'success' => false,
                'massage' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);

        }
        else{
            
            config(['auth.guards.sale-api.driver' => 'session']);
            if(Auth::guard('sale-api')->attempt([ 'phone' => request('phone'), 'password' => request('password') ])){
                // dd(Auth::guard('sale-api')->user());

                $sale = Sale::select('sales.*')->find(Auth::guard('sale-api')->user()->id);
                $token = $sale->createToken('App', ['sale'])->accessToken;
                
                return response()->json([
                    'result' => 1,
                    'status' => 200,
                    'success' => true,
                    'message' => 'Successfull',
                    'data' => $sale,
                    'token' => $token
                ], 200);

            }
            else{

                return response()->json([
                    'status' => 401,
                    'success' => false,
                    'message' => 'Phone or Password Invalid!!'
                ], 401);
            
            }
        }
    }

    public function sale_logout()
    {
        // dd(Auth::guard('admin-api')->user()->token()->scopes[0]);

        Auth::guard('sale-api')->user()->token()->revoke();

        Auth::guard('sale-api')->user()->token()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);

    }

    //user login and logout
    public function user_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 400);

        }
        else{

            if (Auth::attempt(['phone' => request('phone'), 'password' => request('password')])) {
                $user = Auth::user();
                $token = $user->createToken('App', ['user'])->accessToken;

                return response()->json([
                    'result' => 1,
                    'status' => 200,
                    'success' => true,
                    'message' => 'Successful',
                    'data' => $user,
                    'token' => $token
                ], 200);

            }
            else{

                return response()->json([
                    'status' => 401,
                    'success' => false,
                    'message' => 'Phone or Password Invalid!!'
                ], 401);

            }
        }
    }

    public function user_logout()
    {
        // dd(Auth::user());

        Auth::user()->token()->revoke();

        Auth::user()->token()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
        
    }
}
