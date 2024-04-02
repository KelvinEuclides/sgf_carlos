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
Auth::routes();

Route::get(
    '/', [
           'as' => 'home',
           'uses' => 'HomeController@index',
       ]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/home', [
               'as' => 'home',
               'uses' => 'HomeController@index',
           ]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/stripe', [
                 'as' => 'stripe.post',
                 'uses' => 'HomeController@stripe',
             ]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/check', 'HomeController@check')->middleware(
    [
        'auth',
        'XSS',
    ]
);

//------------------------------------Members-------------------------------------------

Route::resource('roles', 'RoleController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('permissions', 'PermissionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('customers', 'UserController@customer')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.index');
Route::get('customers/create', 'UserController@customerCreate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.create');
Route::post('customers/store', 'UserController@customerStore')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.store');
Route::get('customers/edit/{id}', 'UserController@customerEdit')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.edit');
Route::put('customers/update/{id}', 'UserController@customerUpdate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.update');
Route::delete('customers/destroy/{id}', 'UserController@customerDestroy')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.destroy');
Route::get('customers/show/{id}', 'UserController@customerShow')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('customers.show');

Route::get('vendors', 'UserController@vendor')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.index');
Route::get('vendors/create', 'UserController@vendorCreate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.create');
Route::post('vendors/store', 'UserController@vendorStore')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.store');
Route::get('vendors/edit/{id}', 'UserController@vendorEdit')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.edit');
Route::put('vendors/update/{id}', 'UserController@vendorUpdate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.update');
Route::delete('vendors/destroy/{id}', 'UserController@vendorDestroy')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.destroy');
Route::get('vendors/show/{id}', 'UserController@vendorShow')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('vendors.show');

Route::put('customer/billing-detail', 'UserController@customerEditBilling')->name('customer.billing.detail')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put('customer/shipping-detail', 'UserController@customerEditShipping')->name('customer.shipping.detail')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put('vendor/billing-detail', 'UserController@vendorEditBilling')->name('vendor.billing.detail')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put('vendor/shipping-detail', 'UserController@vendorEditShipping')->name('vendor.shipping.detail')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('users', 'UserController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::get('profile', 'UserController@profile')->name('profile')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put('edit-profile', 'UserController@editprofile')->name('update.account')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::put('change-password', 'UserController@updatePassword')->name('update.password');


//------------------------------------Constant-------------------------------------------

Route::get('category/item', 'CategoryController@itemCategoryIndex')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.item.index');
Route::get('category/item/create', 'CategoryController@itemCategoryCreate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.item.create');
Route::post('category/item/store', 'CategoryController@itemCategoryStore')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.item.store');
Route::get('category/item/edit/{id}', 'CategoryController@itemCategoryEdit')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.item.edit');
Route::put('category/item/update/{id}', 'CategoryController@itemCategoryUpdate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.item.update');
Route::delete('category/item/destroy/{id}', 'CategoryController@itemCategoryDestroy')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.item.destroy');


Route::get('category/income', 'CategoryController@incomeCategoryIndex')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.income.index');
Route::get('category/income/create', 'CategoryController@incomeCategoryCreate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.income.create');
Route::post('category/income/store', 'CategoryController@incomeCategoryStore')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.income.store');
Route::get('category/income/edit/{id}', 'CategoryController@incomeCategoryEdit')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.income.edit');
Route::put('category/income/update/{id}', 'CategoryController@incomeCategoryUpdate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.income.update');
Route::delete('category/income/destroy/{id}', 'CategoryController@incomeCategoryDestroy')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.income.destroy');


Route::get('category/expense', 'CategoryController@expenseCategoryIndex')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.expense.index');
Route::get('category/expense/create', 'CategoryController@expenseCategoryCreate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.expense.create');
Route::post('category/expense/store', 'CategoryController@expenseCategoryStore')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.expense.store');
Route::get('category/expense/edit/{id}', 'CategoryController@expenseCategoryEdit')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.expense.edit');
Route::put('category/expense/update/{id}', 'CategoryController@expenseCategoryUpdate')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.expense.update');
Route::delete('category/expense/destroy/{id}', 'CategoryController@expenseCategoryDestroy')->middleware(
    [
        'auth',
        'XSS',
    ]
)->name('category.expense.destroy');


Route::resource('tax', 'TaxController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('unit', 'UnitController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


//-----------------------------------Item------------------------------------------------------------------------------
Route::resource('item', 'ItemController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


//-----------------------------------Estimation------------------------------------------------------------------------------
Route::get('estimation/{id}/status/change', 'EstimationController@statusChange')->name('estimation.status.change');
Route::get('estimation/pdf/{id}', 'EstimationController@estimation')->name('estimation.pdf')->middleware(['XSS',]);
Route::post('estimation/product', 'EstimationController@product')->name('estimation.product');
Route::get('estimation/item', 'EstimationController@item')->name('estimation.item');
Route::post('estimation/item/destroy', 'EstimationController@itemDestroy')->name('estimation.item.destroy');
Route::get('estimation/{id}/sent', 'EstimationController@sent')->name('estimation.sent');
Route::get('estimation/{id}/resent', 'EstimationController@resent')->name('estimation.resent');
Route::resource('estimation', 'EstimationController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('customer/estimation', 'EstimationController@customerEstimation')->name('customer.estimation');
Route::get('customer/estimation/{id}', 'EstimationController@customerEstimationShow')->name('customer.estimation.show');

//--------------------------------------Sales----------------------------------------------------------------------------------
Route::get('invoice/{id}/status/change', 'InvoiceController@statusChange')->name('invoice.status.change');
Route::get('invoice/pdf/{id}', 'InvoiceController@invoice')->name('invoice.pdf')->middleware(['XSS',]);
Route::post('invoice/product', 'InvoiceController@product')->name('invoice.product');
Route::get('invoice/item', 'InvoiceController@item')->name('invoice.item');
Route::post('invoice/item/destroy', 'InvoiceController@itemDestroy')->name('invoice.item.destroy');
Route::get('invoice/{id}/sent', 'InvoiceController@sent')->name('invoice.sent');
Route::get('invoice/{id}/resent', 'InvoiceController@resent')->name('invoice.resent');
Route::get('invoice/{id}/payment', 'InvoiceController@payment')->name('invoice.payment');
Route::post('invoice/{id}/payment', 'InvoiceController@createPayment')->name('invoice.payment');
Route::post('invoice/{id}/payment/{pid}/destroy', 'InvoiceController@paymentDestroy')->name('invoice.payment.destroy');
Route::resource('invoice', 'InvoiceController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('customer/invoice', 'InvoiceController@customerInvoice')->name('customer.invoice');
Route::get('customer/invoice/{id}', 'InvoiceController@customerInvoiceShow')->name('customer.invoice.show');

Route::resource('income', 'IncomeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('customer/payment', 'IncomeController@customerPayment')->name('customer.payment');
//--------------------------------------Purchese----------------------------------------------------------------------------------

Route::get('bill/{id}/status/change', 'BillController@statusChange')->name('bill.status.change');
Route::get('bill/pdf/{id}', 'BillController@bill')->name('bill.pdf')->middleware(['XSS',]);
Route::post('bill/product', 'BillController@product')->name('bill.product');
Route::get('bill/item', 'BillController@item')->name('bill.item');
Route::post('bill/item/destroy', 'BillController@itemDestroy')->name('bill.item.destroy');
Route::get('bill/{id}/sent', 'BillController@sent')->name('bill.sent');
Route::get('bill/{id}/resent', 'BillController@resent')->name('bill.resent');
Route::get('bill/{id}/payment', 'BillController@payment')->name('bill.payment');
Route::post('bill/{id}/payment', 'BillController@createPayment')->name('bill.payment');
Route::post('bill/{id}/payment/{pid}/destroy', 'BillController@paymentDestroy')->name('bill.payment.destroy');
Route::resource('bill', 'BillController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('vendor/bill', 'BillController@vendorBill')->name('vendor.bill');
Route::get('vendor/bill/{id}', 'BillController@vendorBillShow')->name('vendor.bill.show');

Route::get('vendor/payment', 'ExpenseController@vendorPayment')->name('vendor.payment');

Route::resource('expense', 'ExpenseController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


//--------------------------------------Banking----------------------------------------------------------------------------------
Route::resource('account', 'BankAccountController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('transfer', 'TransferController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------------Settings-----------------------------------------------------------------------------------
Route::get('setting', 'SettingController@index')->name('setting.index')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('site-setting', 'SettingController@companySiteSetting')->name('site.setting')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('common-setting', 'SettingController@commonSetting')->name('common.setting')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('system-setting', 'SettingController@systemSetting')->name('system.setting')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('email-setting', 'SettingController@saveEmailSettings')->name('email.setting')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('payment-setting', 'SettingController@savePaymentSettings')->name('payment.setting');

//--------------------------------------Subscription----------------------------------------------------------------------------------
Route::resource('subscription', 'SubscriptionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


//------------------------------------------- Voucher / Subscriber-------------------------------------------------------------------------


Route::get(
    '/apply-voucher', [
                        'as' => 'apply.voucher',
                        'uses' => 'VoucherController@applyVoucher',
                    ]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('voucher', 'VoucherController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::get('/subscriber', 'StripePaymentController@index')->name('subscriber.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');

}
);
Route::post('subscription-pay-with-paypal', 'PaypalController@subscriptionPayWithPaypal')->name('subscription.pay.with.paypal')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('{id}/subscription-get-payment-status', 'PaypalController@subscriptionGetPaymentStatus')->name('subscription.get.payment.status')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('user/{id}/subscription', 'UserController@upgradeSubscription')->name('subscription.upgrade')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('user/{id}/subscription/{pid}', 'UserController@activeSubscription')->name('subscription.active')->middleware(
    [
        'auth',
        'XSS',
    ]
);

//------------------------------------------------Lnaguage------------------------------------------------------
Route::get('change-language/{lang}', 'LanguageController@changeLanquage')->name('change.language');
Route::get('manage-language/{lang}', 'LanguageController@manageLanguage')->name('manage.language');
Route::get('create-language', 'LanguageController@createLanguage')->name('create.language');
Route::post('store-language-data/{lang}', 'LanguageController@storeLanguageData')->name('store.language.data');
Route::post('store-language', 'LanguageController@storeLanguage')->name('store.language');

//-----------------------------------------Summary-------------------------------------------------------------------


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::get('summary/estimation', 'ReportController@estimationSummary')->name('estimation.summary');
    Route::get('summary/estimation/list', 'ReportController@estimationSummaryList')->name('estimation.summary.list');
    Route::get('summary/invoice', 'ReportController@invoiceSummary')->name('invoice.summary');
    Route::get('summary/invoice/list', 'ReportController@invoiceSummaryList')->name('invoice.summary.list');
    Route::get('summary/bill', 'ReportController@billSummary')->name('bill.summary');
    Route::get('summary/bill/list', 'ReportController@billSummaryList')->name('bill.summary.list');
    Route::get('summary/sales', 'ReportController@salesSummary')->name('sales.summary');
    Route::get('summary/sales/list', 'ReportController@salesSummaryList')->name('sales.summary.list');
    Route::get('summary/purchase', 'ReportController@purchaseSummary')->name('purchase.summary');
    Route::get('summary/purchase/list', 'ReportController@purchaseSummaryList')->name('purchase.summary.list');

}
);
