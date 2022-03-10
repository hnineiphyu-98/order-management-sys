<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PercentageResource;
use App\Models\Percentage;
use Illuminate\Support\Facades\Validator;

class PercentageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $percentages = Percentage::all();
        $result = PercentageResource::collection($percentages);

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Percentages retrieved successfully.',
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
        $validator = Validator::make($request->all(),[
            'percent'  => 'required',
            'product_id' => 'required|numeric|min:0|not_in:0',
            'grade_id' => 'required|numeric|min:0|not_in:0'
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

            $percent = $request->percent;
            $product_id = $request->product_id;
            $grade_id = $request->grade_id;

            // Data Insert
            $percentage = new Percentage();
            $percentage->percent = $percent;
            $percentage->product_id = $product_id;
            $percentage->grade_id = $grade_id;
            $percentage->save(); 

            $result = new PercentageResource($percentage);

            return response()->json([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Percentage created successfully.',
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
        $percentage = Percentage::find($id);

        if (is_null($percentage)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Percentage not found.'
            ]);

        }else{
            
            $result = new PercentageResource($percentage);

            return response()->json([
                'status'    =>  200,
                'success'   =>  true,
                'message'   =>  'Percentage retrieved successfully.',
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
        $percentage = Percentage::find($id);

        if (is_null($percentage)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Percentage not found.'
            ]);

        }
        else{

            $validator = Validator::make($request->all(),[
                'percent'  => 'required',
                'product_id' => 'required|numeric|min:0|not_in:0',
                'grade_id' => 'required|numeric|min:0|not_in:0'
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

                $percent = $request->percent;
                $product_id = $request->product_id; 
                $grade_id = $request->grade_id; 

                // Data insert
                $percentage = Percentage::find($id);
                $percentage->percent = $percent;
                $percentage->product_id = $product_id;
                $percentage->grade_id = $grade_id;
                $percentage->save();

                $result = new PercentageResource($percentage);

                return response()->json([
                    'status'  => 200,
                    'success' => true,
                    'message' => 'Percentage updated successfully.',
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
        $percentage = Percentage::find($id);

        if (is_null($percentage)) {

            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Percentage not found.'
            ]);

        }
        else{

            $percentage->delete();

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Percentage deleted successfully.'
            ]);

        }
    }
}
