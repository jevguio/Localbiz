<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'Owner'])->group(function () {
    Route::get('/owner/account', [OwnerController::class, 'account'])->name('owner.account');
    Route::get('/owner/products', [OwnerController::class, 'products'])->name('owner.products');
    Route::get('/owner/courier', [OwnerController::class, 'courier'])->name('owner.courier');
    Route::get('/owner/orders', [OwnerController::class, 'orders'])->name('owner.orders');
    Route::get('/owner/inventory', [OwnerController::class, 'inventory'])->name('owner.inventory');
    Route::get('/owner/reports', [OwnerController::class, 'reports'])->name('owner.reports');
    Route::get('/owner/reports/daterangepicker', [OwnerController::class, 'daterangepicker'])->name('owner.daterangepicker');

    Route::get('/owner/inventory/export', [OwnerController::class, 'exportInventory'])->name('owner.inventory.export');
    Route::get('/owner/sales/export', [OwnerController::class, 'exportSales'])->name('owner.sales.export');
    Route::get('/owner/products/export', [OwnerController::class, 'exportProducts'])->name('owner.products.export');
    
    Route::get('/owner/toppurchase', [OwnerController::class, 'TopPurchase'])->name('owner.toppurchase');
    Route::get('/owner/toppurchase/export', [OwnerController::class, 'exportTopPurchase'])->name('owner.toppurchase.export');

    Route::get('/owner/topseller', [OwnerController::class, 'TopSeller'])->name('owner.topseller');
    Route::get('/owner/topseller/export', [OwnerController::class, 'exportTopSeller'])->name('owner.topseller.export');

    Route::post('/owner/courier/store', [OwnerController::class, 'storeCourier'])->name('owner.courier.store');
    Route::post('/owner/account/store', [OwnerController::class, 'storeAccount'])->name('owner.account.store');

    Route::put('/owner/courier/update/{id}', [OwnerController::class, 'updateCourier'])->name('owner.courier.update');
    Route::put('/owner/account/update/{id}', [OwnerController::class, 'updateAccount'])->name('owner.account.update');
    Route::put('/owner/account/toggle/{id}', [OwnerController::class, 'ToggleAccount'])->name('owner.account.toggle');

    Route::delete('/owner/courier/destroy/{id}', [OwnerController::class, 'destroyCourier'])->name('owner.courier.destroy');
    Route::delete('/owner/account/destroy/{id}', [OwnerController::class, 'destroyAccount'])->name('owner.account.destroy');
});

Route::middleware(['auth', 'Customer'])->group(function () {
    Route::get('/customer/products', [CustomerController::class, 'products'])->name('customer.products');
    Route::get('/customer/cart', [CustomerController::class, 'cart'])->name('customer.cart');
    Route::get('/customer/order-history', [CustomerController::class, 'orderHistory'])->name('customer.order-history');
    Route::get('/customer/feedback', [CustomerController::class, 'feedback'])->name('customer.feedback');

    Route::post('/customer/addToCart', [CustomerController::class, 'addToCart'])->name('customer.addToCart');
    Route::put('/customer/updateCart', [CustomerController::class, 'updateCart'])->name('customer.updateCart');
    Route::put('/customer/updateSelectionCart', [CustomerController::class, 'updateSelectionCart'])->name('customer.updateSelectionCart');
    Route::post('/customer/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
    Route::delete('/customer/removeCart', [CustomerController::class, 'removeCart'])->name('customer.removeCart');
    Route::put('/customer/cancelled', [CustomerController::class, 'cancelOrder'])->name('customer.cancelled');

    Route::get('/customer/tracking/pending', [CustomerController::class, 'trackingPending'])->name('customer.tracking.pending');
    Route::get('/customer/tracking/processed', [CustomerController::class, 'trackingProcessed'])->name('customer.tracking.processed');
    Route::get('/customer/tracking/receiving', [CustomerController::class, 'trackingToReceive'])->name('customer.tracking.receiving');
    Route::get('/customer/tracking/cancelled', [CustomerController::class, 'trackingCancelled'])->name('customer.tracking.cancelled');
    Route::get('/customer/tracking/delivered', [CustomerController::class, 'trackingDelivered'])->name('customer.tracking.delivered');

    Route::post('/customer/tracking/processed/upload', [CustomerController::class, 'uploadReceipt'])->name('customer.tracking.processed.upload');
    Route::post('/customer/tracking/delivered/upload', [CustomerController::class, 'uploadFeedback'])->name('customer.tracking.delivered.upload');
});

Route::middleware(['auth', 'Seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::get('/seller/products', [SellerController::class, 'products'])->name('seller.products');
    Route::get('/seller/categories', [SellerController::class, 'categories'])->name('seller.categories');
    Route::get('/seller/locations', [SellerController::class, 'locations'])->name('seller.locations');
    Route::get('/seller/cashier', [SellerController::class, 'cashier'])->name('seller.cashier');
    Route::get('/seller/rider', [SellerController::class, 'rider'])->name('seller.rider');
    Route::get('/seller/reports', [SellerController::class, 'reports'])->name('seller.reports');
    Route::get('/seller/order-history', [SellerController::class, 'orderHistory'])->name('seller.order-history');

    Route::get('/seller/inventory/export', [SellerController::class, 'exportInventory'])->name('seller.inventory.export');
    Route::get('/seller/sales/export', [SellerController::class, 'exportSales'])->name('seller.sales.export');
    Route::get('/seller/products/export', [SellerController::class, 'exportProducts'])->name('seller.products.export');

    Route::get('/seller/tracking/pending', [SellerController::class, 'trackingPending'])->name('seller.tracking.pending');
    Route::get('/seller/tracking/processed', [SellerController::class, 'trackingProcessed'])->name('seller.tracking.processed');
    Route::get('/seller/tracking/receiving', [SellerController::class, 'trackingToReceive'])->name('seller.tracking.receiving');
    Route::get('/seller/tracking/cancelled', [SellerController::class, 'trackingCancelled'])->name('seller.tracking.cancelled');
    Route::get('/seller/tracking/delivered', [SellerController::class, 'trackingDelivered'])->name('seller.tracking.delivered');

    Route::post('/seller/dashboard/upload', [SellerController::class, 'upload'])->name('seller.dashboard.upload');
    Route::post('/seller/products/store', [SellerController::class, 'storeProduct'])->name('seller.products.store');
    Route::post('/seller/categories/store', [SellerController::class, 'storeCategory'])->name('seller.categories.store');
    Route::post('/seller/locations/store', [SellerController::class, 'storeLocation'])->name('seller.locations.store');
    Route::post('/seller/cashier/store', [SellerController::class, 'storeCashier'])->name('seller.cashier.store');
    Route::post('/seller/rider/store', [SellerController::class, 'storeRider'])->name('seller.rider.store');

    Route::put('/seller/order/update', [SellerController::class, 'updateOrder'])->name('seller.order.update');
    Route::put('/seller/categories/update/{id}', [SellerController::class, 'updateCategory'])->name('seller.categories.update');
    Route::put('/seller/products/update/{id}', [SellerController::class, 'updateProduct'])->name('seller.products.update');
    Route::put('/seller/locations/update/{id}', [SellerController::class, 'updateLocation'])->name('seller.locations.update');
    Route::put('/seller/cashier/update/{id}', [SellerController::class, 'updateCashier'])->name('seller.cashier.update');
    Route::put('/seller/rider/update/{id}', [SellerController::class, 'updateRider'])->name('seller.rider.update');
   
    Route::delete('/seller/cashier/destroy/{id}', [SellerController::class, 'destroyCashier'])->name('seller.cashier.destroy');
    Route::delete('/seller/rider/destroy/{id}', [SellerController::class, 'destroyRider'])->name('seller.rider.destroy');
    Route::delete('/seller/categories/destroy/{id}', [SellerController::class, 'destroyCategory'])->name('seller.categories.destroy');
    Route::delete('/seller/products/destroy/{id}', [SellerController::class, 'destroyProduct'])->name('seller.products.destroy');
    Route::delete('/seller/locations/destroy/{id}', [SellerController::class, 'destroyLocation'])->name('seller.locations.destroy');
});

Route::middleware(['auth', 'GovernmentAgency'])->group(function () {
    Route::get('/government/approval', [GovernmentController::class, 'index'])->name('government.approval');

    Route::put('/government/approval/update/{id}', [GovernmentController::class, 'updateApproval'])->name('government.approval.update');
});

Route::middleware(['auth', 'Cashier'])->group(function () {
    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');
    Route::get('/cashier/orders', [CashierController::class, 'orders'])->name('cashier.orders');
    Route::get('/cashier/reports', [CashierController::class, 'reports'])->name('cashier.reports');

    Route::get('/cashier/sales/export', [CashierController::class, 'exportSales'])->name('cashier.sales.export');

    Route::get('/cashier/tracking/pending', [CashierController::class, 'trackingPending'])->name('cashier.tracking.pending');
    Route::get('/cashier/tracking/processed', [CashierController::class, 'trackingProcessed'])->name('cashier.tracking.processed');
    Route::get('/cashier/tracking/receiving', [CashierController::class, 'trackingToReceive'])->name('cashier.tracking.receiving');
    Route::get('/cashier/tracking/cancelled', [CashierController::class, 'trackingCancelled'])->name('cashier.tracking.cancelled');
    Route::get('/cashier/tracking/delivered', [CashierController::class, 'trackingDelivered'])->name('cashier.tracking.delivered');

    Route::post('/cashier/dashboard/upload', [CashierController::class, 'upload'])->name('cashier.dashboard.upload');

    Route::put('/cashier/orders/update/{id}', [CashierController::class, 'updateOrder'])->name('cashier.orders.update');
});

Route::middleware(['auth', 'DeliveryRider'])->group(function () {
    Route::get('/rider/dashboard', [RiderController::class, 'dashboard'])->name('rider.dashboard');
    Route::get('/rider/orders', [RiderController::class, 'orders'])->name('rider.orders');

    Route::get('/rider/tracking/pending', [RiderController::class, 'trackingPending'])->name('rider.tracking.pending');
    Route::get('/rider/tracking/processed', [RiderController::class, 'trackingProcessed'])->name('rider.tracking.processed');
    Route::get('/rider/tracking/receiving', [RiderController::class, 'trackingToReceive'])->name('rider.tracking.receiving');
    Route::get('/rider/tracking/cancelled', [RiderController::class, 'trackingCancelled'])->name('rider.tracking.cancelled');
    Route::get('/rider/tracking/delivered', [RiderController::class, 'trackingDelivered'])->name('rider.tracking.delivered');

    Route::post('/rider/dashboard/upload', [RiderController::class, 'upload'])->name('rider.dashboard.upload');
    
    
    Route::put('/rider/orders/update/{id}', [RiderController::class, 'updateOrder'])->name('rider.orders.update');
});

require __DIR__ . '/auth.php';
