<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // $this->middleware('admin');
        // $this->middleware('sale')->only('index', 'show', 'store');
    }
    public function index()
    {
        //
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
        dd("Hello");
        $validator = Validator::make($request->all(), [
            'name' 		=> 'required',
            'phone'    	=> 'required',
            'email' 	=> 'required|email|unique:users',
            'password' 	=> 'required',
            'shop_name' => 'required',
            'address'   => 'required',
            'grade_id' 	=> 'required|numeric|min:0|not_in:0'
        ]);

        if($validator->fails()){

            return response()->json([
                'status'  => 400,
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => $validator->errors()
            ]);       
        }
        else{

            $name = $request->name;
            $email = $request->email;
            $phone = $request->phone;
            $password = $request->password;
            $shop_name = $request->shop_name;
            $address = $request->address;
            $grade_id = $request->grade_id;

            // dd(Auth::guard('admin-api')->check());
            $admin_id = null;
            $sale_id = null;
            
            if (Auth::guard('admin-api')->check() && Auth::guard('admin-api')->user()->token()->scopes[0] == "admin") {
                $admin_id = Auth::guard('admin-api')->user()->id;
                // dd("admin");
            } elseif (Auth::guard('sale-api')->check() && Auth::guard('sale-api')->user()->token()->scopes[0] == "sale") {
                $sale_id = Auth::guard('sale-api')->user()->id;
                // dd("sale");
            }

            $user = User::create([
                'name'      =>  $name,
                'email'     =>  $email,
                'phone'     =>  $phone,
                'password'  =>  Hash::make($password),
                'shop_name' =>  $shop_name,
                'address'   =>  $address,
                'admin_id'  =>  $admin_id,
                'sale_id'   =>  $sale_id,
                'grade_id'  =>  $grade_id
            ]);

            $result = new UserResource($user);

            return response()->json([
                'status'  => 200,
                'success' => true,
                'data'    => $result,
                'message' => 'User created successfully.',
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
    }
}
