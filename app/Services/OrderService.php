<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Orders;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    public function updateOrder($request, $id)
    {
        try {
            $order = Orders::find($id);
            if (!$order) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Order not found',
                ]);
            }
            $order->status = $request->status;

            if ($request->hasFile('proof_of_delivery')) {
                $image = $request->file('proof_of_delivery');
                $filename = $image->getClientOriginalName();
                $image->move(public_path('delivery_receipt'), $filename);
                $order->proof_of_delivery = $filename;
            }

            $order->save();

            session()->flash('success', 'Proof of Delivery updated successfully.');

            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Proof of Delivery updated successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Order update failed: ' . $e->getMessage()); // Log for debugging
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update proof of delivery.',
            ]);
        }
    }
}
