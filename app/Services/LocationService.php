<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Location;

class LocationService
{
    public function storeLocation($request)
    {
        try {
            $location = Location::create($request->all());

            session()->flash('success', 'Location added successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Location added successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add location.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to add location.',
            ]);
        }
    }

    public function updateLocation($request, $id)
    {
        try {
            $location = Location::find($id);
            $location->update($request->all());

            session()->flash('success', 'Location updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Location updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update location.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update location.',
            ]);
        }
    }

    public function destroyLocation($id)
    {
        try {
            $location = Location::find($id);
            $location->delete();

            session()->flash('success', 'Location deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Location deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete location.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete location.',
            ]);
        }
    }
}
