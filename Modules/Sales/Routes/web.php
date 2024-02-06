<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;
use Modules\Sales\Http\Controllers\CustomerController;
use Modules\Sales\Http\Controllers\SalesZoneController;
use Modules\Sales\Http\Controllers\SalesOrderController;
use Modules\Sales\Http\Controllers\DeliveryOrderController;
use Modules\SupplyChain\Http\Controllers\ProductController;
use Modules\Sales\Http\Controllers\DeliveryChallanController;
use Modules\Sales\Http\Controllers\SalesCollectionController;
use Modules\Sales\Http\Controllers\SalesPersonTargetController;
use Modules\Sales\Http\Controllers\SalesReturnController;
use Modules\SupplyChain\Http\Controllers\ProductSizeController;
use Modules\SupplyChain\Http\Controllers\ProductTypeController;

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

Route::prefix('sales')->group(function () {
    Route::get('/', 'SalesController@index');

    Route::middleware(['auth'])->group(function () {
        Route::resources([
            'customers' => CustomerController::class,
            'sales-orders' => SalesOrderController::class,
            'delivery-orders' => DeliveryOrderController::class,
            'delivery-challans' => DeliveryChallanController::class,
            'sales-collections' => SalesCollectionController::class,
            'sales-zones' => SalesZoneController::class,
            'sales-person-targets' => SalesPersonTargetController::class,
            'sales-returns' => SalesReturnController::class,
        ]);

        Route::get('get-product-types', [ProductTypeController::class, 'getProductTypes'])->name('getProductTypes');
        Route::get('get-products', [ProductController::class, 'getRawMaterials'])->name('getProducts');
        Route::get('get-product-sizes', [ProductSizeController::class, 'getProductSizes'])->name('getProductSizes');
        Route::get('all-products', [ProductController::class, 'allProducts'])->name('allProducts');
        Route::get('getPreviousDue', [DeliveryOrderController::class, 'getPreviousDue'])->name('getPreviousDue');
        Route::get('delivery-order/{id}/print', [DeliveryOrderController::class, 'print'])->name('deliveryOrder.print');
        Route::get('delivery-order/{id}/print-without-value', [DeliveryOrderController::class, 'printWithoutValue'])->name('deliveryOrder.print-without-value');
        Route::get('sales-order/{id}/print', [SalesOrderController::class, 'print'])->name('salesOrder.print');
        Route::get('delivery-challan/{id}/print', [DeliveryChallanController::class, 'print'])->name('deliveryChallan.print');
        Route::get('delivery-challan/{id}/invoice', [DeliveryChallanController::class, 'invoice'])->name('deliveryChallan.invoice');
        Route::get('delivery-sales-collection', [SalesController::class, 'deliverySalesCollection'])->name('deliverySalesCollection');
        Route::post('delivery-sales-collection/report', [SalesController::class, 'deliverySalesCollectionReport'])->name('deliverySalesCollection.report');
        Route::get('delivery-sales-collection-employee-target', [SalesController::class, 'deliverySalesCollectionEmployeeTarget'])->name('deliverySalesCollectionEmployeeTarget');
        Route::post('delivery-sales-collection-employee-target/report', [SalesController::class, 'deliverySalesCollectionEmployeeTargetReport'])->name('deliverySalesCollectionEmployeeTarget.report');

        Route::get('sales-return', [SalesController::class, 'salesReturn'])->name('salesReturn');
        Route::post('sales-return/report', [SalesController::class, 'salesReturnReport'])->name('salesReturn.report');

        Route::get('delivery-challan', [SalesController::class, 'deliveryChallan'])->name('deliveryChallan');
        Route::post('delivery-challan/report', [SalesController::class, 'deliveryChallanReport'])->name('deliveryChallan.report');

        Route::get('do-wise-delivery-challan', [SalesController::class, 'doWiseDeliveryChallan'])->name('doWiseDeliveryChallan');
        Route::post('do-wise-delivery-challan/report', [SalesController::class, 'doWiseDeliveryChallanReport'])->name('doWiseDeliveryChallan.report');






        Route::get('get-delivery-order-details/{id}', [DeliveryOrderController::class, 'getDeliveryOrderDetails'])->name('getDeliveryOrderDetails');
        Route::get('get-delivery-challan-details/{id}', [DeliveryChallanController::class, 'getDeliveryChallanDetails'])->name('getDeliveryChallanDetails');

        Route::post('getCustomerDetails', [CustomerController::class, 'getCustomerDetails'])->name('getCustomerDetails');
        Route::get('getCategoryWiseProducts', [SalesController::class, 'getCategoryWiseProducts'])->name('getCategoryWiseProducts');

        Route::get('/delivery-orders/complete/{requisition_id}', [DeliveryOrderController::class, 'complete'])->name('delivery-orders.complete');
    });
});
