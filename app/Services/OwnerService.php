<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Cashier;
use App\Models\Feedback;
use App\Models\Government;
use App\Models\OrderItem;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Reports;
use App\Models\Rider;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OwnerService
{
    public function storeAccount($data)
    {
        try {
            $user = User::create($data);

            if ($user->role == 'Seller') {
                $seller = Seller::create([
                    'user_id' => $user->id,
                    'is_approved' => 0,
                ]);
            }

            if ($user->role == 'DeliveryRider') {
                $rider = Rider::create([
                    'user_id' => $user->id,
                    'is_approved' => 0,
                ]);
            }

            session()->flash('success', 'Account created successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Account created successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create account.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to create account.',
            ]);
        }
    }

    public function updateAccount($id, $request)
    {
        try {
            $user = User::find($id);
            $user->update($request->except('password'));

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            session()->flash('success', 'Account updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Account updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update account.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update account.',
            ]);
        }
    }
    public function ToggleAccount($id, $request)
    {
        try {
            $user = User::find($id);
            $user->update(['is_active' => $user->is_active==1? 2:1]);
            

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            session()->flash('success', 'Account updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Account updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update account.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update account.',
            ]);
        }
    }
    public function destroyAccount($id)
    {
        try {
            $user = User::find($id);
    
            if ($user->role == 'Seller') {
                $seller = Seller::where('user_id', $id)->first();
    
                if ($seller) {
                    OrderItems::whereHas('product', function ($query) use ($seller) {
                        $query->where('seller_id', $seller->id);
                    })->delete();
    
                    Products::where('seller_id', $seller->id)->delete();
    
                    Orders::whereHas('orderItems', function ($query) use ($seller) {
                        $query->whereHas('product', function ($q) use ($seller) {
                            $q->where('seller_id', $seller->id);
                        });
                    })->delete();
    
                    Reports::where('seller_id', $seller->id)->delete();
                    Government::where('seller_id', $seller->id)->delete();
                    Rider::where('seller_id', $seller->id)->delete();
                    Cashier::where('seller_id', $seller->id)->delete();
    
                    $seller->delete();
                }
            }
    
            if ($user->role == 'DeliveryRider') {
                $rider = Rider::where('user_id', $id)->first();
                if ($rider) {
                    Government::where('rider_id', $rider->id)->delete();
                    $rider->delete();
                }
            }
    
            Feedback::where('user_id', $id)->delete();
            $user->delete();
    
            session()->flash('success', 'Account deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Account deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete account.');
            Log::error('Failed to delete account: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete account.',
            ]);
        }
    }    
}
