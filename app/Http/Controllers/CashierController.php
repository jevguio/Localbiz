<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Courier;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Reports;
use App\Services\CashierService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use App\Exports\PaymentTransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    public function dashboard()
    {
        return view('cashier.dashboard');
    }

    public function upload(Request $request)
    {
        $result = (new CashierService())->upload($request);
        return redirect()->back();
    }

    public function orders()
    {
        $cashier = Auth::user()->cashier;
        $seller_id = $cashier->seller_id;
        $couriers = Courier::all();

        if ($cashier->is_approved == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }

        $orders = Orders::with(['orderItems','payments'])->whereHas('orderItems', function ($query) use ($seller_id) {
            $query->whereHas('product', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            });
        })->orderBy('created_at', 'desc')
            ->with(['user', 'orderItems.product', 'payments'])
            ->paginate(10);

        $orderItems = OrderItems::whereHas('product', function ($query) use ($seller_id) {
            $query->where('seller_id', $seller_id);
        })->paginate(10);
        $payments = Payments::all();
        $products = Products::all();
        $categories = Categories::all();

        return view('cashier.orders', compact('orders', 'orderItems', 'payments', 'products', 'categories', 'couriers'));
    }

    public function reports()
    {
        $cashier = Auth::user()->cashier;

        if ($cashier->is_approved == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }

        $reports = Reports::where('user_id', $cashier->id)->latest()->paginate(10);
        return view('cashier.reports', compact('reports'));
    } 
    public function exportSales(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ]);

        $cashier = Auth::user()->cashier;
        $seller_id = $cashier->seller_id;

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $payments = Payments::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->whereHas('order.orderItems.product', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })
            ->with(['order.user', 'order.orderItems.product'])
            ->get();

        $report = new Reports();  // Changed from Report to Reports
        $report->report_name = 'Payment Transactions ' . date('Y-m-d H:i:s');
        $report->report_type = 'Payment Transactions';
        $report->user_id = $cashier->id;  // Added user_id
        $report->save();

        return Excel::download(new PaymentTransactionsExport($payments), 'payment_transactions_' . date('Y-m-d') . '.xlsx');
    }

    public function updateOrder(Request $request, $id)
    {
        $result = (new OrderService())->updateOrder($request, $id);
        return redirect()->back();
    }

    public function trackingPending()
    {
        $cashier = Auth::user()->cashier;
        if ($cashier->is_approved == 0 || $cashier->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'pending');
        })
            ->whereHas('product', function ($query) use ($cashier) {
                $query->where('seller_id', $cashier->seller_id);
            })
            ->get();
        return view('cashier.tracking.pending', compact('cartItems'));
    }

    public function trackingProcessed()
    {
        $cashier = Auth::user()->cashier;
        if ($cashier->is_approved == 0 || $cashier->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'processed');
        })
            ->whereHas('product', function ($query) use ($cashier) {
                $query->where('seller_id', $cashier->seller_id);
            })
            ->get();
        return view('cashier.tracking.processed', compact('cartItems'));
    }

    public function trackingToReceive()
    {
        $cashier = Auth::user()->cashier;
        if ($cashier->is_approved == 0 || $cashier->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'to_receive');
        })
            ->whereHas('product', function ($query) use ($cashier) {
                $query->where('seller_id', $cashier->seller_id);
            })
            ->get();
        return view('cashier.tracking.receiving', compact('cartItems'));
    }

    public function trackingCancelled()
    {
        $cashier = Auth::user()->cashier;
        if ($cashier->is_approved == 0 || $cashier->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'cancelled');
        })
            ->whereHas('product', function ($query) use ($cashier) {
                $query->where('seller_id', $cashier->seller_id);
            })
            ->get();
        return view('cashier.tracking.cancelled', compact('cartItems'));
    }

    public function trackingDelivered()
    {
        $cashier = Auth::user()->cashier;
        if ($cashier->is_approved == 0 || $cashier->user->is_active == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }
        $cartItems = OrderItems::whereHas('order', function ($query) {
            $query->where('status', 'delivered');
        })
            ->whereHas('product', function ($query) use ($cashier) {
                $query->where('seller_id', $cashier->seller_id);
            })
            ->get();
        return view('cashier.tracking.delivered', compact('cartItems'));
    }
}
