<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Location;
use App\Models\ProductImage;
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

        \Log::info($request->all());
        try {
            $seller = Seller::where('user_id', Auth::user()->id)->first();
            if (!$seller) {

                \Log::info("No seller");
                \Log::info($request->all());
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Seller not found',
                ]);
            }

            \Log::info("Has seller");
            // if (!$location) {
            //     \Log::info("No Location");
            //     return response()->json([
            //         'error_code' => MyConstant::FAILED_CODE,
            //         'status_code' => MyConstant::FAILED_CODE,
            //         'message' => 'Location not found',
            //     ]);
            // }

            \Log::info("Has Location");
            $productData = $request->all();
            $productData['location_id'] = 1;
            $productData['seller_id'] = $seller->id;
            $productData['image'] = ' ';
            \Log::info($productData);
            //best_before_date
            $product = Products::create($productData);

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $file) {

                    \Log::info($file);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('assets'), $filename);

                    // Save each image filename to the database if needed
                    // For example, using a ProductImage model:
                    ProductImage::create([
                        'product_id' => $product->id,
                        'filename' => $filename
                    ]);

                    \Log::info("Image Uploaded and added");
                }
            } else {

                \Log::info("No Image");
            }


            session()->flash('success', 'Product added successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product added successfully',
            ]);
        } catch (\Exception $e) {
            // session()->flash('error', 'Failed to add product');

            \Log::error('Failed to add product: ' . $e->getMessage());
            \Log::error($e->getTraceAsString()); // This shows exactly where the error happened
            // Log::error('Failed to add product: ' . $e->getMessage());
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
                foreach ($request->file('image') as $file) {

                    // Generate a unique filename
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();



                    // Save only the relative path in the database
                    $product->image = $filename;
                    $file->move(public_path('assets'), $filename);

                    // Save each image filename to the database if needed
                    // For example, using a ProductImage model:
                    ProductImage::create([
                        'product_id' => $product->id,
                        'filename' => $filename
                    ]);

                    \Log::info("Image Uploaded and added");
                }
            } else {

                \Log::info("No Image");
            }

            $product->location_id = 1;
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
