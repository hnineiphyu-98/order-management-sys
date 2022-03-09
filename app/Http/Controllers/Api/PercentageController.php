<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PercentageResource;
use App\Models\Percentage;
use Validator;

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
        $message = 'Percentages retrieved successfully.';
        $status = 200;

        $response = [
            'status'  => $status,
            'success' => true,
            'message' => $message,
            'data'    => $result,
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
        $validator = Validator::make($request->all(),[
            'percent'  => 'required',
            'product_id' => 'required|numeric|min:0|not_in:0',
            'grade_id' => 'required|numeric|min:0|not_in:0'
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
            $percent = $request->percent;
            $product_id = $request->product_id;
            $grade_id = $request->grade_id;

            // Data Insert
            $percentage = new Percentage();
            $percentage->percent = $percent;
            $percentage->product_id = $product_id;
            $percentage->grade_id = $grade_id;
            $percentage->save(); 

            $status = 200;
            $message = 'Percentage created successfully.';
            $result = new PercentageResource($percentage);

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
        $percentage = Percentage::find($id);

        if (is_null($percentage)) {
            # 404
            $status = 404;
            $message = 'Percentage not found.';

            $response = [
                'status'    => $status,
                'success'   => false,
                'message'   => $message
            ];

            return response()->json($response,404);
        }else{
            #200
            $status = 200;
            $message = 'Percentage retrieved successfully.';
            $result = new PercentageResource($percentage);

            $response = [
                'status'    =>  $status,
                'success'   =>  true,
                'message'   =>  $message,
                'data'      =>  $result
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
        $percentage = Percentage::find($id);

        if (is_null($percentage)) {

            $status = 404;
            $message = 'Percentage not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);

        }
        else{

            $validator = Validator::make($request->all(),[
                'percent'  => 'required',
                'product_id' => 'required|numeric|min:0|not_in:0',
                'grade_id' => 'required|numeric|min:0|not_in:0'
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

                return response()->json($response,400);
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

                $status = 200;
                $result = new PercentageResource($percentage);
                $message = 'Percentage updated successfully.';

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
        $percentage = Percentage::find($id);

        if (is_null($percentage)) {

            $status = 404;
            $message = 'Percentage not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);
        }
        else{

            $percentage->delete();

            $status = 200;
            $message = 'Percentage deleted successfully.';

            $response = [
                'status'  => $status,
                'success' => true,
                'message' => $message,
            ];


            return response()->json($response, 200);
        }
    }
}
