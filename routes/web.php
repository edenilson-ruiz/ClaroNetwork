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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/network2g','NetworkMonitorController@showData2g')->name('network2g');
Route::get('/network3g','NetworkMonitorController@index')->name('network3g');
Route::get('/networkLte','NetworkMonitorController@showDataLte')->name('networkLte');
Route::get('/networkWifi','NetworkMonitorController@showDataWifi')->name('networkWifi');
