<?php

use App\Http\Controllers\Common\AffiliateController;
use App\Http\Controllers\Common\FollowController;
use App\Http\Controllers\Common\WalletController;
use App\Http\Controllers\Common\WalletRechargeController;
use App\Http\Controllers\Instructor\SaasController;
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




Route::group(['as' => 'affiliate.','prefix'=>'affiliate'], function () {

    Route::get('become-an-affiliate', [AffiliateController::class, 'becomeAffiliate'])->name('become-an-affiliate');
    Route::post('create-affiliate-request', [AffiliateController::class, 'becomeAffiliateApply'])->name('create-affiliate-request');
    Route::group(['middleware' => ['affiliate']], function () {
        Route::get('dashboard', [AffiliateController::class, 'dashboard'])->name('dashboard');
    });
    Route::get('my-affiliate-list',[AffiliateController::class, 'myAffiliations'])->name('my-affiliate-list');

});

Route::group(['middleware' => ['common']],function(){
    Route::get('follow', [FollowController::class,'follow'])->name('follow');
    Route::get('unfollow', [FollowController::class,'unfollow'])->name('unfollow');
    // Route::get('saas-list', [SaasController::class, 'saasList'])->name('saas_panel');
    Route::get('saas-plan', [SaasController::class, 'saasPlan'])->name('saas_plan');
    Route::get('saas-plan-details/{id}', [SaasController::class, 'saasPlanDetails'])->name('saas_plan_details');
});

Route::group(['as' => 'wallet.','prefix'=>'wallet','middleware' => ['common']], function () {
    Route::get('/', [WalletController::class, 'index'])->name('/');
    Route::get('transaction-history', [WalletController::class, 'transactionHistory'])->name('transaction-history');
    Route::get('withdrawal-history', [WalletController::class, 'WithdrawalHistory'])->name('withdrawal-history');
    Route::get('wallet-recharge-history', [WalletController::class, 'rechargeHistory'])->name('recharge-history');
    Route::post('process-withdraw', [WalletController::class, 'withdrawProcess'])->name('process-withdraw');
    Route::get('beneficiary', [WalletController::class, 'myBeneficiary'])->name('my-beneficiary');

    Route::get('wallet-recharge/checkout', [WalletRechargeController::class, 'checkout'])->name('wallet_recharge.checkout');
    Route::post('wallet-recharge/pay', [WalletRechargeController::class, 'pay'])->name('wallet_recharge.pay');
    Route::post('wallet-recharge/razor-pay-payment', [WalletRechargeController::class, 'razorPayPayment'])->name('wallet_recharge.razor_pay_payment');

    Route::post('save-beneficiary', [WalletController::class, 'saveBeneficiary'])->name('save.my-beneficiary');
    Route::post('status-change-beneficiary/{beneficiary:uuid}', [WalletController::class, 'statusChangeBeneficiary'])->name('beneficiary_status.change');
    Route::post('save-paypal', [WalletController::class, 'savePaypal'])->name('save.paypal');
    Route::get('download-receipt/{uuid}', [WalletController::class, 'downloadReceipt'])->name('download-receipt');

});






