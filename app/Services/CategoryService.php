<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Categories;

class CategoryService
{
    public function storeCategory($request)
    {
        try {
            $category = Categories::create($request->all());

            session()->flash('success', 'Category added successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Category added successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add category.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to add category.',
            ]);
        }
    }

    public function updateCategory($request, $id)
    {
        try {
            $category = Categories::find($id);
            $category->update($request->all());

            session()->flash('success', 'Category updated successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Category updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update category.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to update category.',
            ]);
        }
    }

    public function destroyCategory($id)
    {
        try {
            $category = Categories::find($id);
            $category->delete();

            session()->flash('success', 'Category deleted successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete category.');
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to delete category.',
            ]);
        }
    }
}