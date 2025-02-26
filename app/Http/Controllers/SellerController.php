<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\Exports\ProductsExport;
use App\Exports\ReportsExport;
use App\Exports\SalesExport;
use App\Models\Cashier;
use App\Models\Categories;
use App\Models\Courier;
use App\Models\Location;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Reports;
use App\Models\Rider;
use App\Models\Seller;
use App\Services\CashierService;
use App\Services\CategoryService;
use App\Services\LocationService;
use App\Services\ProductService;
use App\Services\RiderService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SellerController extends Controller
{
    public function index()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        return view('seller.dashboard', compact('seller'));
    }

    public function upload(Request $request)
    {
        $result = (new SellerService())->upload($request);
        return redirect()->back();
    }

    public function products()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        if (!$seller || !$seller->is_approved || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        if ($seller->user->is_active == 0) {
            session()->flash('error', 'Your account is not active. Please contact the administrator.');
            return redirect()->route('seller.dashboard');
        }
        $products = $seller->products()->orderBy('created_at', 'desc')->paginate(10);
        $categories = Categories::all();
        $locations = Location::all();
        return view('seller.products', compact('products', 'categories', 'locations'));
    }

    public function storeProduct(Request $request)
    {
        $result = (new ProductService())->storeProduct($request);
        return redirect()->back();
    }

    public function updateProduct(Request $request, $id)
    {
        $result = (new ProductService())->updateProduct($request, $id);
        return redirect()->back();
    }

    public function exportProducts()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        $fileName = $seller->user->name . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new ProductsExport(), $filePath, 'public');

        $report = Reports::create([
            'seller_id' => $seller->id,
            'report_name' => 'Products Report',
            'report_type' => 'excel',
            'content' => $fileName,
        ]);

        return Excel::download(new ProductsExport(), $fileName);
    }

    public function destroyProduct($id)
    {
        $result = (new ProductService())->destroyProduct($id);
        return redirect()->back();
    }

    public function categories()
    {
        $categories = Categories::paginate(10);
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        return view('seller.category', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $result = (new CategoryService())->storeCategory($request);
        return redirect()->back();
    }

    public function updateCategory(Request $request, $id)
    {
        $result = (new CategoryService())->updateCategory($request, $id);
        return redirect()->back();
    }

    public function destroyCategory($id)
    {
        $result = (new CategoryService())->destroyCategory($id);
        return redirect()->back();
    }

    public function locations()
    {
        $locations = Location::paginate(10);
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if (!$seller || !$seller->is_approved || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        return view('seller.locations', compact('locations'));
    }

    public function storeLocation(Request $request)
    {
        $result = (new LocationService())->storeLocation($request);
        return redirect()->back();
    }

    public function updateLocation(Request $request, $id)
    {
        $result = (new LocationService())->updateLocation($request, $id);
        return redirect()->back();
    }

    public function destroyLocation($id)
    {
        $result = (new LocationService())->destroyLocation($id);
        return redirect()->back();
    }

    public function cashier()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cashiers = Cashier::where('seller_id', $seller->id)->paginate(10);
        return view('seller.cashier', compact('cashiers'));
    }

    public function storeCashier(Request $request)
    {
        $result = (new CashierService())->storeCashier($request);
        return redirect()->back();
    }

    public function updateCashier(Request $request, $id)
    {
        $result = (new CashierService())->updateCashier($request, $id);
        return redirect()->back();
    }

    public function destroyCashier($id)
    {
        $result = (new CashierService())->destroyCashier($id);
        return redirect()->back();
    }

    public function rider()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $riders = Rider::where('seller_id', $seller->id)->paginate(10);
        return view('seller.rider', compact('riders'));
    }

    public function storeRider(Request $request)
    {
        $result = (new RiderService())->storeRider($request);
        return redirect()->back();
    }

    public function updateRider(Request $request, $id)
    {
        $result = (new RiderService())->updateRider($request, $id);
        return redirect()->back();
    }

    public function destroyRider($id)
    {
        $result = (new RiderService())->destroyRider($id);
        return redirect()->back();
    }

    public function trackingPending()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'pending');
        })
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();
        return view('seller.tracking.pending', compact('cartItems'));
    }

    public function trackingProcessed()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'processing');
        })
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();
        return view('seller.tracking.processed', compact('cartItems'));
    }

    public function trackingToReceive()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();

        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'receiving');
        })
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();
        return view('seller.tracking.receiving', compact('cartItems'));
    }

    public function trackingCancelled()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'cancelled');
        })
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();
        return view('seller.tracking.cancelled', compact('cartItems'));
    }

    public function trackingDelivered()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'delivered');
        })
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();
        return view('seller.tracking.delivered', compact('cartItems'));
    }

    public function orderHistory()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if (!$seller || !$seller->is_approved || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $orders = Orders::whereHas('orderItems', function ($query) use ($seller) {
            $query->whereHas('product', function ($subQuery) use ($seller) {
                $subQuery->where('seller_id', $seller->id);
            });
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Categories::all();
        $couriers = Courier::all();
        return view('seller.order-history', compact('orders', 'categories', 'couriers'));
    }

    public function exportInventory()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        $fileName = $seller->user->name . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new InventoryExport($seller->id), $filePath, 'public');

        $report = Reports::create([
            'seller_id' => $seller->id,
            'report_name' => 'Inventory Report',
            'report_type' => 'excel',
            'content' => $fileName,
        ]);

        return Excel::download(new InventoryExport($seller->id), $fileName);
    }

    public function exportSales()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        $fileName = $seller->user->name . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new SalesExport(), $filePath, 'public');

        $report = Reports::create([
            'seller_id' => $seller->id,
            'report_name' => 'Sales Report',
            'report_type' => 'excel',
            'content' => $fileName,
        ]);

        return Excel::download(new SalesExport(), $fileName);
    }

    public function reports()
    {
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if (!$seller || !$seller->is_approved || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $reports = Reports::where('seller_id', $seller->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('seller.reports', compact('reports'));
    }
}
