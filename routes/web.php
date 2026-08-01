<?php

use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PriceRequestController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReportController;

Auth::routes();

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class , 'index'])->name('home');

// Management Routes (Admin & Owner)
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class , 'adminDashboard'])->name('admin.dashboard');
    Route::get('/owner/dashboard', [DashboardController::class , 'ownerDashboard'])->name('owner.dashboard');
    Route::get('/admin/orders', [OrderController::class , 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/create', [OrderController::class , 'create'])->name('admin.orders.create');
    Route::post('/admin/orders', [OrderController::class , 'store'])->name('admin.orders.store');
    Route::get('/admin/orders/{order}', [OrderController::class , 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [OrderController::class , 'updateStatus'])->name('admin.orders.updateStatus');
    Route::post('/admin/orders/{order}/confirm-payment', [OrderController::class , 'confirmPayment'])->name('admin.orders.confirmPayment');
    Route::get('/admin/orders/{order}/invoice', [InvoiceController::class , 'downloadPDF'])->name('admin.orders.invoice');
    Route::get('/admin/orders/export/report', [OrderController::class , 'exportPDF'])->name('admin.orders.export');
    Route::get('/admin/orders/export/excel', [OrderController::class , 'exportExcel'])->name('admin.orders.exportExcel');
    Route::get('/admin/reports/profit-loss', [ReportController::class , 'profitLossPDF'])->name('admin.reports.profitLoss');
    Route::get('/admin/reports/profit-loss/excel', [ReportController::class , 'profitLossExcel'])->name('admin.reports.profitLossExcel');

    // Kelola Treatment
    Route::resource('admin/treatments', TreatmentController::class)->names([
        'index' => 'admin.treatments.index',
        'create' => 'admin.treatments.create',
        'store' => 'admin.treatments.store',
        'edit' => 'admin.treatments.edit',
        'update' => 'admin.treatments.update',
        'destroy' => 'admin.treatments.destroy',
    ])->except(['show']);

    // Kelola Pengeluaran
    Route::resource('admin/expenses', ExpenseController::class)->names([
        'index' => 'admin.expenses.index',
        'create' => 'admin.expenses.create',
        'store' => 'admin.expenses.store',
        'edit' => 'admin.expenses.edit',
        'update' => 'admin.expenses.update',
        'destroy' => 'admin.expenses.destroy',
    ])->except(['show']);

    Route::resource('admin/price-requests', PriceRequestController::class)->names([
        'index' => 'admin.price-requests.index',
        'create' => 'admin.price-requests.create',
        'store' => 'admin.price-requests.store',
        'show' => 'admin.price-requests.show',
        'destroy' => 'admin.price-requests.destroy',
    ])->except(['edit', 'update']);

    Route::post('admin/price-requests/{priceRequest}/approve', [PriceRequestController::class, 'approve'])->name('admin.price-requests.approve');
    Route::post('admin/price-requests/{priceRequest}/reject', [PriceRequestController::class, 'reject'])->name('admin.price-requests.reject');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class , 'customerDashboard'])->name('customer.dashboard');
    Route::post('/customer/orders', [DashboardController::class , 'storeOrder'])->name('customer.orders.store');
    Route::post('/customer/orders/{order}/payment', [DashboardController::class , 'uploadPayment'])->name('customer.payment.upload');
    Route::post('/customer/payment/{order}/create', [\App\Http\Controllers\PaymentController::class, 'createInvoice'])->name('customer.payment.xendit');
});

// Public Invoice View
Route::get('/invoice/{order_code}', [InvoiceController::class , 'publicView'])->name('public.invoice');
