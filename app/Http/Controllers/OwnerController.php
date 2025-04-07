<?php

namespace App\Http\Controllers;

use App\Exports\AdminInventoryExport;
use App\Exports\AdminOrdersExport;
use App\Exports\AdminProductsExport;
use App\Exports\InventoryExport;
use App\Exports\ProductsExport;
use App\Exports\SalesExport;
use App\Models\Categories;
use App\Models\Courier;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Reports;
use App\Models\Seller;
use App\Models\User;
use App\Services\CourierService;
use App\Services\OwnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class OwnerController extends Controller
{
    public function account(Request $request)
    {
        if ($request->filter == 'all') {

            $users = User::paginate(10)
            ->appends(['filter' => $request->filter]);
            return view('owner.account', compact('users'));
        } else {

            $users = User::where('role', '=', $request->filter)->paginate(10)
            ->appends(['filter' => $request->filter]);
            return view('owner.account', compact('users'));
        }
    }

    public function storeAccount(Request $request)
    {
        $result = (new OwnerService())->storeAccount($request->all());
        return redirect()->back()->with('success', 'Account created successfully');
    }

    public function updateAccount(Request $request, $id)
    {
        $result = (new OwnerService())->updateAccount($id, $request);
        return redirect()->back()->with('success', 'Account updated successfully');
    }


    public function ToggleAccount(Request $request, $id)
    {
        $result = (new OwnerService())->ToggleAccount($id, $request);
        return redirect()->back()->with('success', 'Account updated successfully');
    }

    public function destroyAccount($id)
    {
        $result = (new OwnerService())->destroyAccount($id);
        return redirect()->back()->with('success', 'Account deleted successfully');
    }

    public function products()
    {
        $products = Products::where('is_active', '=', true)->paginate(10);
        $categories = Categories::all();
        $sellers = Seller::all();
        return view('owner.products', compact('products', 'categories', 'sellers'));
    }

    public function courier()
    {
        $couriers = Courier::paginate(10);
        return view('owner.courier', compact('couriers'));
    }

    public function storeCourier(Request $request)
    {
        $result = (new CourierService())->storeCourier($request->all());
        return redirect()->back()->with('success', 'Courier created successfully');
    }

    public function updateCourier(Request $request, $id)
    {
        $result = (new CourierService())->updateCourier($id, $request);
        return redirect()->back()->with('success', 'Courier updated successfully');
    }

    public function destroyCourier($id)
    {
        $result = (new CourierService())->destroyCourier($id);
        return redirect()->back()->with('success', 'Courier deleted successfully');
    }


    public function orders()
    {
        $orders = Orders::with(['user', 'orderItems.product', 'payments'])->latest()->paginate(10);

        $orderItems = OrderItems::all();
        $couriers = Courier::all();
        $payments = Payments::all();
        $products = Products::all();
        $categories = Categories::all();
        return view('owner.orders', compact('orders', 'orderItems', 'payments', 'products', 'categories', 'couriers'));
    }
    public function inventory(Request $request)
    {
        $sellers = Seller::all();
        $orders = null;
        $totalSales = 0;
        $products = null;

        if ($request->filter == 'all') {
            $sellerIds = $sellers->pluck('id'); // Get all seller IDs
        } else {
            $filteredSellers = Seller::where('user_id', $request->filter)->get();
            $sellerIds = $filteredSellers->pluck('id'); // Get filtered seller IDs
        }

        // Retrieve orders for the given seller IDs
        $orders = Orders::whereHas('orderItems.product', function ($query) use ($sellerIds) {
            $query->whereIn('seller_id', $sellerIds);
        })->paginate(10);

        // Sum total sales for given sellers
        $totalSales = Orders::whereHas('orderItems.product', function ($query) use ($sellerIds) {
            $query->whereIn('seller_id', $sellerIds);
        })->sum('total_amount');

        // Retrieve products for the given sellers
        $products = Products::whereIn('seller_id', $sellerIds)->where('is_active', '=', true)->paginate(10);

        // Retrieve categories and locations
        $categories = Categories::all();
        $locations = Location::all();

        return view('owner.inventory', compact('orders', 'products', 'totalSales', 'categories', 'locations', 'sellers'));
    }

    public function exportInventory(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // return Excel::download(new AdminInventoryExport(), $fileName);
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $filePath = 'reports/' . $fileName;

        $selectedSeller = User::where('role', 'seller')->where('id', $request->id)->get()->first();
        $items = Products::leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
            ->leftJoin('tbl_sellers', 'tbl_products.seller_id', '=', 'tbl_sellers.id')
            ->leftJoin('tbl_users', 'tbl_sellers.user_id', '=', 'tbl_users.id')
            ->selectRaw('
            tbl_products.id,
            tbl_products.name AS name, 
            tbl_products.seller_id AS seller_id,
            tbl_products.stock AS stock,
            tbl_products.description AS description,
            COALESCE(SUM(tbl_order_items.quantity), 0) AS sold,
            tbl_products.price,
            MAX(tbl_order_items.created_at) AS order_date,
            tbl_sellers.is_approved AS seller_approved,
            tbl_users.fname AS seller_fname,
            tbl_users.lname AS seller_lname,
            tbl_users.email AS seller_email,
            tbl_users.phone AS seller_phone
        ')->where('tbl_products.is_active', '=', true)


            ->where('tbl_sellers.user_id', $request->id) // Filtering by seller_id
            ->groupBy(
                'tbl_products.id',
                'tbl_products.name',
                'tbl_products.stock',
                'tbl_products.price',
                'tbl_sellers.is_approved',
                'tbl_users.fname',
                'tbl_users.lname',
                'tbl_users.email',
                'tbl_users.phone'
            )
            ->get();
        $is_view = false;
        // Generate PDF
        $pdf = Pdf::loadView('reports.inventory', compact('items', 'selectedSeller', 'is_view', 'startDate', 'endDate'))->setPaper('a4', 'portrait');

        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());

        // Save report to database
        $report = Reports::create([
            'report_name' => 'Inventory Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);

        // Return PDF for download
        return $pdf->download($fileName);
    }

    public function exportProducts()
    {
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new AdminProductsExport(), $filePath, 'public');

        $report = Reports::create([
            'report_name' => 'Products Report',
            'report_type' => 'excel',
            'content' => $fileName,
        ]);

        return Excel::download(new AdminProductsExport(), $fileName);
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
            ->leftJoin('tbl_sellers', 'tbl_products.seller_id', '=', 'tbl_sellers.id')
            ->leftJoin('tbl_users', 'tbl_sellers.user_id', '=', 'tbl_users.id')
            ->selectRaw('
            tbl_products.id,
            tbl_products.name AS name,
            tbl_products.seller_id AS seller_id,
            tbl_products.stock AS stock,
            tbl_products.description AS description,
            COALESCE(SUM(tbl_order_items.quantity), 0) AS sold,
            tbl_products.price,
            MAX(tbl_order_items.created_at) AS order_date,
            tbl_sellers.is_approved AS seller_approved,
            tbl_users.fname AS seller_fname,
            tbl_users.lname AS seller_lname,
            tbl_users.email AS seller_email,
            tbl_users.phone AS seller_phone
        ')
            ->where('tbl_sellers.user_id', $request->id) // Filtering by seller_id
            ->groupBy(
                'tbl_products.id',
                'tbl_products.name',
                'tbl_products.stock',
                'tbl_products.price',
                'tbl_sellers.is_approved',
                'tbl_users.fname',
                'tbl_users.lname',
                'tbl_users.email',
                'tbl_users.phone'
            )
            ->get();
        $is_view = false;
        // Generate PDF
        $pdf = Pdf::loadView('reports.sales', compact('items', 'selectedSeller', 'is_view', 'startDate', 'endDate'), [
            'encoding' => 'UTF-8'
        ])->setPaper('a4', 'portrait');

        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());

        // Save report to database
        $report = Reports::create([
            'report_name' => 'Sales Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);

        // Return PDF for download
        return $pdf->download($fileName);
    }
    public function daterangepicker()
    {

        return view('reports.daterangepicker');
    }
    public function reports(Request $request)
    {
        $reports = Reports::orderBy('created_at', 'desc')->paginate(10);

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $products = Products::paginate(10);
        $sellers = User::where('role', 'seller')->get();
        $selectedSeller = User::where('role', 'seller')->where('id', $request->id)->get()->first();
        $items = Products::leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
            ->leftJoin('tbl_sellers', 'tbl_products.seller_id', '=', 'tbl_sellers.id')
            ->leftJoin('tbl_users', 'tbl_sellers.user_id', '=', 'tbl_users.id')
            ->selectRaw('
            tbl_products.id,
            tbl_products.name AS name,
            tbl_products.seller_id AS seller_id,
            tbl_products.stock AS stock,
            tbl_products.description AS description,
            COALESCE(SUM(tbl_order_items.quantity), 0) AS sold,
            tbl_products.price,
            MAX(tbl_order_items.created_at) AS order_date,
            tbl_sellers.is_approved AS seller_approved,
            tbl_users.fname AS seller_fname,
            tbl_users.lname AS seller_lname,
            tbl_users.email AS seller_email,
            tbl_users.phone AS seller_phone
        ')
            ->where('tbl_sellers.user_id', $request->id) // Filtering by seller_id
            ->groupBy(
                'tbl_products.id',
                'tbl_products.name',
                'tbl_products.stock',
                'tbl_products.price',
                'tbl_sellers.is_approved',
                'tbl_users.fname',
                'tbl_users.lname',
                'tbl_users.email',
                'tbl_users.phone'
            )
            ->get();
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
        $is_view = true;
        return view('owner.reports', compact('reports', 'products', 'items', 'sellers', 'is_view', 'topSellers', 'startDate', 'endDate', 'selectedSeller'));
    }

    public function TopSeller()
    {
        $topSellers = User::where('role', 'seller')
            ->leftJoin('tbl_sellers', 'tbl_users.id', '=', 'tbl_sellers.user_id')
            ->leftJoin('tbl_products', 'tbl_sellers.id', '=', 'tbl_products.seller_id')
            ->leftJoin('tbl_order_items', 'tbl_products.id', '=', 'tbl_order_items.product_id')
            ->leftJoin('tbl_orders', 'tbl_order_items.order_id', '=', 'tbl_orders.id') // join for created_at
            ->selectRaw('
            tbl_users.id,
            tbl_users.fname,
            tbl_users.lname,
            COUNT(DISTINCT tbl_order_items.order_id) AS total_order,
            SUM(tbl_order_items.quantity) AS total_units_sold,
            DATE_FORMAT(tbl_orders.created_at, "%M %Y") as month,

            SUM(tbl_order_items.quantity * tbl_order_items.price) AS revenue,
            CASE 
                WHEN COUNT(DISTINCT tbl_order_items.order_id) > 0 
                THEN SUM(tbl_order_items.quantity * tbl_order_items.price) / COUNT(DISTINCT tbl_order_items.order_id) 
                ELSE 0 
            END AS avg_order_value
        ')
            ->whereNotNull('tbl_orders.created_at')
            ->groupBy('seller_id', 'month', 'fname', 'lname')
            ->orderBy('month', 'asc')
            ->orderBy('revenue', 'desc')
            ->limit(5) // Get top 10 sellers
            ->get();


        $chartData = [];
        foreach ($topSellers as $data) {
            $chartData[$data->month][$data->fname . "" . $data->lname] = $data->revenue;
        }
        $isViewBTN = true;
        return view('reports.top_seller', compact('topSellers', 'chartData', 'isViewBTN'));
    }
    public function exportTopSeller(Request $request)
    {
        $isViewBTN = false;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
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

        $chartData = [];
        foreach ($topSellers as $data) {
            $chartData[$data->month][$data->fname . "" . $data->lname] = $data->revenue;
        }
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $pdf = Pdf::loadView('reports.top_seller_component', compact('topSellers', 'chartData', 'startDate', 'endDate', 'isViewBTN'))->setPaper('a4', 'portrait');

        $filePath = 'reports/' . $fileName;
        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());

        // Save report to database
        $report = Reports::create([
            'report_name' => 'Top Seller Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);

        // Return PDF for download
        return $pdf->download($fileName);
    }

    //////////////////////////////////





    public function TopPurchase(Request $request)
    {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $topProducts = OrderItems::join('tbl_products', 'tbl_order_items.product_id', '=', 'tbl_products.id')
            ->select(
                'tbl_products.name',
                'tbl_order_items.product_id',
                DB::raw('DATE_FORMAT(tbl_order_items.created_at, "%Y-%m") as month'), // Get month format
                DB::raw('COUNT(DISTINCT tbl_order_items.order_id) as total_orders'),
                DB::raw('SUM(tbl_order_items.quantity) as total_sold'),
                DB::raw('SUM(tbl_order_items.price * tbl_order_items.quantity) as total_revenue'),
                DB::raw('MAX(tbl_order_items.price) as price'), // Use MAX() instead of just price
                DB::raw('AVG(tbl_order_items.price * tbl_order_items.quantity) as avg_order_value')
            )
            ->groupBy('month', 'tbl_order_items.product_id', 'tbl_products.name')
            ->orderBy('total_revenue', 'DESC')
            ->limit(10)
            ->get();
        $topProductsByMonth = DB::table('tbl_order_items')
            ->join('tbl_products', 'tbl_order_items.product_id', '=', 'tbl_products.id')
            ->select(
                'tbl_products.name as product_name',
                DB::raw('DATE_FORMAT(tbl_order_items.created_at, "%Y-%m") as month'),
                DB::raw('SUM(tbl_order_items.quantity) as total_sold')
            )
            ->groupBy('month', 'tbl_order_items.product_id', 'tbl_products.name')
            ->orderBy('total_sold', 'DESC')
            ->get();

        $chartData = [];
        foreach ($topProductsByMonth as $data) {
            $chartData[$data->month][$data->product_name] = $data->total_sold;
        }
        $products = Products::whereIn('id', $topProducts->pluck('product_id'))->where('is_active', '=', true)->get()->keyBy('id');

        // Merge product details into topProducts collection
        $topProducts->each(function ($item) use ($products) {
            $product = $products[$item->product_id] ?? null;
            if ($product) {
                $item->name = $product->name;
            }
        });
        $isViewBTN = true;
        return view('reports.top_purchase', compact('chartData', 'topProducts', 'startDate', 'endDate', 'isViewBTN'));
    }

    public function exportTopPurchase(Request $request)
    {
        $isViewBTN = false;

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $topProducts = OrderItems::join('tbl_products', 'tbl_order_items.product_id', '=', 'tbl_products.id')
            ->select(
                'tbl_products.name',
                'tbl_order_items.product_id',
                DB::raw('DATE_FORMAT(tbl_order_items.created_at, "%Y-%m") as month'), // Get month format
                DB::raw('COUNT(DISTINCT tbl_order_items.order_id) as total_orders'),
                DB::raw('SUM(tbl_order_items.quantity) as total_sold'),
                DB::raw('SUM(tbl_order_items.price * tbl_order_items.quantity) as total_revenue'),
                DB::raw('MAX(tbl_order_items.price) as price'), // Use MAX() instead of just price
                DB::raw('AVG(tbl_order_items.price * tbl_order_items.quantity) as avg_order_value')
            )
            ->groupBy('month', 'tbl_order_items.product_id', 'tbl_products.name')
            ->orderBy('month', 'ASC')
            ->limit(10)
            ->get();
        $topProductsByMonth = DB::table('tbl_order_items')
            ->join('tbl_products', 'tbl_order_items.product_id', '=', 'tbl_products.id')
            ->select(
                'tbl_products.name as product_name',
                DB::raw('DATE_FORMAT(tbl_order_items.created_at, "%Y-%m") as month'),
                DB::raw('SUM(tbl_order_items.quantity) as total_sold')
            )
            ->groupBy('month', 'tbl_order_items.product_id', 'tbl_products.name')
            ->orderBy('month', 'ASC')
            ->get();
        $chartData = [];
        foreach ($topProductsByMonth as $data) {
            $chartData[$data->month][$data->product_name] = $data->total_sold;
        }
        $products = Products::whereIn('id', $topProducts->pluck('product_id'))->get()->keyBy('id');

        // Merge product details into topProducts collection
        $topProducts->each(function ($item) use ($products) {
            $product = $products[$item->product_id] ?? null;
            if ($product) {
                $item->name = $product->name;
            }
        });
        // Generate PDF
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $pdf = Pdf::loadView('reports.top_purchase_component', compact('topProducts', 'isViewBTN', 'startDate', 'endDate', 'chartData'))
            ->setPaper('a4', 'landscape');

        $filePath = 'reports/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        Reports::create([
            'report_name' => 'Top Purchase Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);
        return $pdf->download($fileName);
    }



}
