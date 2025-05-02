<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Courier;
use App\Models\Feedback;
use App\Models\Location;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Seller;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function products(Request $request)
    {
        $products = Products::with(['feedback','images'])->where('is_active', '=', true)->latest()->get();
        $categories = Categories::all();
        $locations = Location::all();
        $seller = Seller::with('user')->get();
        $averageRating = Products::with('feedback')
        ->where('is_active', '=', true)
        ->latest()->get()->avg('rating');


        if ($request->has('category') && $request->category != '') {
            $products = $products->where('category_id', $request->category);
        }
        if ($request->has('location') && $request->location != '') {
            $products = $products->where('location_id', $request->location);
        }
        if ($request->has('seller') && $request->seller != '') {
            $products = $products->where('seller_id', $request->seller);
        }
        return view('customer.products', compact('products','averageRating', 'seller', 'categories', 'locations'));
    }

    public function cart()
    {
        $cartItems = OrderItems::with([
            'product' => function ($query) {
                $query->where('is_active', true);
            }
        ])->with('product.images')->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'on-cart');
        })->latest()->get();

        // Check if all cart items have `is_active` as true
        $hasUncheckedItems = OrderItems::with([
            'product' => function ($query) {
                $query->where('is_active', true);
            }
        ])->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'on-cart');
        })->where('is_checked', false)->exists();

        // Check if all cart items have `is_active` as true
        $totalItems = OrderItems::with([
            'product' => function ($query) {
                $query->where('is_active', true);
            }
        ])->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'on-cart');
        })->count();

        $checkedItems = OrderItems::with([
            'product' => function ($query) {
                $query->where('is_active', true);
            }
        ])->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'on-cart');
        })->where('is_checked', true)->count();

        $allActive = ($totalItems > 0) && ($totalItems == $checkedItems);
        $hasActive = $checkedItems > 0 ? true : false;
        $is_active_checkout = $hasActive;
        $couriers = Courier::all();

        $seller = Seller::where('user_id', Auth::id())->get();
        return view('customer.cart', compact('cartItems', 'couriers', 'seller', 'allActive', 'is_active_checkout'));
    }

    public function orderHistory()
    {
        $orders = Orders::withCount('orderItems')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['delivered', 'cancelled']) // Correct way to filter multiple statuses
            ->latest()
            ->paginate(15);
        $categories = Categories::all();
        $couriers = Courier::all();
        return view('customer.order-history', compact('orders', 'categories', 'couriers'));
    }

    public function addToCart(Request $request)
    {
        $result = (new CustomerService())->addToCart($request);
        return redirect()->back()->with('success', 'Added to cart successfully');
    }

    public function removeCart(Request $request)
    {
        $result = (new CustomerService())->removeCart($request);
        return redirect()->back()->with('success', 'Product removed successfully');
    }

    public function cancelOrder(Request $request)
    {
        $result = (new CustomerService())->cancelOrder($request);
        return redirect()->back()->with('success', 'Order cancelled successfully');
    }

    public function updateSelectionCart(Request $request)
    {
        $result = (new CustomerService())->updateSelectionCart($request);
        return redirect()->back();
    }

    public function updateSelectAllCart(Request $request)
    {
        $result = (new CustomerService())->updateSelectAllCart($request);
        return redirect()->back();
    }

    public function updateCart(Request $request)
    {
        $result = (new CustomerService())->updateCart($request);
        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $result = (new CustomerService())->checkout($request);
        return redirect()->back()->with('success', 'Checkout successful');
    }

    public function trackingAll()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $cartItems = OrderItems::with(['order.payments', 'product'])->whereHas(
            'order.payments',
            fn($query) => $query->where('user_id', Auth::id())
                ->where('status', '!=', 'on-cart')
        )
            ->latest()
            ->get();
        return view('customer.tracking.all', compact('cartItems', 'categories', 'couriers'));
    }


    public function trackingPending()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $cartItems = OrderItems::whereHas(
            'order.payments',
            fn($query) => $query->where('user_id', Auth::id())
                ->where('status', 'pending')
        )
            ->latest()
            ->get();
        return view('customer.tracking.pending', compact('cartItems', 'categories', 'couriers'));
    }

    public function trackingProcessed()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $cartItems = OrderItems::whereHas('order.payments', fn($query) => $query->where('user_id', Auth::id())->where('status', ['processing']))->latest()->get();
        return view('customer.tracking.processed', compact('cartItems', 'categories', 'couriers'));
    }

    public function trackingToReceive()
    {
        $couriers = Courier::all();
        $categories = Categories::all();
        $cartItems = OrderItems::whereHas('order.payments', fn($query) => $query->where('user_id', Auth::id())->where('status', 'receiving'))->latest()->get();
        return view('customer.tracking.receiving', compact('cartItems', 'categories', 'couriers'));
    }

    public function trackingCancelled()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $cartItems = OrderItems::whereHas('order.payments', fn($query) => $query->where('user_id', Auth::id())->where('status', 'cancelled'))->latest()->get();
        return view('customer.tracking.cancelled', compact('cartItems', 'categories', 'couriers'));
    }

    public function trackingDelivered()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $cartItems = OrderItems::whereHas('order.payments', fn($query) => $query->where('user_id', Auth::id())->where('status', 'delivered'))->latest()->get();
        $feedback = Feedback::where('user_id', Auth::id())->get();
        return view('customer.tracking.delivered', compact('cartItems', 'feedback', 'categories', 'couriers'));
    }

    public function uploadFeedback(Request $request)
    {
        $result = (new CustomerService())->uploadFeedback($request);
        return redirect()->back()->with('success', 'Feedback uploaded successfully');
    }
}
