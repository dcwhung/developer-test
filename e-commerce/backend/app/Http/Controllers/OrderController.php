<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\PaymentRequest;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->isAdmin)
        {
            $orders = Order::with('orderItems')->get();
            return response()->json($orders);
        }
        else
        {
            return response()->json(['message' => 'You have no permission'], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try
        {
            $user = auth()->user();
            $order = Order::with('orderItems')->findOrFail($id);

            // -- Only admin or order owner can view the order details -- 
            if ($user->isAdmin || $order->user_id == $user->id)
                return response()->json($order);
            else
                return response()->json(['message' => 'You have no permission to view this order'], Response::HTTP_FORBIDDEN);
        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function placeOrder(OrderRequest $request)
    {
        $user = auth()->user();
        
        // -- Check enough stock first -- 
        foreach ($request->items as $itemData)
        {
            $item = Item::findOrFail($itemData['item_id']);
    
            if ($item->stock < $itemData['quantity'])
                return response()->json(['message' => 'Insufficient stock for item: '.$item->name.', '.$item->stock.' in stock only.'], Response::HTTP_BAD_REQUEST);
        }        

        // -- Prepare order -- 
        $total_amount = 0;

        $order = new Order();
        $order->user_id = $user->id;
        $order->total_amount = $total_amount;
        $order->status = 'pending';
        $order->save();

        foreach ($request->items as $itemData)
        {
            $quantity = $itemData['quantity'];
            $item = Item::findOrFail($itemData['item_id']);

            $subtotal = $item->price * $quantity;

            // -- Place order -- 
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->item_id = $item->id;
            $orderItem->quantity = $quantity;
            $orderItem->subtotal = $subtotal;
            $orderItem->save();

            $total_amount += $subtotal;

            // -- Update stock -- 
            $item->stock -= $quantity;
            $item->save();
        }

        // -- Update order amount -- 
        $order->total_amount = $total_amount;
        $order->save();
        
        return response()->json(['message' => 'Order placed successfully'], Response::HTTP_CREATED);
    }

    public function processPayment(PaymentRequest $request)
    {
        try
        {
            $user = auth()->user();
            $order = Order::findOrFail($request->order_id);

            // -- Double check order owner -- 
            if ($order->user_id == $user->id)
            {
                // -- Process payment only for pending & failed -- 
                if ($order->status !== 'completed')
                {
                    $isPaymentSuccess = rand(0, 1);
                    if ($isPaymentSuccess)
                    {
                        $order->status = 'completed';
                        $order->save();
                        return response()->json(['message' => 'Payment completed'], Response::HTTP_CREATED);
                    }
                    else
                    {
                        $order->status = 'failed';
                        $order->save();
                        return response()->json(['message' => 'Payment failed'], Response::HTTP_BAD_REQUEST);
                    }
                }
                else
                {
                    return response()->json(['message' => 'Payment has settled already'], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json(['message' => 'Order information not matched'], Response::HTTP_BAD_REQUEST);
            }

        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
