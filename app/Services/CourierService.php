<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Courier;

class CourierService
{
    public function storeCourier($data)
    {
        try {
            $courier = Courier::create($data);

            session()->flash('success', 'Courier created successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Courier created successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create courier.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to create courier.',
            ]);
        }
    }

    public function updateCourier($id, $data)
    {
        try {
            $courier = Courier::find($id);
            $courier->update($data);

            session()->flash('success', 'Courier updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Courier updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update courier.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update courier.',
            ]);
        }
    }

    public function destroyCourier($id)
    {
        try {
            $courier = Courier::find($id);
            $courier->delete();

            session()->flash('success', 'Courier deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Courier deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete courier.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete courier.',
            ]);
        }
    }
}
