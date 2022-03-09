<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GradeResource;
use App\Models\Grade;
use Validator;

class GradeController extends Controller
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
        $validator = Validator::make($request->all(),[
            'level'  => 'required|string|max:255|unique:grades'
        ]);

        if ($validator->fails()) {
            $status = 400;
            $message = 'Validation Error.';

            $response = [
                'status'    =>  $status,
                'success'   =>  false,
                'message'   =>  $message,
                'data'      =>  $validator->errors(),
            ];

            return response()->json($response, 400);
        }
        else{
            $grade_level = $request->level;

            // Data Insert
            $grade = new Grade();
            $grade->level = $grade_level;
            $grade->save(); 

            $status = 200;
            $message = 'Grade created successfully.';
            $result = new GradeResource($grade);

            $response = [
                'success'   => true,
                'status'    => $status,
                'message'   => $message,
                'data'      => $result,
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
