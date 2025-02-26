<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Location;
use App\Models\Products;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function storeProduct($request)
    {
        try {
            $seller = Seller::where('user_id', Auth::user()->id)->first();
            if (!$seller) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Seller not found',
                ]);
            }

            $location = Location::find($request->location);
            if (!$location) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Location not found',
                ]);
            }

            $productData = $request->all();
            $productData['seller_id'] = $seller->id;
            $productData['location_id'] = $location->id;

            $product = Products::create($productData);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $image->getClientOriginalName();
                $image->move(public_path('assets'), $filename);
                $product->image = $filename;
                $product->save();
            }

            session()->flash('success', 'Product added successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product added successfully',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add product');
            Log::error('Failed to add product: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to add product',
            ]);
        }
    }

    public function updateProduct($request, $id)
    {
        try {
            $product = Products::find($id);
            $product->update($request->all());

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/assets/', $image->getClientOriginalName());
                $product->image = $image->getClientOriginalName();
            }

            $product->location_id = $request->location;
            $product->save();

            session()->flash('success', 'Product updated successfully');

            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update product.');
            Log::error('Failed to update product: ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update product.',
            ]);
        }
    }

    public function destroyProduct($id)
    {
        try {
            $product = Products::find($id);
            $product->delete();

            session()->flash('success', 'Product deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete product. ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete product.',
            ]);
        }
    }
}
