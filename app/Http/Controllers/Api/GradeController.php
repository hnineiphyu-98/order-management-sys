<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GradeResource;
use App\Models\Grade;
use Illuminate\Support\Facades\Validator;

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
        $grades = Grade::all();
        $result = GradeResource::collection($grades);
       

        return response()->json([
            'stasus'  => 200,
            'success' => true,
            'message' => 'Grades retrieved successfully.',
            'data'    => $result
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
        $validator = Validator::make($request->all(),[
            'level'  => 'required|string|unique:grades'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'    =>  400,
                'success'   =>  false,
                'message'   =>  'Validation Error.',
                'data'      =>  $validator->errors()
            ], 400);

        }
        else{
            $grade_level = $request->level;

            // Data Insert
            $grade = new Grade();
            $grade->level = $grade_level;
            $grade->save(); 

            $result = new GradeResource($grade);

            return response()->json([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Grade created successfully.',
                'data'      => $result,
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
        $grade = Grade::find($id);

        if (is_null($grade)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Grade not found.'
            ], 404);
            
        }else{
            
            $result = new GradeResource($grade);

            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Grade retrieved successfully.',
                'data'      =>  $result
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
        $grade = Grade::find($id);

        if (is_null($grade)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Grade not found.'
            ], 404);

        }
        else{

            $validator = Validator::make($request->all(),[
                'level'  => 'required|string|unique:grades'
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status'    =>  400,
                    'success'   =>  false,
                    'message'   =>  'Validation Error.',
                    'data'      =>  $validator->errors()
                ], 400);

            }
            else{

                $level = $request->level;
                
                $grade = Grade::find($id);

                $grade->level = $level;
                $grade->save();

                $result = new GradeResource($grade);

                return response()->json([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'Grade updated successfully.',
                    'data'      => $result
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
        $grade = Grade::find($id);
        if (is_null($grade)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Grade not found.'
            ], 404);

        }
        else{

            $grade->delete();

            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Grade deleted successfully.'
            ], 200);

        }
    }
}
