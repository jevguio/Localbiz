<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GovernmentService
{
    public function updateApproval($id, $is_approved)
    {
        try {
            $seller = Seller::find($id);

            $seller->update([
                'is_approved' => $is_approved,
            ]);

            $seller->user->update([
                'is_active' => $is_approved,
            ]);

            session()->flash('success', 'Seller updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Seller updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update seller.');
            Log::error('Failed to update seller: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update seller.',
            ]);
        }
    }
}
