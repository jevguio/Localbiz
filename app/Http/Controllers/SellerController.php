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
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Services\CategoryService;
use App\Services\LocationService;
use App\Services\ProductService;
use App\Constant\MyConstant;
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
        $lowStockProducts = Products::where('stock', '<=', 5)->get();

        return view('seller.dashboard', compact('seller','lowStockProducts'));
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
    public function updateOrder(Request $request)
    {
        
        \Log::error($request);
        try {
            $orderItem = Orders::findOrFail($request->id); 
            // $order = Orders::where('user_id', Auth::id())->where('status', 'pending')->firstOrFail();

             
            $orderItem->status = $request->status;
            $orderItem->save();

            // $order->total_amount = $order->orderItems->sum('price');
            // $order->save();

            session()->flash('success', 'Updated successfully.');
            
        return redirect()->back();
        } catch (\Exception $e) { 
            \Log::error($e);
            session()->flash('error', 'Failed to update product in cart.');
           
        return redirect()->back();
        }
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
        $categories = Categories::all();
        $couriers = Courier::all();
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        $orders = Orders::whereHas('orderItems', function ($query) use ($seller) {
            $query->whereHas('product', function ($subQuery) use ($seller) {
                $subQuery->where('seller_id', $seller->id);
            });
        })
        ->get();
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
        return view('seller.tracking.pending', compact('cartItems','categories','couriers','orders'));
    }

    public function trackingProcessed()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        $orders = Orders::whereHas('orderItems', function ($query) use ($seller) {
            $query->whereHas('product', function ($subQuery) use ($seller) {
                $subQuery->where('seller_id', $seller->id);
            });
        })
        ->get();
        if ($seller->is_approved == 0 || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->whereIn('status', ['processing', 'pending']);
        })
            ->whereHas('product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();
        return view('seller.tracking.processed', compact('cartItems','categories','couriers','orders'));
    }

    public function trackingToReceive()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $seller = Seller::where('user_id', Auth::user()->id)->first();

        $orders = Orders::whereHas('orderItems', function ($query) use ($seller) {
            $query->whereHas('product', function ($subQuery) use ($seller) {
                $subQuery->where('seller_id', $seller->id);
            });
        })
        ->get();
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
        return view('seller.tracking.receiving', compact('cartItems','categories','couriers','orders'));
    }

    public function trackingCancelled()
    {
        $categories = Categories::all();
        $couriers = Courier::all();
        $seller = Seller::where('user_id', Auth::user()->id)->first();

        $orders = Orders::whereHas('orderItems', function ($query) use ($seller) {
            $query->whereHas('product', function ($subQuery) use ($seller) {
                $subQuery->where('seller_id', $seller->id);
            });
        })
        ->get();
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
        return view('seller.tracking.cancelled', compact('cartItems','categories','couriers','orders'));
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


    public function exportInventory(Request $request)
    { 
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // return Excel::download(new AdminInventoryExport(), $fileName);
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $filePath = 'reports/' . $fileName;

        $selectedSeller = Auth::user();
        $items = Products::leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
        ->selectRaw('
            tbl_products.id,
            tbl_products.name AS name,
            tbl_products.seller_id AS seller_id,
            tbl_products.stock AS stock,
            COALESCE(SUM(tbl_order_items.quantity), 0) AS sold,
            tbl_products.price,
            MAX(tbl_order_items.created_at) AS order_date
        ') 
        ->groupBy('tbl_products.id', 'tbl_products.name', 'tbl_products.stock', 'tbl_products.price')
        ->get();
        // Generate PDF
        $pdf = Pdf::loadView('reports.inventory', compact('items','selectedSeller'))->setPaper('a4', 'portrait');
    
        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());
    
        // Save report to database
        $report = Reports::create([
            'seller_id' => Seller::where('user_id', $selectedSeller->id)->first()->id,
            'report_name' => 'Inventory Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);
        
    
        // Return PDF for download
        return $pdf->download($fileName);
    }

    public function exportSales(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // return Excel::download(new AdminInventoryExport(), $fileName);
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $filePath = 'reports/' . $fileName;

        $selectedSeller = User::where('role', 'seller')->where('id', $request->id)->get()->first();
        $items = Products::leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
        ->selectRaw('
            tbl_products.id,
            tbl_products.name AS name,
            tbl_products.seller_id AS seller_id,
            tbl_products.stock AS stock,
            COALESCE(SUM(tbl_order_items.quantity), 0) AS sold,
            tbl_products.price,
            MAX(tbl_order_items.created_at) AS order_date
        ') 
        ->groupBy('tbl_products.id', 'tbl_products.name', 'tbl_products.stock', 'tbl_products.price')
        ->get();
        // Generate PDF
        $pdf = Pdf::loadView('reports.sales', compact('items','selectedSeller'))->setPaper('a4', 'portrait');
    
        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());
    
        // Save report to database
        $report = Reports::create([
            'seller_id' => Auth::user()->id,
            'report_name' => 'Inventory Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);
    
        // Return PDF for download
        return $pdf->download($fileName);
    }
    public function exportTopSeller(){
        $isViewBTN=false;
        $topSellers = User::where('role', 'seller')
        ->leftJoin('tbl_sellers', 'tbl_users.id', '=', 'tbl_sellers.user_id')
        ->leftJoin('tbl_products', 'tbl_sellers.id', '=', 'tbl_products.seller_id')
        ->leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
        ->selectRaw('
            tbl_users.id,
            tbl_users.fname,
            tbl_users.lname,
            COUNT(DISTINCT tbl_order_items.order_id) AS total_order,
            SUM(tbl_order_items.quantity) AS total_units_sold,
            SUM(tbl_order_items.quantity * tbl_order_items.price) AS revenue,
            CASE 
                WHEN COUNT(DISTINCT tbl_order_items.order_id) > 0 
                THEN SUM(tbl_order_items.quantity * tbl_order_items.price) / COUNT(DISTINCT tbl_order_items.order_id) 
                ELSE 0 
            END AS avg_order_value
        ')
        ->groupBy('tbl_users.id', 'tbl_users.fname', 'tbl_users.lname')
        ->orderByDesc('revenue') // Sort by highest revenue
        ->limit(10) // Get top 10 sellers
        ->get();
        $is_view=false;
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $pdf = Pdf::loadView('reports.top_seller_component', compact('topSellers','is_view','isViewBTN'))->setPaper('a4', 'portrait');
    
        $filePath = 'reports/' . $fileName;
        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());
    
        // Save report to database
        $report = Reports::create([
            'seller_id' => Auth::user()->id,
            'report_name' => 'Inventory Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);
    
        // Return PDF for download
        return $pdf->download($fileName); 
    }
    public function reports()
    {
        $items = Products::leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
        ->selectRaw('
            tbl_products.id,
            tbl_products.name AS name,
            tbl_products.seller_id AS seller_id,
            tbl_products.stock AS stock,
            COALESCE(SUM(tbl_order_items.quantity), 0) AS sold,
            tbl_products.price,
            MAX(tbl_order_items.created_at) AS order_date
        ') 
        ->groupBy('tbl_products.id', 'tbl_products.name', 'tbl_products.stock', 'tbl_products.price')
        ->get();
        $selectedSeller = User::where('role', 'seller')->where('id', Auth::user()->id)->get()->first();
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        if (!$seller || !$seller->is_approved || $seller->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('seller.dashboard');
        }
        $isViewBTN=false;
        $is_view=false;
        $topSellers = User::where('role', 'seller')
        ->leftJoin('tbl_sellers', 'tbl_users.id', '=', 'tbl_sellers.user_id')
        ->leftJoin('tbl_products', 'tbl_sellers.id', '=', 'tbl_products.seller_id')
        ->leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
        ->selectRaw('
            tbl_users.id,
            tbl_users.fname,
            tbl_users.lname,
            COUNT(DISTINCT tbl_order_items.order_id) AS total_order,
            SUM(tbl_order_items.quantity) AS total_units_sold,
            SUM(tbl_order_items.quantity * tbl_order_items.price) AS revenue,
            CASE 
                WHEN COUNT(DISTINCT tbl_order_items.order_id) > 0 
                THEN SUM(tbl_order_items.quantity * tbl_order_items.price) / COUNT(DISTINCT tbl_order_items.order_id) 
                ELSE 0 
            END AS avg_order_value
        ')
        ->groupBy('tbl_users.id', 'tbl_users.fname', 'tbl_users.lname')
        ->orderByDesc('revenue') // Sort by highest revenue
        ->limit(10) // Get top 10 sellers
        ->get();
        $reports = Reports::where('seller_id', $seller->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('seller.reports', compact('reports','items','topSellers','is_view','isViewBTN','selectedSeller','seller'));
    }
}
