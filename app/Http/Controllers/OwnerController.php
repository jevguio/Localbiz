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

class OwnerController extends Controller
{
    public function account()
    {
        $users = User::paginate(10);
        return view('owner.account', compact('users'));
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
        $products = Products::where('seller_id', Auth::user()->id)->paginate(10);
        $categories = Categories::all();
        return view('owner.products', compact('products', 'categories'));
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
        $orders = Orders::with(['user', 'orderItems.product', 'payments'])->paginate(10);
        $orderItems = OrderItems::all();
        $payments = Payments::all();
        $products = Products::all();
        $categories = Categories::all();
        return view('owner.orders', compact('orders', 'orderItems', 'payments', 'products', 'categories'));
    }

    public function inventory()
    {
        $sellers = Seller::all();

        $orders = Orders::whereHas('orderItems.product', function ($query) use ($sellers) {
            $query->whereIn('seller_id', $sellers->pluck('id'));
        })->paginate(10);

        $totalSales = Orders::whereHas('orderItems.product', function ($query) use ($sellers) {
            $query->whereIn('seller_id', $sellers->pluck('id'));
        })->sum('total_amount');

        $products = Products::whereIn('seller_id', $sellers->pluck('id'))->get();

        return view('owner.inventory', compact('orders', 'products', 'totalSales'));
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
            'seller_id' => Auth::user()->id,
            'report_name' => 'Inventory Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);
    
        // Return PDF for download
        return $pdf->download($fileName);
    }

    public function exportProducts()
    {
        $fileName =  Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new AdminProductsExport(), $filePath, 'public');

        $report = Reports::create([
            'seller_id' => Auth::user()->id,
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
    public function daterangepicker(){
        
        return view('reports.daterangepicker');
    }
    public function reports(Request $request)
    { 
        $reports = Reports::paginate(10);
        $startDate= $request->startDate;
        $endDate= $request->endDate;
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
        return view('owner.reports', compact('reports','products','items','sellers','topSellers','startDate','endDate','selectedSeller'));
    }
    
    public function TopSeller(){
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
        $isViewBTN=true;
          return view('reports.top_seller', compact('topSellers','isViewBTN'));
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
        
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $pdf = Pdf::loadView('reports.top_seller_component', compact('topSellers','isViewBTN'))->setPaper('a4', 'portrait');
    
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
}
