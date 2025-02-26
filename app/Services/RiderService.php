<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Seller;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RiderService
{
    public function storeRider($request)
    {
        try {
            $seller = Seller::where('user_id', Auth::user()->id)->first();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'address' => $request->address,
                'phone' => $request->phone,
                'role' => 'DeliveryRider',
                'is_active' => 1,
            ]);

            $rider = Rider::create([
                'user_id' => $user->id,
                'seller_id' => $seller->id,
                'is_approved' => 0,
            ]);

            session()->flash('success', 'Rider added successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Rider added successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Internal Server Error.');
            Log::error('Failed to add rider: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error.',
            ]);
        }
    }

    public function updateRider($request, $id)
    {
        try {
            $rider = Rider::find($id);
            $rider->update([
                'is_approved' => $request->is_approved,
            ]);

            session()->flash('success', 'Rider updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Rider updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Internal Server Error.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error.',
            ]);
        }
    }

    public function destroyRider($id)
    {
        try {
            $rider = Rider::find($id);
            $rider->delete();

            session()->flash('success', 'Rider deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Rider deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete rider.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete rider.',
            ]);
        }
    }

    public function upload(Request $request)
    {
        try {
            $rider = Rider::where('user_id', Auth::user()->id)->first();

            if (!$rider) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Rider not found.',
                ]);
            }

            if ($request->hasFile('document_file')) {
                $document_file = $request->file('document_file');
                $document_file->move(public_path('rider/documents/'), $document_file->getClientOriginalName());
                $rider->document_file = $document_file->getClientOriginalName();
            }

            $rider->is_approved = 0;
            $rider->save();

            session()->flash('success', 'Document uploaded successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Document uploaded successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload document.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to upload document.',
            ]);
        }
    }
}
