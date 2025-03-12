<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SellerService
{
    public function upload(Request $request)
    {
        try {
            $seller = Seller::where('user_id', Auth::user()->id)->first();

            if (!$seller) {
                return response()->json([
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::FAILED_CODE,
                    'message' => 'Seller not found.',
                ]);
            }

            // if ($request->hasFile('document_file')) {
            //     $document_file = $request->file('document_file');
            //     $document_file->move(public_path('seller/documents/'), $document_file->getClientOriginalName());
            //     $seller->document_file = $document_file->getClientOriginalName();
            // }

            if ($request->hasFile('document_file')) {
                $documentFiles = $seller->document_files ? json_decode($seller->document_files, true) : [];
            
                foreach ($request->file('document_file') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('seller/documents/'), $filename);
                    $documentFiles[] = $filename; // Add new file to the array
                }
            
                // Convert back to JSON and save
                $seller->document_file = json_encode($documentFiles);
                $seller->save();
            }
            
            
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logo->move(public_path('seller/logo/'), $logo->getClientOriginalName());
                $seller->logo = $logo->getClientOriginalName();
            }

            $seller->is_approved = 0;
            $seller->save();

            session()->flash('success', 'Document uploaded successfully');
            return response()->json([
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::SUCCESS_CODE,
                'message' => 'Product updated successfully.',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload document.');
            \Log::info(MyConstant::FAILED_CODE);
            \Log::info($e);
            return response()->json([
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::FAILED_CODE,
                'message' => 'Failed to upload document.',
            ]);
        }
    }
}
