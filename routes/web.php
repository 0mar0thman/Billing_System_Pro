<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceSittingController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceAchiveController;
use App\Http\Controllers\InvoiceReportController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Validation\Rules\Can;

// الصفحة الرئيسية وتوجيهات المصادقة
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('auth.login');
});

Auth::routes();
Auth::routes(['register' => false]); // يمكن تفعيلها لتعطيل التسجيل

// المسارات العامة بعد المصادقة
Route::middleware(['auth', 'user.active'])->group(function () {
    // الصفحة الرئيسية
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // مسارات الفواتير
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');

    Route::put('/invoices/update/{id}', [InvoiceController::class, 'update'])->name('invoices.update');

    Route::get('/section/{id}/products', [InvoiceController::class, 'getproducts'])->name('sections.products');
    Route::get('get-products/{id}', [InvoiceController::class, 'getProducts'])->name('get-products');
    Route::get('getProductDetails/{id}', [InvoiceController::class, 'getProductDetails'])->name('getProductDetails');

    Route::resource('invoices', InvoiceController::class);

    Route::get('/edit_invoice/{id}', [InvoiceController::class, 'edit']);
    Route::get('/Status_show/{id}', [InvoiceController::class, 'show'])->name('Status_show');
    Route::post('/Status_Update/{id}', [InvoiceController::class, 'Status_Update'])->name('Status_Update');
    Route::get('/invoices/print/{id}', [InvoiceController::class, 'print'])->name('invoices.print');

    // أنواع الفواتير
    Route::get('Invoice_Paid', [InvoiceController::class, 'Invoice_Paid'])->name('Invoice_Paid');
    Route::get('Invoice_Partial', [InvoiceController::class, 'Invoice_Partial'])->name('Invoice_Partial');
    Route::get('Invoice_UnPaid', [InvoiceController::class, 'Invoice_UnPaid'])->name('Invoice_UnPaid');


    // مرفقات الفواتير
    Route::resource('InvoiceAttachments', InvoiceAttachmentController::class);
    Route::post('/InvoiceAttachments', [InvoiceAttachmentController::class, 'store'])->name('attachments.store');

    // تفاصيل الفواتير
    Route::get('/InvoicesDetails/{id}', [InvoiceDetailController::class, 'edit'])->name('invoices.details');
    Route::delete('attachments/{id}', [InvoiceDetailController::class, 'destroy'])->name('attachments.destroy');

    // إعدادات الفواتير
    Route::prefix('invoices-sitting')->group(function () {
        Route::get('/', [InvoiceSittingController::class, 'index'])->name('invoices.sittings');
        Route::post('/store', [InvoiceSittingController::class, 'store'])->name('invoices_sittings_store');
    });

    // الأرشيف
    Route::resource('Archive', InvoiceAchiveController::class);

    // الأقسام
    Route::resource('sections', SectionController::class);

    // المنتجات
    Route::resource('products', ProductController::class);
    Route::get('export_invoices', [InvoiceController::class, 'export']);
    Route::get('export_products', [ProductController::class, 'export']);

    // إدارة المستخدمين والأدوار
    Route::group(['middleware' => ['role:owner']], function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', AdminController::class);
    });

    Route::get('invoices_report', [InvoiceReportController::class, 'index'])->name('invoices.report');
    Route::post('invoices_report/search', [InvoiceReportController::class, 'search'])->name('invoices.report.search');


    Route::get('customers_report', [CustomerReportController::class, 'index'])->name("customers_report");
    Route::post('Search_customers', [CustomerReportController::class, 'Search_customers'])->name('search_customers');
    Route::get('/section/{id}', [CustomerReportController::class, 'getProducts'])->name('get_products');

    Route::get('/invoice_notification', function () {
        return view('emails.invoice_notification');
    });

    Route::post('/notifications/mark-all-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllAsRead');

    // web.php
    Route::get('/read-notification/{id}', [NotificationController::class, 'read'])->name('read_notification');
    Route::get('/mark-all-as-read', [NotificationController::class, 'markAll'])->name('mark_all_notifications');

    
});

Route::get('/{page}', [AdminController::class, 'index'])->where('page', '.*');
