<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Exports\SalesExport;
use App\Models\Seller;
use App\Models\User;
use App\Models\WalkinOrders;
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
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    public function dashboard()
    {
        return view('cashier.dashboard');
    }

    public function index()
    {
        $user = Auth::user()->load('cashier');
        $seller_id = $user->cashier->seller_id ?? null; // Use null-safe operator in case it's missing
        $orders = WalkinOrders::where('seller_id', '=', $seller_id)->latest()->get();

        return view('cashier.orders.walkinorder', compact('orders'));
    }

    public function upload(Request $request)
    {
        $result = (new CashierService())->upload($request);
        return redirect()->back();
    }
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'delivery_method' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);
        $items = [];

        $subtotal = 0;
        foreach ($cart['product'] as $entry) {
            $product = $entry['product'];
            $price = (float) $entry['price'];
            $quantity = (int) $entry['quantity'];

            $items[] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $price,
                'quantity' => $quantity,
            ];

            $subtotal += $price * $quantity;
        }

        $deliveryFee = $data['delivery_method'] === 'delivery' ? 50 : 0;
        // $total = $subtotal + $deliveryFee;
        $user = Auth::user()->load('cashier');
        $seller_id = $user->cashier->seller_id ?? null; // Use null-safe operator in case it's missing
        $amount_paid = $data['amount_paid'];
        $payment_status = $amount_paid == $subtotal ? 'paid' : 'partial';
        $order = WalkinOrders::create([
            'customer_name' => $data['customer_name'] ?? null,
            'items' => $items,
            'seller_id' => $seller_id,
            'delivery_method' => $data['delivery_method'],
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $subtotal,
            'amount_paid' => $data['amount_paid'],
            'status' => $payment_status,
        ]);

        session()->forget('cart'); // optional: clear the cart

        session()->flash('success', 'Order Added');
        return redirect()->back();
    }

    public function walkin(Request $request)
    {

        $user = Auth::user()->load('cashier');
        $seller_id = $user->cashier->seller_id ?? null; // Use null-safe operator in case it's missing
        $couriers = Courier::all();

        if ($user->cashier->is_approved == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }


        $products = Products::with('orderItems')->get();

        $cart = session()->get('cart', []);
        $cart = array_merge([
            'id' => time(),
            'customer_name' => '',
            'product' => [],

            'subtotal' => 0,
            'delivery_fee' => 0,
            'total' => 0,
        ], $cart);
        return view('cashier.orders.walkin', compact('products', 'cart'));
    }
    public function showOrderPage()
    {
        $products = Products::all();
        $cart = session()->get('cart', []);

        return view('cashier.orders.walkin', compact('products', 'cart'));
    }


    public function updateCart(Request $request, $productId)
    {
        $product = Products::findOrFail($productId);

        $cart = session()->get('cart', []);
        $cart = array_merge([
            'id' => time(),
            'customer_name' => '',
            'product' => [],
            'subtotal' => 0,
            'delivery_fee' => 0,
            'total' => 0,
        ], $cart);

        // Initialize the product in cart if not yet set
        if (!isset($cart['product'][$productId])) {
            $cart['product'][$productId] = [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                ],
                'price' => $product->price,
                'quantity' => 0
            ];
        }

        // Update quantity
        if ($request->action === 'increase') {
            $cart['product'][$productId]['quantity'] += 1;
        } else {
            $cart['product'][$productId]['quantity'] = max(0, $cart['product'][$productId]['quantity'] - 1);
            // Optionally remove item if quantity = 0
            if ($cart['product'][$productId]['quantity'] === 0) {
                unset($cart['product'][$productId]);
            }
        }

        // Recalculate subtotal and total
        $subtotal = 0;
        foreach ($cart['product'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $cart['subtotal'] = $subtotal;
        $cart['delivery_fee'] = 0; // or set your logic for delivery fee
        $cart['total'] = $cart['subtotal'] + $cart['delivery_fee'];

        session()->put('cart', $cart);

        return redirect()->back();
    }

    public function orders()
    {
        $user = Auth::user()->load('cashier');
        $seller_id = $user->cashier->seller_id ?? null; // Use null-safe operator in case it's missing
        $couriers = Courier::all();

        if ($user->cashier->is_approved == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }

        $orders = Orders::with([
            'user',
            'orderItems.product', // ensures orderItems and nested product are loaded
            'payments'
        ])
            ->whereHas('orderItems.product', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })->where('status', '=', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orderItems = OrderItems::whereHas('product', function ($query) use ($seller_id) {
            $query->where('seller_id', $seller_id);
        })->paginate(10);
        $payments = Payments::all();
        $products = Products::all();
        $categories = Categories::all();

        return view('cashier.orders.orders', compact('orders', 'orderItems', 'payments', 'products', 'categories', 'couriers'));
    }

    public function ordersHistory()
    {

        $user = Auth::user()->load('cashier');
        $seller_id = $user->cashier->seller_id ?? null; // Use null-safe operator in case it's missing
        $couriers = Courier::all();

        if ($user->cashier->is_approved == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }

        $orders = Orders::with([
            'user',
            'orderItems.product', // ensures orderItems and nested product are loaded
            'payments'
        ])
            ->whereHas('orderItems.product', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })->where('status', '=', 'processing')
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        $payments = Payments::all();
        $products = Products::all();
        $categories = Categories::all();

        return view('cashier.orders', compact('orders', 'payments', 'products', 'categories', 'couriers'));
    }

    public function reports()
    {

        $user = Auth::user()->load('cashier');
        $seller_id = $user->cashier->seller_id ?? null; // Use null-safe operator in case it's missing
        $couriers = Courier::all();

        if ($user->cashier->is_approved == 0) {
            session()->flash('error', 'You are not approved to access this page.');
            return redirect()->route('cashier.dashboard');
        }

        $reports = Reports::where('user_id', $user->cashier->id)->latest()->paginate(10);
        return view('cashier.reports', compact('reports'));
    }
    public function exportSales(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        // return Excel::download(new AdminInventoryExport(), $fileName);
        $fileName = Auth::user()->fname . '_' . Auth::user()->lname . '_' . now()->format('YmdHis') . '.pdf';
        $filePath = 'reports/' . $fileName;

        $selectedSeller = User::with('cashier')->where('id', Auth::user()->id)->get()->first();
        $seller_id = $selectedSeller->cashier->seller_id;

        $payments = Payments::with(['customer', 'order'])->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->whereHas('order.orderItems.product', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })
            ->with(['order.user', 'order.orderItems.product'])
            ->get();
        $is_view = false;
        // Generate PDF
        $pdf = Pdf::loadView('reports.payments', compact('payments', 'selectedSeller', 'fromDate', 'toDate', 'is_view'))->setPaper('a4', 'portrait');

        // Store PDF in storage
        Storage::disk('public')->put($filePath, $pdf->output());

        $cashier = Auth::user()->cashier;
        // Save report to database
        $report = Reports::create([
            'user_id' => $cashier->id,
            'report_name' => 'Payment Report',
            'report_type' => 'pdf',
            'content' => $fileName,
        ]);

        // Return PDF for download
        return $pdf->download($fileName);
    }
    // public function exportSales(Request $request)
    // {
    //     $request->validate([
    //         'from_date' => 'required|date',
    //         'to_date' => 'required|date|after_or_equal:from_date'
    //     ]);

    //     $cashier = Auth::user()->cashier;
    //     $seller_id = $cashier->seller_id;

    //     $fromDate = $request->from_date;
    //     $toDate = $request->to_date;

    //     $payments = Payments::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
    //         ->whereHas('order.orderItems.product', function ($query) use ($seller_id) {
    //             $query->where('seller_id', $seller_id);
    //         })
    //         ->with(['order.user', 'order.orderItems.product'])
    //         ->get();

    //     // Generate Excel file and save it
    //     $filename = 'payment_transactions_' . date('Y-m-d_His') . '.xlsx';
    //     Excel::store(new PaymentTransactionsExport($payments), 'reports/' . $filename, 'public');

    //     // Create report record
    //     $report = new Reports();
    //     $report->report_name = 'Payment Transactions ' ;
    //     $report->report_type = 'pdf';
    //     $report->user_id = $cashier->id;
    //     $report->content = $filename;
    //     $report->save();

    //     return Excel::download(new PaymentTransactionsExport($payments), $filename);
    // }

    public function updateOrder(Request $request, $id)
    {
        $result = (new OrderService())->updateOrder($request, $id);

        session()->flash('success', 'Payment Approved');
        return redirect()->back();
    }

    public function createOrder(Request $request, $id)
    {
        $result = (new OrderService())->createOrder($request, $id);

        session()->flash('success', 'Payment Approved');
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
