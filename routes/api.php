<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('salesmen/login', 'API\Salesman\SalesmenLoginController@login');

Route::group(['prefix' => 'salesmen','middleware' => ['jwt.verify','auth:salesmen-api']],function ()
{
    Route::get('customer-list', 'API\Salesman\SalesmenController@customer_list');
    Route::get('customer/{id}', 'API\Salesman\SalesController@customer_data');
    Route::get('products', 'API\Salesman\ProductSearchController@products');
    Route::get('product/{id}', 'API\Salesman\ProductSearchController@product_data');
    Route::get('tax-list', 'API\Salesman\SalesController@tax_list');
    Route::get('payment-account-list/{payment_method}', 'API\Salesman\SalesController@account_list');
    Route::post('store-sale-data', 'API\Salesman\SalesController@store_sale_data');
    Route::post('summary-data', 'API\Salesman\SalesmenController@salesmen_data_summary');
    Route::get('sales-list', 'API\Salesman\SalesController@sales_list');
    Route::get('sale/{id}/view', 'API\Salesman\SalesController@sale_view');
    Route::post('change-password', 'API\Salesman\SalesmenPasswordChangeController@change_password');
});

Route::post('login', 'API\Admin\AdminLoginController@login');

Route::group(['middleware' => ['jwt.verify','auth:api']],function ()
{
    Route::get('dashboard-summary','API\Admin\DashboardController@summaryData');
    Route::get('daily-summary-report','API\Admin\ReportController@daily_summary_report');
    Route::get('material-stock-report','API\Admin\ReportController@material_stock_report');
    Route::get('product-stock-report','API\Admin\ReportController@product_stock_report');
    Route::get('vouchers','API\Admin\VoucherController@index');
    Route::get('voucher/{voucher_no}/delete','API\Admin\VoucherController@destroy');
    Route::post('voucher/approve','API\Admin\VoucherController@voucher_approve');
});
