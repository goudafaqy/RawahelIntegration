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


// Update
Route::get('index',  ['as' => 'rawahel', 'uses' => 'rawahelController@index']);
Route::get('test',  ['as' => 'test', 'uses' => 'testController@test']);

// Auth
Route::get('login',  ['as' => 'login', 'uses' => 'authController@login']);
Route::get('logout',  ['as' => 'logout', 'uses' => 'authController@logout']);

Route::get('units',  ['as' => 'units', 'uses' => 'unitsController@index']);
Route::get('amps',  ['as' => 'amps', 'uses' => 'rawahelController@amps']);
Route::get('statues',  ['as' => 'statues', 'uses' => 'rawahelController@statues']);
Route::get('trips',  ['as' => 'trips', 'uses' => 'rawahelController@trips']);
Route::get('events',  ['as' => 'events', 'uses' => 'eventsController@index']);
Route::get('data',  ['as' => 'data', 'uses' => 'eventsController@data']);
Route::get('view/{id}',  ['as' => 'view', 'uses' => 'eventsController@view']);
Route::get('avl',  ['as' => 'avl', 'uses' => 'avlController@index']);
Route::get('unplanned',  ['as' => 'unplanned', 'uses' => 'eventsController@unplanned']);
Route::get('drivers',  ['as' => 'driversController', 'uses' => 'driversController@index']);
