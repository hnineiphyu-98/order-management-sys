<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Percentage;
use App\Models\User;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    //
    public function order(Request $request)
    {   
        $user_id = $request->user_id;
        $user_grade_id = User::where('id', $user_id)->value('grade_id');

        $carts = $request->carts;
        $final_total = 0;
        foreach ($carts as $cart) {

            $product_id = $cart['product_id'];
            $product_price = $cart['product_price'];
            $qty = $cart['qty'];
            $unit_price = $product_price * $qty;

            
            $percent = Percentage::where('product_id', $product_id)
                                ->where('grade_id', $user_grade_id)
                                ->value('percent');

            $total = $unit_price - ($unit_price * ($percent)/100);

            $final_total+=$total;
            
        }
        $voucher_no = uniqid();
        $order = new Order;
        $order->voucher_no = $voucher_no;
        $order->total = $final_total;
        $order->status = 1;
        $order->user_id = $user_id;
        $order->save();

        foreach ($carts as $row) {
            $order->products()->attach($row['product_id'],['qty'=>$row['qty']]);
        }

        return response()->json([
            'massage' => 'Order Successfully created!'
        ]);

    }

    public function order_lists()
    {
        $orders = Order::all();
        $message = 'Orders retrieved successfully.';
        $status = 200;

        $response = [
            'status'    =>  $status,
            'success'   =>  true,
            'message'   =>  $message,
            'data'      =>  $orders,
        ];

        return response()->json($response, 200);
    }

    public function order_history($id)      
    {
        $order = Order::find($id);
        if (is_null($order)) {

            $status = 404;
            $message = 'This Order not found.';

            $response = [
                'status'  => $status,
                'success' => false,
                'message' => $message,
            ];

            return response()->json($response, 404);

        }
        else{

            $message = 'Order History retrieved successfully.';
            $result = new OrderResource($order);
            $status = 200;

            $response = [
                'status'  => $status,
                'success' => true,
                'message' => $message,
                'data'    => $result,
            ];
            // return response()->json(Auth::hasUser());
            
            return response()->json($response, 200);
        }
    }
}
