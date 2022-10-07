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

Route::get('xStologs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('sign-in', function () {
	if (\Auth::check()) return redirect('/');
	return view('auth.login');
});

Route::get('sign-up', function () {
	if (\Auth::check()) return redirect('/');
	return view('auth.register'); 
});

Route::post('sign-up', 'Auth\LoginController@postRegister')->name('sign-up');
Route::post('sign-in', 'Auth\LoginController@postLogin')->name('sign-in');
Route::post('sign-validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\LoginController@postValidateToken'])->name('sign-validate');

Route::get('sign-validate', 'Auth\LoginController@getValidateToken');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('email-verification/{id?}', 'Auth\LoginController@emailVerification');
Route::post('activation', 'Auth\LoginController@emailActivation')->name('activation');
Route::get('forgot-password', function () {
	return view('auth.forget-password');
});
Route::get('resend-otp', 'Auth\LoginController@resendOtp')->name('resendotp');
Route::get('login-otp', 'Auth\LoginController@loginOtp')->name('login-otp');
Route::post('forget-password', 'Auth\LoginController@postForgetPassword')->name('auth.forget-password');
Route::get('reset-password/{token}', 'Auth\LoginController@getResetPassword');
Route::post('reset-password', 'Auth\LoginController@resetPassword')->name('auth.reset-password');
Route::get('/', function () {
	$data['pageName'] = 'Home';
	return view('home', compact('data'));
});


Route::middleware('auth')->group(function () {
	Route::post('sendotp-mail', 'UserController@commonotpGenerate')->name('sendotptomail');

	Route::get('account', 'AccountController@index');

	Route::get('profile-verification', 'AccountController@getVerification');

	Route::get('change-password', 'UserController@getChangePassword');
	Route::post('change-password', 'UserController@postChangePassword')->name('change-password');
	Route::get('country', 'CommonController@getCountry');

	//Google 2 factor 
	Route::get('2fa-enable', 'Google2FAController@enableTwoFactor');
	Route::get('2fa-disable', 'Google2FAController@disableTwoFactor');
	Route::post('g2f-otp-check', 'Google2FAController@g2fotpcheckenable')->name('g2f-otp-check');

	Route::get('notification-settings', 'AccountController@getNotificationSettings');
	Route::post('notification-settings', 'AccountController@posNotificationSettings')->name('notification-settings');
	Route::post('profile-update', 'AccountController@postProfileUpdate')->name('profile-update');
	Route::post('update-kyc', 'AccountController@updatekycDoc')->name('update-kyc');

	Route::get('referral-history', 'UserController@getReferral');

	Route::get('bank/{id?}', 'UserController@getBank');
	Route::get('banks', 'UserController@getBanks');
	Route::post('bank', 'UserController@postBank')->name('bank');

	Route::get('exchange', 'ExchangeController@index');
	Route::get('deposit', 'ExchangeController@getDeposits');
	Route::get('load-view', 'ExchangeController@getExchange');
	Route::post('currency-balance', 'ExchangeController@currencyBalance');
	Route::post('exchange', 'ExchangeController@postExchange')->name('exchange');
	Route::get('balance', 'ExchangeController@getBalances');
	Route::post('exchange-update', 'ExchangeController@postExchangeApprove')->name('exchange-update');
	Route::post('calculate-coin', 'ExchangeController@calculateCoin')->name('calculate-coin');

	Route::get('deposit-address', 'ExchangeController@depositAddress');//
	
	Route::post('deposit-address', 'ExchangeController@depositAddress')->name('deposit-address');
	Route::get('sell/{key}', 'ExchangeController@sell');
	Route::post('sell-order', 'ExchangeController@sellOrder')->name('sell-order');
	Route::get('list-sellorder', 'ExchangeController@listSellOrder');
	Route::get('edit-sellorder/{id}', 'ExchangeController@editSellorder');
	// withdraw
	Route::get('withdraw-request', 'WithdrawController@getWithdrawRequest');
	Route::get('withdraw/{key}', 'WithdrawController@getWithdraw');
	Route::post('withdraw', 'WithdrawController@postWithdraw')->name('withdraw');
	Route::post('send-otp', ['middleware' => 'throttle:5', 'uses' => 'WithdrawController@sendOtp']);

	Route::get('p2p-exchange', 'ExchangeController@exchangeList');
	Route::get('buy/exchange-form/{id}', 'ExchangeController@exchangeForm');
	Route::any('coin-request', 'ExchangeController@coinRequest')->name('coin-request');
	Route::any('document-update/{id}', 'ExchangeController@coinRequest')->name('document.update');
	Route::any('upload-document-proof', 'ExchangeController@uploadDocumentProof')->name('upload-document-proof');
	Route::any('outcoming-coin-request', 'ExchangeController@outcomingCoinRequest')->name('outcoming-coin-request');
	Route::any('incoming-coin-request', 'ExchangeController@incomingCoinRequest')->name('incoming-coin-request');
	Route::get('get-requested-coin-status/{id}', 'ExchangeController@updateCoinStatus');
	Route::any('update-coin-status', 'ExchangeController@updateCoinStatus')->name('update-coin-status');
	Route::get('advertiser-detail/{id}', 'ExchangeController@advertiserDetail');
	Route::post('sell-order-fillter', 'ExchangeController@sellOrderFillter')->name('sell.order.fillter');


	
	// stacking process	 
	Route::get('stacking-address/{id}', 'StackingContractController@getContractAddress');
	Route::get('stacking-contract', 'StackingContractController@getStacking');
	Route::post('stacking-contract', 'StackingContractController@postStacking')->name('stacking-contract');
	Route::get('contract-update/{id}', 'StackingContractController@viewContract');
	Route::post('contract-update', 'StackingContractController@postContract')->name('contract-update');
	Route::get('contract', 'StackingContractController@getContract');
	Route::get('bonus', 'StackingContractController@getBonus');
	Route::get('transactions', 'StackingContractController@getTransacion');
	Route::get('load-stacking', 'StackingContractController@getStackingView');
	Route::post('stacking-update', 'StackingContractController@postStackingApprove')->name('stacking-update');
	Route::get('re-stacking/{coin}', 'DashboardController@reStacking');
	Route::delete('remove-stacking', 'StackingContractController@deleteStacking')->name('remove-update');
	
});

// Route::get('trade/{pair}', 'TradeController@advanceTrade');
// Route::get('show-advance-data', 'TradeController@showAdvanceUserBalance');
// Route::get('coin-pairs', 'TradeController@coinPairs');
// Route::get('chart/{coin}/{type}', 'TradeController@chart');
// Route::get('pair-data-advance/{id}', 'TradeController@getPairDataAdvance');
// Route::get('pair-data-advance/{id}/{id1}', 'TradeController@getPairDataAdvance');
