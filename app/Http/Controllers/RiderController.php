<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Rider;
use App\Models\Seller;
use App\Models\WalkinOrders;
use App\Services\OrderService;
use App\Services\RiderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    public function dashboard()
    {
        return view('rider.dashboard');
    }

    public function upload(Request $request)
    {
        $result = (new RiderService())->upload($request);
        return redirect()->back();
    }

    public function orders()
    {
        $rider = Auth::user()->rider;
        $seller_id = $rider->seller_id;

        if ($rider->is_approved == 0) {
            return redirect()->route('rider.dashboard')->with('error', 'Your documents are not approved yet.');
        }

        $orders = Orders::whereHas('orderItems.product', function ($query) use ($seller_id) {
            $query->where('seller_id', $seller_id);
        })
        ->latest()
            ->with(['user', 'orderItems.product', 'payments'])
            ->paginate(10);

        $orderItems = OrderItems::whereHas('product', function ($query) use ($seller_id) {
            $query->where('seller_id', $seller_id);
        })
        ->latest()
        ->get();

        $payments = Payments::all();
        $products = Products::all();
        $categories = Categories::all();

        return view('rider.orders', compact('orders', 'orderItems', 'payments', 'products', 'categories'));
    }

    public function updateOrder(Request $request, $id)
    {
        $result = (new OrderService())->updateOrder($request, $id);

        session()->flash('success', 'Item Delivered');
        return redirect()->back();
    }

    public function trackingPending()
    {
        $rider = Rider::where('user_id', Auth::user()->id)->first();
        if ($rider->is_approved == 0 || $rider->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('rider.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'pending');
        })
            ->whereHas('product', function ($query) use ($rider) {
                $query->where('seller_id', $rider->seller_id);
            })
            ->get();
        return view('rider.tracking.pending', compact('cartItems'));
    }

    public function trackingProcessed()
    {
        $rider = Rider::where('user_id', Auth::user()->id)->first();
        if ($rider->is_approved == 0 || $rider->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('rider.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'processing');
        })
            ->whereHas('product', function ($query) use ($rider) {
                $query->where('seller_id', $rider->seller_id);
            })
            ->get();
        return view('rider.tracking.processed', compact('cartItems'));
    }

    public function trackingToReceive()
    {
        $rider = Rider::where('user_id', Auth::user()->id)->first();
        if ($rider->is_approved == 0 || $rider->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('rider.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'receiving');
        })
            ->whereHas('product', function ($query) use ($rider) {
                $query->where('seller_id', $rider->seller_id);
            })
            ->get();
        return view('rider.tracking.receiving', compact('cartItems'));
    }

    public function trackingCancelled()
    {
        $rider = Rider::where('user_id', Auth::user()->id)->first();
        if ($rider->is_approved == 0 || $rider->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('rider.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'cancelled');
        })
            ->whereHas('product', function ($query) use ($rider) {
                $query->where('seller_id', $rider->seller_id);
            })
            ->get();
        return view('rider.tracking.cancelled', compact('cartItems'));
    }

    public function index()
    {
        $user = Auth::user()->load('rider');
        $seller_id = $user->rider->seller_id ?? null; // Use null-safe operator in case it's missing
        \Log::info(''. $user->id .' '. $seller_id);
        $orders = WalkinOrders::where('seller_id', '=', $seller_id)->get();

        return view('rider.tracking.walkinorder', compact('orders'));
    }

    public function trackingDelivered()
    {
        $rider = Rider::where('user_id', Auth::user()->id)->first();
        if ($rider->is_approved == 0 || $rider->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('rider.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'delivered');
        })
            ->whereHas('product', function ($query) use ($rider) {
                $query->where('seller_id', $rider->seller_id);
            })
            ->get();
        return view('rider.tracking.delivered', compact('cartItems'));
    }

    public function trackingCompleted()
    {
        $rider = Rider::where('user_id', Auth::user()->id)->first();
        if ($rider->is_approved == 0 || $rider->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('rider.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'completed');
        })
            ->whereHas('product', function ($query) use ($rider) {
                $query->where('seller_id', $rider->seller_id);
            })
            ->get();
        return view('rider.tracking.completed', compact('cartItems'));
    }
}
