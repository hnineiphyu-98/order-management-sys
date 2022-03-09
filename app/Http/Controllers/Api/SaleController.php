<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Http\Resources\SaleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        
        $validator = Validator::make($request->all(), [
            'name' 		=> 'required',
            'phone'    	=> 'required|unique:sales',
            'email' 	=> 'required|email|unique:sales',
            'password' 	=> 'required',
        ]);

        if($validator->fails()){
            $message = 'Validation Error.';
            $status = 400;

            $response = [
                'status'  => $status,
                'success' => false,
                'data'    => $validator->errors(),
                'message' => $message,
            ];

            return response()->json($response, 400);       
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


            $message = 'Sale created successfully.';
            $status = 200;
            $result = new SaleResource($sale);

            $response = [
                'status'  => $status,
                'success' => true,
                'data'    => $result,
                'message' => $message,
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
