<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/home', function () {
            if (Gate::allows('access-dashboard', auth()->user())) {
                return redirect()->route('dashboard');
            } elseif (Gate::allows('access-forum', auth()->user())) {
                return redirect()->route('forum.index');
            }
        })->name('home');
    });


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/counts', [DashboardController::class, 'getCounts']);
    Route::get('/api/dashboard/quotations-by-month', [DashboardController::class, 'getQuotationsByMonth']);

    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('/users/{user}', [\App\Http\Controllers\UserController::class, 'enable_disable'])->name('users.enable_disable');

    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');

    Route::get('quotations/getPDF/{idQuotation}',[\App\Http\Controllers\QuotationController::class, 'getPDF'])->name('quotations.getPDF');
    Route::get('quotations/getBL/{idQuotation}',[\App\Http\Controllers\QuotationController::class, 'getBL'])->name('quotations.getBL');
    Route::post('/quotations/deleteLine', [\App\Http\Controllers\QuotationController::class, 'deleteLine'])->name('quotations.delete_line');
    Route::resource('quotations', \App\Http\Controllers\QuotationController::class);
    Route::post('/quotations/{quotation}', [\App\Http\Controllers\QuotationController::class, 'activate'])->name('quotations.activate');

    Route::resource('/credits', \App\Http\Controllers\CreditController::class);
    Route::get('/credits/{credit}/payments', [\App\Http\Controllers\CreditController::class, 'payments'])->name('credits.payments');
    Route::get('/credits/clients/nonPaid', [\App\Http\Controllers\CreditController::class, 'nonPaid'])->name('credits.nonPaid');

    Route::get('/invoices/create/{quotation}', [\App\Http\Controllers\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
    Route::put('/invoices', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}/edit', [\App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::get('/invoices/getPDF/{invoiceID}',[\App\Http\Controllers\InvoiceController::class, 'getPDF'])->name('invoices.getPDF');
    Route::post('/invoices/deleteLine', [\App\Http\Controllers\InvoiceController::class, 'deleteLine'])->name('invoices.delete_line');

    Route::resource('/orders', \App\Http\Controllers\OrderController::class);
    Route::post('/orders/deleteLine', [\App\Http\Controllers\OrderController::class, 'deleteLine'])->name('orders.delete_line');
    Route::post('/orders/changeStatus', [\App\Http\Controllers\OrderController::class, 'changeStatus'])->name('orders.change_status');

    Route::get('/deliveryNotes/create/{order}', [\App\Http\Controllers\DeliveryNoteController::class, 'create'])->name('deliveryNotes.create');
    Route::get('/deliveryNotes/{deliveryNote}', [\App\Http\Controllers\DeliveryNoteController::class, 'show'])->name('deliveryNotes.show');
    Route::get('/deliveryNotes', [\App\Http\Controllers\DeliveryNoteController::class, 'index'])->name('deliveryNotes.index');
    Route::post('/deliveryNotes', [\App\Http\Controllers\DeliveryNoteController::class, 'store'])->name('deliveryNotes.store');
    Route::delete('/deliveryNotes', [\App\Http\Controllers\DeliveryNoteController::class, 'destroy'])->name('deliveryNotes.destroy');

    Route::resource('/supplierCredits', \App\Http\Controllers\SupplierCreditController::class);
    Route::get('/supplierCredits/{supplierCredit}/payments', [\App\Http\Controllers\SupplierCreditController::class, 'payments'])->name('supplierCredits.payments');
    Route::get('/credits/suppliers/nonPaid', [\App\Http\Controllers\SupplierCreditController::class, 'nonPaid'])->name('supplierCredits.nonPaid');


    Route::resource('/products', \App\Http\Controllers\ProductController::class);
    Route::resource('/providers', \App\Http\Controllers\ProviderController::class);
    Route::resource('/clients', \App\Http\Controllers\ClientController::class);

});

