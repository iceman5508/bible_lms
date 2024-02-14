<?php

use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CertificateVerifyController;
use App\Http\Controllers\Common\WalletRechargeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\Instructor\GmeetSettingController;
use App\Http\Controllers\Student\SubscriptionController as StudentSubscriptionController;
use App\Http\Controllers\VersionUpdateController;
use App\Models\Language;
use Illuminate\Support\Facades\App;
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


Route::group(['middleware' => ['version.update']], function () {

    Route::get('/local/{ln}', function ($ln) {
        $language = Language::where('iso_code', $ln)->first();
        if (!$language){
            $language = Language::find(1);
            if ($language){
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }

        session(['local' => $ln]);
        App::setLocale(session()->get('local'));
        return redirect()->back();
    });

    Auth::routes(['register' => false]);
    Route::get('notification-url/{uuid}', [InstallerController::class, 'notificationUrl'])->name('notification.url');
    Route::post('mark-all-as-read', [InstallerController::class, 'markAllAsReadNotification'])->name('notification.all-read');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/logout', [LoginController::class, 'logout']);
        Route::get('/home', [HomeController::class, 'index'])->name('home');
    });
});

Route::get('version-update', [VersionUpdateController::class, 'versionUpdate'])->name('version-update');
Route::post('process-update', [VersionUpdateController::class, 'processUpdate'])->name('process-update');

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::match(array('GET','POST'), 'verify-certificate', [CertificateVerifyController::class, 'verifyCertificate'])->name('verify_certificate');
Route::match(array('GET','POST'),'/payment-notify/{id}', [PaymentApiController::class, 'paymentNotifier'])->name('paymentNotify');
Route::match(array('GET','POST'),'/payment-notify-subscription/{id}', [PaymentApiController::class, 'paymentSubscriptionNotifier'])->name('paymentNotify.subscription');
Route::match(array('GET','POST'),'/payment-notify-wallet-recharge/{id}', [PaymentApiController::class, 'paymentWalletRechargeNotifier'])->name('paymentNotify.wallet_recharge');

Route::match(array('GET','POST'),'payment-cancel/{id}', [PaymentApiController::class, 'paymentCancel'])->name('paymentCancel');

Route::get('subscription/thank-you', [StudentSubscriptionController::class, 'thankYou'])->name('subscription.thank-you');
Route::get('wallet-recharge/thank-you', [WalletRechargeController::class, 'thankYou'])->name('wallet_recharge.thank-you');
Route::get('instructor/gmeet-callback', [GmeetSettingController::class, 'gMeetCallback'])->name('instructor.gmeet_callback');