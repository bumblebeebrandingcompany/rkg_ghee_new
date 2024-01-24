
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('home');


//TODO: middleware check for admin
//Admin routes
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');
    
    Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products');
    Route::get('/state-by-point/{id}', [App\Http\Controllers\Admin\ProductController::class, 'state_by_point'])->name('state_by_point');
    Route::post('/point_store', [App\Http\Controllers\Admin\ProductController::class, 'point_store'])->name('point_store');
    // product report route
    Route::get('/product/report', [App\Http\Controllers\Admin\ProductController::class, 'product_report'])->name('product_report');

    // edit price of product
    Route::get('/product/price-edit', [App\Http\Controllers\Admin\ProductController::class, 'price_edit'])->name('price_edit');

    Route::post('/product/price-update', [App\Http\Controllers\Admin\ProductController::class, 'price_update'])->name('price_update');


    Route::get('/product/add-price', [App\Http\Controllers\Admin\ProductController::class, 'add_price'])->name('add_price');

    Route::post('/product/add-price', [App\Http\Controllers\Admin\ProductController::class, 'store_price'])->name('store_price');

    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('target', App\Http\Controllers\DistributorTargetController::class);
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::get('/orders/edit-status/{id}', [App\Http\Controllers\OrderController::class, 'edit_status'])->name('orders.edit_status');
    Route::post('/orders/update-status/{id}', [App\Http\Controllers\OrderController::class, 'update_status'])->name('orders.update_status');
    Route::get('/orders/edit-date/{id}', [App\Http\Controllers\OrderController::class, 'edit_date'])->name('orders.edit_date');
    Route::post('/orders/update-date/{id}', [App\Http\Controllers\OrderController::class, 'update_date'])->name('orders.update_date');
    Route::get('dist_sales_under_am', [App\Http\Controllers\Admin\UserController::class, 'dist_sales_under_am'])->name('dist_sales_under_am');

    Route::post('/orders/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancelOrder'])->name('orders.cancel');

    Route::get('/orders/{id}/verify', [App\Http\Controllers\OrderController::class, 'verify'])
        ->name('order.verify');

    Route::post('/orders/{id}/verify-send', [App\Http\Controllers\OrderController::class, 'verifySend'])
        ->name('order.verifysend');

    Route::get('/shops', [App\Http\Controllers\SalesRep\ShopController::class, 'index'])
        ->name('shops.index');
    Route::get('/shops/{id}/approve-sales', [App\Http\Controllers\SalesRep\ShopController::class, 'approve_sales'])->name('approve_sales');
    Route::post('/shops/{id}/approve-sales', [App\Http\Controllers\SalesRep\ShopController::class, 'storeApproveSales'])->name('store_approve_sales');
    
    // report route
    Route::get('user/reports', [App\Http\Controllers\Admin\UserController::class, 'report'])->name('report');

    Route::resource('states', App\Http\Controllers\StateController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/download-order/{id}/invoive', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('download.invoice');
    Route::get('edit-profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit.profile');
    Route::put('update-profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('update.profile');
    Route::post('check-email-exist', [App\Http\Controllers\HomeController::class, 'checkIfEmailExist'])->name('check.email.exist');

    Route::post('check-reference_id-exist', [App\Http\Controllers\HomeController::class, 'checkIfreference_idExist'])->name('check.reference_id.exist');

    Route::get('/download/{id}/media', [App\Http\Controllers\MediaController::class, 'downloadMedia'])
        ->name('download.media');

    Route::get('/order/{id}/view-invoice', [App\Http\Controllers\OrderController::class, 'viewInvoice'])
        ->name('view_invoice');
        
    Route::resource('medias', App\Http\Controllers\MediaController::class)
        ->except(['show'])
        ->names([
            'index' => 'medias.index',
            'create' => 'medias.create',
            'store' => 'medias.save',
            'edit' => 'medias.edit',
            'update' => 'medias.update',
            'destroy' => 'medias.destroy'
        ]);

    Route::post('check-shop-exist', [App\Http\Controllers\SalesRep\ShopController::class, 'checkIfShopExist'])->name('check.shop.exist');
});



//distributor routes
Route::prefix('dist')->middleware(['auth'])->name('dist.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Dist\HomeController::class, 'index'])->name('home');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::get('/shops/{id}/approve-sales', [App\Http\Controllers\Dist\HomeController::class, 'approve_sales'])->name('approve_sales');    
    Route::post('/reason', [App\Http\Controllers\Dist\HomeController::class, 'decline_reason'])->name('reason');  
    Route::get('/orders/{id}/verify', [App\Http\Controllers\OrderController::class, 'verify'])
        ->name('order.verify');
    Route::post('/orders/{id}/verify-send', [App\Http\Controllers\OrderController::class, 'verifySend'])
        ->name('order.verifysend');
    Route::get('/shops', [App\Http\Controllers\SalesRep\ShopController::class, 'index'])->name('shop');
    Route::post('/forward_to_rkg/{id}', [App\Http\Controllers\OrderController::class, 'forward_to_rkg'])
    ->name('orders.forward_to_rkg');});



// route for wholesaler
Route::prefix('wholesaler')->middleware(['auth', 'CheckForWholesaler'])->name('wholesaler.')->group(function () {
    Route::get('/home', [App\Http\Controllers\wholesaler\HomeController::class, 'index'])->name('home');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::get('/orders/{id}/verify', [App\Http\Controllers\OrderController::class, 'verify'])
        ->name('order.verify');
    Route::post('/orders/{id}/verify-send', [App\Http\Controllers\OrderController::class, 'verifySend'])
        ->name('order.verifysend');
});

Route::prefix('sub_stockist')->middleware(['auth'])->name('sub_stockist.')->group(function () {
    Route::get('/home', [App\Http\Controllers\SubStockist\HomeController::class, 'index'])->name('home');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::get('/orders/{id}/verify', [App\Http\Controllers\OrderController::class, 'verify'])
    ->name('order.verify');
    Route::get('/shops', [App\Http\Controllers\SalesRep\ShopController::class, 'index'])->name('shop');
    Route::get('/shops/{id}/approve-sales', [App\Http\Controllers\Dist\HomeController::class, 'approve_sales'])->name('approve_sales');
    Route::post('/orders/{id}/verify-send', [App\Http\Controllers\OrderController::class, 'verifySend'])
    ->name('order.verifysend');
    
});


Route::prefix('super_stockist')->middleware(['auth'])->name('super_stockist.')->group(function () {
    Route::get('/home', [App\Http\Controllers\SuperStockist\HomeController::class, 'index'])->name('home');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::post('/forward_to_rkg/{id}', [App\Http\Controllers\OrderController::class, 'forward_to_rkg'])
    ->name('orders.forward_to_rkg');
    Route::post('/orders/{id}/verify-send', [App\Http\Controllers\OrderController::class, 'verifySend'])
    ->name('order.verifysend');
    Route::get('/orders/{id}/verify', [App\Http\Controllers\OrderController::class, 'verify'])
    ->name('order.verify');
});






//salesRep routes
Route::prefix('sales_rep')->middleware(['auth', 'CheckForSalesrep'])->name('sales_rep.')->group(function () {
    Route::get('/home', [App\Http\Controllers\SalesRep\HomeController::class, 'index'])->name('home'); 
    Route::resource('shops', App\Http\Controllers\SalesRep\ShopController::class);
    Route::get('/shops/{id}/convert-sales', [App\Http\Controllers\SalesRep\ShopController::class, 'convertSales'])->name('convert_sales');
    Route::post('/shops/{id}/convert-sales', [App\Http\Controllers\SalesRep\ShopController::class, 'storeConvertSales'])->name('store_convert_sales');

    Route::resource('shop-visits', App\Http\Controllers\SalesRep\ShopVisitController::class);
    
    Route::get('contacts', [App\Http\Controllers\SalesRep\HomeController::class, 'contacts'])->name('contacts');
});

//salesRep routes
Route::prefix('sales_man')->middleware(['auth'])->name('sales_man.')->group(function () {
    Route::get('/home', [App\Http\Controllers\SalesMan\HomeController::class, 'index'])->name('home'); 
    Route::resource('shops', App\Http\Controllers\SalesRep\ShopController::class);
    Route::resource('shop-visits', App\Http\Controllers\SalesRep\ShopVisitController::class);
    Route::get('/shops/{id}/convert-sales', [App\Http\Controllers\SalesRep\ShopController::class, 'convertSales'])->name('convert_sales');
    Route::post('/shops/{id}/convert-sales', [App\Http\Controllers\SalesRep\ShopController::class, 'storeConvertSales'])->name('store_convert_sales');
});