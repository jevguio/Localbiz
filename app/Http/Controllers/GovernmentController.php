<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use App\Services\GovernmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sellers = Seller::whereHas('user', function($query) {
            $query->where('role', 'Seller');
        })->paginate(10);

        return view('government.approval', compact('sellers', 'user'));
    }

    public function updateApproval(Request $request, $id)
    {
        $result = (new GovernmentService())->updateApproval($id, $request->is_approved);
        return redirect()->back()->with('success', 'Approval updated successfully');
    }
}
