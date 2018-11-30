<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest:api')->name('api.')->group(function() {

    //auth
    Route::post('register', 'Auth\RegisterController@register')->name('register');
    Route::post('login', 'Auth\LoginController@loginApi')->name('login');

});

Route::middleware(['auth:api', 'permission:3'])->prefix('customer')->name('customer.')->group(function() {

    Route::get('dashboard/{id_kategori?}/{seaarch?}', 'Customer\PesananController@dashboard')->name('dashboard');
    Route::post('pesan', 'Customer\PesananController@pesan')->name('pesan');
    Route::get('cart', 'Customer\PesananController@cart')->name('cart');
    Route::get('beli/{id_meja}', 'Customer\PesananController@beli')->name('beli');
    Route::post('beli-alt', 'Customer\PesananController@beliAlt')->name('beli.alt');
    Route::get('cart/delete/{id_pemesanan}', 'Customer\PesananController@cartDelete')->name('cart.delete');
    Route::post('cart/amount/{id_pemesanan}', 'Customer\PesananController@cartAmount')->name('cart.amount');

    //history
    Route::get('transaksi/index/{bulan?}/{tahun?}',
        'Customer\HistoryController@transaksi')->name('transaksi.index');
    Route::get('transaksi/current',
        'Customer\HistoryController@transaksiCurrent')->name('transaksi.current');
    Route::get('dompet/{bulan?}/{tahun?}',
        'Customer\HistoryController@dompet')->name('dompet');

});

Route::middleware(['auth:api', 'permission:2'])->prefix('stan')->name('stan.')->group(function() {

    Route::get('pesanan/index/{bulan?}/{tahun?} ', 'Stan\PesananController@index')->name('pesanan.index');
    Route::get('pesanan/current ', 'Stan\PesananController@current')->name('pesanan.current');
    Route::get('pesanan/konfirmasi/{id_pesanan} ', 'Stan\PesananController@konfirmasi')->name('pesanan.konfirmasi');

    Route::get('history/{tipe?}/{bulan?}/{tahun?}', 'Stan\HistoryController@index')->name('history.index');

    Route::get('deposit', 'Stan\HistoryController@depositIndex')->name('deposit.index');
    Route::post('deposit/store', 'Stan\HistoryController@depositStore')->name('deposit.store');

    Route::get('menu/{id_kategori?}', 'Stan\HistoryController@hidangan')->name('menu.index');
    Route::get('stock/{id_hidangan}', 'Stan\HistoryController@stock')->name('menu.stock');


});

