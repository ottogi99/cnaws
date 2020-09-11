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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [
    'as'  => 'home.index',
    'uses' => 'HomeController@index',
]);

/* 사용자 가입 */
Route::get('auth/register', [
    'as'    => 'users.create',
    'uses'  => 'UsersController@create',
]);
Route::post('auth/register', [
    'as'    => 'users.store',
    'uses'  => 'UsersController@store',
]);

/* 사용자 인증 */
Route::get('auth/login', [
  'as'    => 'sessions.create',
  'uses'  => 'SessionsController@create',
]);
Route::post('auth/login', [
  'as'    => 'sessions.store',
  'uses'  => 'SessionsController@store',
]);
Route::get('auth/logout', [
  'as'    => 'sessions.destroy',
  'uses'  => 'SessionsController@destroy',
]);

/* 도로명 주소 */
Route::get('apis', 'ApisController@index');
Route::get('apis/popup', 'ApisController@popup');
Route::post('apis/popup', 'ApisController@callback');
