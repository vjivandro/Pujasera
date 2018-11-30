<?php

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
    return redirect()->route('login');
    // return view('welcome');
});

Auth::routes();

Route::get('/home', function (){
    return redirect()->route('admin.stan.index');
});

Route::middleware(['auth', 'permission:1'])->prefix('admin')->name('admin.')->group(function() {
    // stan
    Route::resource('stan', 'StanController', [
        //'except' => ['show', 'index']
    ]);
    Route::get('/stan/{id}/edit/password', 'StanController@editPassword')->name('stan.edit.password');
    Route::put('/stan/{id}/update/password', 'StanController@updatePassword')->name('stan.update.password');

    //kategori
    Route::resource('kategori', 'KategoriController');

    //hidangan
    Route::resource('hidangan', 'HidanganController');

    //meja
    Route::resource('meja', 'MejaController');

    //transaksi
    Route::resource('transaksi', 'TransaksiController');

    //customer
    Route::resource('customer', 'CustomerController');
});

Route::get('/test', function (){

    $first = \App\Customer::selectRaw("nama, saldo, 'customer 12321321' as Source");


    return $users = \App\Stan::selectRaw("nama, saldo, 'stan' as ource")
        ->union($first)
        ->get();

/*    $user = new \App\User();
    $user->username = 'admin';
    $user->password = bcrypt('admin');
    $user->api_token = bcrypt(date('Ymd').$user->username);
    $user->role = 1;
    $user->id_person = null;
    $user->save();

    return $user->api_token;*/
});
