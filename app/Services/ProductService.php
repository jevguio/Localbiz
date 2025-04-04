<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Location;
use App\Models\Products;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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
            \Log::info($productData);
            //best_before_date
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
            Log::info($request->all());
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Generate a unique filename
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public disk (accessible from 'storage' symlink)
                $path = $image->storeAs('assets', $filename, 'public');


                // Log the storage path
                Log::info('Stored at: ' . $path);

                // Save only the relative path in the database
                $product->image =   $filename;
                Log::info('Saved Image Path: ' . $product->image);
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
    public function archiveProduct($id)
    {
        try {
            $product = Products::find($id);

            if ($product) {
                $product->is_active = false;
                $product->save();
            }

            session()->flash('success', 'Product archive successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product archive successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to archive product. ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to archive product.',
            ]);
        }
    }
    public function unarchiveProduct($id)
    {
        try {
            $product = Products::find($id);

            if ($product) {
                $product->is_active = true;
                $product->save();
            }

            session()->flash('success', 'Product un-archive successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product un-archive successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to un-archive product. ' . $e->getMessage());
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to un-archive product.',
            ]);
        }
    }
}
