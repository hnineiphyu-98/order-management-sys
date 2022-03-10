<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Percentage;
use App\Models\User;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //
    public function order(Request $request)
    {   
        dd(Auth::guard('api')->user()->id);
        $user_id = Auth::guard('api')->user()->id;
        $user_grade_id = User::where('id', $user_id)->value('grade_id');
        // dd("grade_id = $user_grade_id ");

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
        $order->status = "Pending Order";
        $order->read_at = 1;
        $order->user_id = $user_id;
        $order->save();

        foreach ($carts as $row) {
            $order->products()->attach($row['product_id'],['qty'=>$row['qty']]);
        }

        return response()->json([
            'massage' => 'Order Successfully created!'
        ]);

    }

    public function order_update($id)
    {
        $order = Order::find($id);
        if ($order->status == "Pending Order") {
            
        }
    }

    public function order_lists()
    {
        $orders = Order::all();

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data'    => $orders
        ]);

    }

    public function order_history($id)      
    {
        $order = Order::find($id);
        if (is_null($order)) {

            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'This Order not found.'
            ]);

        }
        else{

            $result = new OrderResource($order);
            // return response()->json(Auth::hasUser());
            
            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Order History retrieved successfully.',
                'data'    => $result,
            ]);

        }
    }

    public function order_confirm($id)
    {
        $order = Order::find($id);
        if (is_null($order)) {

            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'This Order not found.'
            ]);

        }
        else{

            $order->status = "Order Confirm";
            $order->save();
            $result = new OrderResource($order);

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'This Order has been confirmed.',
                'data'    => $result
            ]);

        }
    }

    public function order_cancel($id)
    {
        $order = Order::find($id);
        if (is_null($order)) {

            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'This Order not found.'
            ]);

        }
        else{
            
            $order->status = "Order Cancel";
            $order->save();
            $result = new OrderResource($order);

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'This Order has been canceled.',
                'data'    => $result
            ]);
            
        }
    }
}
