<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Feedback;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public function addToCart($request)
    {
        try {
            $product = Products::findOrFail($request->product_id);

            if ($product->stock < 1) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                    'message' => 'Product is out of stock.',
                ]);
            }

            $order = Orders::create([
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'order_number' => 'ORD-' . rand(100000, 999999),
            ]);

            OrderItems::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'quantity' => 1,
                'price' => $product->price,
            ]);

            $order->total_amount = $order->orderItems->sum('price');
            $order->save();

            session()->flash('success', 'Product added to cart successfully. Your Order Number: ' . $order->order_number);
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product added to cart successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add product to cart. ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function cancelOrder($request)
    {
        try {
            $order = Orders::findOrFail($request->order_id);
            $order->status = 'cancelled';
            $order->save();

            session()->flash('success', 'Order cancelled successfully.');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Order cancelled successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel order. ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function removeCart($request)
    {
        try {
            $orderItem = OrderItems::findOrFail($request->id);
            $order = $orderItem->order;
    
            $orderItem->delete();

            if ($order && $order->orderItems()->count() === 0) {
                $order->delete();
            }
    
            session()->flash('success', 'Product removed from cart successfully.');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product removed from cart successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove product from cart.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }    

    public function updateSelectionCart($request)
    {
        try {
            $orderItem = OrderItems::findOrFail($request->id);
            $orderItem->is_checked = $request->is_checked;
            $orderItem->save();
            session()->flash('success', 'Product updated in cart successfully.');
            Log::info("success $request");
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product updated in cart successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Failed to update product in cart.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function updateCart($request)
    {
        try {
            $orderItem = OrderItems::findOrFail($request->id); 
            // $order = Orders::where('user_id', Auth::id())->where('status', 'pending')->firstOrFail();

            if ($request->increment) {
                $orderItem->increment('quantity');
            } elseif ($request->decrement && $orderItem->quantity > 1) {
                $orderItem->decrement('quantity');
            } 
            $orderItem->price = $orderItem->product->price * $orderItem->quantity;
            $orderItem->save();

            // $order->total_amount = $order->orderItems->sum('price');
            // $order->save();

            session()->flash('success', 'Product updated in cart successfully.');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product updated in cart successfully.',
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            session()->flash('error', 'Failed to update product in cart.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function checkout($request)
    {
        try {
            $orders = Orders::where('user_id', Auth::id())->where('status', 'on-cart')->get();
            $totalAmount = 0;

            $receipt = $request->file('receipt_file');
            $filename = $receipt->getClientOriginalName();
            $receipt->move(public_path('receipt_file'), $filename);

            foreach ($orders as $order) {
                $totalAmount += $order->total_amount;

                foreach ($order->orderItems as $orderItem) {
                    Payments::create([
                        'order_id' => $orderItem->order_id,
                        'customer_id' => Auth::id(),
                        'courier_id' => $request->courier_id,
                        'payment_method' => $request->payment_method,
                        'payment_amount' => $orderItem->price,
                        'receipt_file' => $filename,
                        'payment_date' => Carbon::now()->timezone('Asia/Manila'),
                        'paid_at' => Carbon::now()->timezone('Asia/Manila'),
                    ]);

                    $product = Products::findOrFail($orderItem->product_id);
                    $product->stock -= $orderItem->quantity;
                    $product->save();
                }

                $order->status = 'pending';
                $order->save();
            }

            session()->flash('success', 'Checkout successful for all orders.');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Checkout successful for all orders.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to checkout: ' . $e->getMessage());
            Log::error('Failed to checkout: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function uploadFeedback($request)
    {
        try {
            $orderItem = OrderItems::where('order_id', $request->order_id)->firstOrFail();

            $existingFeedback = Feedback::where('order_id', $orderItem->order_id)->where('product_id', $orderItem->product_id)->first();

            if ($existingFeedback) {
                $existingFeedback->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);

                session()->flash('success', 'Feedback updated successfully.');
                return response()->json([
                    'error_code' => MyConstant::SUCCESS_CODE,
                    'status_code' => MyConstant::SUCCESS_CODE,
                    'message' => 'Feedback updated successfully.',
                ]);
            } else {
                Feedback::create([
                    'order_id' => $orderItem->order_id,
                    'user_id' => Auth::id(),
                    'product_id' => $orderItem->product_id,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);

                session()->flash('success', 'Feedback uploaded successfully.');
                return response()->json([
                    'error_code' => MyConstant::SUCCESS_CODE,
                    'status_code' => MyConstant::SUCCESS_CODE,
                    'message' => 'Feedback uploaded successfully.',
                ]);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload/update feedback.');
            Log::error('Failed to upload/update feedback: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
