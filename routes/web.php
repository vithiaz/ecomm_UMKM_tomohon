<?php

use App\Http\Livewire\RegisterPage;
use App\Http\Livewire\Base\Homepage;
use App\Http\Livewire\Base\UmkmPage;
use App\Http\Livewire\User\CartPage;
use App\Http\Livewire\User\EditUmkm;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Admin\Products;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Base\SearchPage;
use App\Http\Livewire\User\AddProduct;
use App\Http\Livewire\Base\ProductPage;
use App\Http\Livewire\User\EditProduct;
use App\Http\Livewire\User\UmkmProfile;
use App\Http\Livewire\Admin\UmkmPayment;
use App\Http\Livewire\User\CheckoutPage;
use App\Http\Livewire\User\RegisterUmkm;
use App\Http\Livewire\Base\ProductDetail;
use App\Http\Livewire\Admin\ProductReview;
use App\Http\Livewire\Admin\RefoundPayment;
use App\Http\Livewire\User\AccountSettings;
use App\Http\Livewire\User\TransactionPage;
use App\Http\Livewire\User\UmkmTransaction;
use App\Http\Livewire\Admin\ProductCategory;
use App\Http\Livewire\Admin\SellingProducts;
use App\Http\Livewire\Base\UmkmProductsPage;
use App\Http\Livewire\Admin\UmkmRegistration;
use App\Http\Livewire\User\RefoundTransaction;
use App\Http\Livewire\Admin\ProductVerification;
use App\Http\Livewire\Admin\UmkmRegistrationReview;
use App\Http\Livewire\Admin\UmkmAccountVerification;
use App\Http\Controllers\Admin\UmkmBankAccountMethods;
use App\Http\Controllers\Admin\ProductCategory as ProductCategoryMethods;


Route::get('/', Homepage::class)->name('homepage');
Route::get('/products/{category_slug}', ProductPage::class)->name('product-page');
Route::get('/umkm', UmkmPage::class)->name('umkm-page');
Route::get('/umkm-product/{umkm_id}', UmkmProductsPage::class)->name('umkm-products');
Route::get('/search/{search_key}', SearchPage::class)->name('search-page');
Route::get('/product/{product_id}-{name_slug}', ProductDetail::class)->name('product-details');
Route::get('/register', RegisterPage::class)->name('register');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->back();
})->name('logout');

// Users Auth Middleware Group
Route::middleware('auth')->group(function () {
    Route::get('/account-settings', AccountSettings::class)->name('account-settings');
    Route::get('/my-cart', CartPage::class)->name('cart-page');
    Route::get('/checkout', CheckoutPage::class)->name('checkout');

    Route::get('/{umkm}/add-product', AddProduct::class)->name('add-product');
    Route::get('/{umkm}/{product}/edit', EditProduct::class)->name('edit-product');
    
    Route::get('/transactions/{status}', TransactionPage::class)->name('transaction-page');
    Route::get('/refound-transaction/{transaction_id}', RefoundTransaction::class)->name('refound-transaction-page');

    Route::get('/umkm/register', RegisterUmkm::class)->name('umkm.register');
    Route::get('/umkm/edit/{umkm}', EditUmkm::class)->name('umkm.edit');
    Route::get('/umkm/profile', UmkmProfile::class)->name('umkm.profile');
    Route::get('/umkm/transaction/{status}', UmkmTransaction::class)->name('umkm.transaction');    
});

// Admin Auth Middleware Group
Route::middleware(['auth', 'admin'])->prefix('/admin')->group(function () {
    Route::get('/product-categories', ProductCategory::class)->name('admin.product-categories');
    Route::delete('/product-categories/delete/{id}', [ProductCategoryMethods::class, 'delete'])->name('admin.product-categories.delete');
    
    Route::get('/products/{status}', Products::class)->name('admin.products');
    Route::get('/product/{product_id}-{name_slug}', ProductReview::class)->name('admin.product-review');
    Route::get('/selling-product', SellingProducts::class)->name('admin.selling-product');

    
    Route::get('/umkm/registrations/{status}', UmkmRegistration::class)->name('admin.umkm-registration');
    Route::get('/umkm/registration/{user_id}-{reg_id}', UmkmRegistrationReview::class)->name('admin.umkm-registration-review');
    
    Route::get('/umkm/accounts/{status}', UmkmAccountVerification::class)->name('admin.umkm-account-verification');    
    Route::post('/umkm/account/confirm/{account_number}-{id}', [UmkmBankAccountMethods::class, 'confirm_request'])->name('admin.umkm-account.confirm');    
    Route::post('/umkm/account/reject/{account_number}-{id}', [UmkmBankAccountMethods::class, 'reject_request'])->name('admin.umkm-account.reject');    
    Route::post('/umkm/account/revoke/{account_number}-{id}', [UmkmBankAccountMethods::class, 'revoke_request'])->name('admin.umkm-account.revoke');    

    Route::get('/umkm/payment/{status}', UmkmPayment::class)->name('admin.umkm-payment');
    Route::get('/refound-payment/{status}', RefoundPayment::class)->name('admin.refound-payment');
});

// Checkpoint
// handle product stock