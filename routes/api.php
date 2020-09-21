<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api')->group(function () {
//     Route::get('/siguns', 'SigunsController@')
//     Route::get('/users')
// })
//
//

// Route::prefix('users')->middleware('auth:api')->group(function () {
//     Route::post('/', 'UserController@store');
//     Route::post('/', 'UserController@store');
// });

Route::get('/siguns', [
    'as' => 'api.siguns',
    'uses' => 'SigunController@index'
]);

Route::get('/users', [
    'as' => 'api.users',
    'uses' => 'UserController@index'
]);
