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
        $products = Products::with('feedback')->get();
        $categories = Categories::all();
        $locations = Location::all();

        if ($request->has('category') && $request->category != '') {
            $products = $products->where('category_id', $request->category);
        }
        if ($request->has('location') && $request->location != '') {
            $products = $products->where('location_id', $request->location);
        }
        return view('customer.products', compact('products', 'categories', 'locations'));
    }

    public function cart()
    {
        $cartItems = OrderItems::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'on-cart'))->get();
        $couriers = Courier::all();
        $seller = Seller::where('user_id', Auth::id())->get();
        return view('customer.cart', compact('cartItems', 'couriers', 'seller'));
    }

    public function orderHistory()
    {
        $orders = Orders::withCount('orderItems')->where('user_id', Auth::id())->paginate(10);
        $categories = Categories::all();
        $couriers = Courier::all();
        return view('customer.order-history', compact('orders', 'categories', 'couriers'));
    }

    public function addToCart(Request $request)
    {
        $result = (new CustomerService())->addToCart($request);
        return redirect()->back()->with('success', 'Product added to cart successfully');
    }

    public function removeCart(Request $request)
    {
        $result = (new CustomerService())->removeCart($request);
        return redirect()->back()->with('success', 'Product removed from cart successfully');
    }

    public function cancelOrder(Request $request)
    {
        $result = (new CustomerService())->cancelOrder($request);
        return redirect()->back()->with('success', 'Order cancelled successfully');
    }
    
    public function updateSelectionCart(Request $request)
    {
        $result = (new CustomerService())->updateSelectionCart($request);
        return redirect()->back()->with('success', 'Product updated in cart successfully');
    }

    public function updateCart(Request $request)
    { 
        $result = (new CustomerService())->updateCart($request);
        return redirect()->back()->with('success', 'Product updated in cart successfully');
    }

    public function checkout(Request $request)
    {
        $result = (new CustomerService())->checkout($request);
        return redirect()->back()->with('success', 'Checkout successful');
    }

    public function trackingPending()
    {
        $cartItems = OrderItems::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'pending'))->get();
        return view('customer.tracking.pending', compact('cartItems'));
    }

    public function trackingProcessed()
    {
        $cartItems = OrderItems::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'processing'))->get();
        return view('customer.tracking.processed', compact('cartItems'));
    }

    public function trackingToReceive()
    {
        $cartItems = OrderItems::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'receiving'))->get();
        return view('customer.tracking.receiving', compact('cartItems'));
    }

    public function trackingCancelled()
    {
        $cartItems = OrderItems::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'cancelled'))->get();
        return view('customer.tracking.cancelled', compact('cartItems'));
    }

    public function trackingDelivered()
    {
        $cartItems = OrderItems::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'delivered'))->get();
        $feedback = Feedback::where('user_id', Auth::id())->get();
        return view('customer.tracking.delivered', compact('cartItems', 'feedback'));
    }

    public function uploadFeedback(Request $request)
    {
        $result = (new CustomerService())->uploadFeedback($request);
        return redirect()->back()->with('success', 'Feedback uploaded successfully');
    }
}
