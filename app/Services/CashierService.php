<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Cashier;
use App\Models\Rider;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CashierService
{
    public function storeCashier($request)
    {
        try {
            $seller = Seller::where('user_id', Auth::user()->id)->first();
            if (User::where('email', $request->email)->exists()) {
                session()->flash('error', 'An account with this email is Already exist');
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Email already exists.',
                ], 400);
            }
            $user = User::create([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'address' => $request->address,
                'phone' => $request->phone,
                'role' => 'Cashier',
                'is_active' => 1,
            ]);

            $cashier = Cashier::create([
                'user_id' => $user->id,
                'seller_id' => $seller->id,
                'is_approved' => 0,
            ]);

            session()->flash('success', 'Cashier added successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Cashier added successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::info($e);
            session()->flash('error', 'Failed to add cashier.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to add cashier.',
            ]);
        }
    }

    public function ToggleRider($request, $id)
    {
        try {
            $rider = Rider::find($id);
            $rider->update([
                'is_approved' => !$rider->is_approved,
            ]);

            session()->flash('success', 'Rider updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Rider updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update Rider: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update Rider.',
            ]);
        }
    }
    public function ToggleCashier($request, $id)
    {
        try {
            $cashier = Cashier::find($id);
            $cashier->update([
                'is_approved' => !$cashier->is_approved,
            ]);

            session()->flash('success', 'Cashier updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Cashier updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update cashier: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update cashier.',
            ]);
        }
    }

    public function updateCashier($request, $id)
    {
        try {
            $cashier = Cashier::find($request->id);
            $user = User::find($cashier->user_id);
            if ($request->is_approved) {

                $cashier->update([
                    'is_approved' => $request->is_approved,
                ]);
            }
            $user->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
            ]);
            session()->flash('success', 'Cashier updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Cashier updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update cashier: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update cashier.',
            ]);
        }
    }

    public function destroyCashier($id)
    {
        try {
            $cashier = Cashier::find($id);
            $cashier->delete();

            session()->flash('success', 'Cashier deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Cashier deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete cashier.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete cashier.',
            ]);
        }
    }

    public function upload(Request $request)
    {
        try {
            $cashier = Cashier::where('user_id', Auth::user()->id)->first();

            if (!$cashier) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Cashier not found.',
                ]);
            }

            if ($request->hasFile('document_file')) {
                $document_file = $request->file('document_file');
                $document_file->move(public_path('cashier/documents/'), $document_file->getClientOriginalName());
                $cashier->document_file = $document_file->getClientOriginalName();
            }

            $cashier->is_approved = 0;
            $cashier->save();

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
