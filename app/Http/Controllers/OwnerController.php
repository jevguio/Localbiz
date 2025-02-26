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

    public function exportInventory()
    {
        $fileName = Auth::user()->name . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new AdminInventoryExport(), $filePath, 'public');

        $report = Reports::create([
            'seller_id' => Auth::user()->id,
            'report_name' => 'Inventory Report',
            'report_type' => 'excel',
            'content' => $fileName,
        ]);

        return Excel::download(new AdminInventoryExport(), $fileName);
    }

    public function exportProducts()
    {
        $fileName = Auth::user()->name . '_' . now()->format('YmdHis') . '.xlsx';
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

    public function exportSales()
    {
        $fileName = Auth::user()->name . '_' . now()->format('YmdHis') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new AdminOrdersExport(), $filePath, 'public');

        $report = Reports::create([
            'seller_id' => Auth::user()->id,
            'report_name' => 'Sales Report',
            'report_type' => 'excel',
            'content' => $fileName,
        ]);

        return Excel::download(new AdminOrdersExport(), $fileName);
    }

    public function reports()
    {
        $reports = Reports::paginate(10);
        return view('owner.reports', compact('reports'));
    }
}
