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
Route::get('home', [
    'as'  => 'home.index2',
    'uses' => 'HomeController@index',
]);

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
Route::get('apis/excel', 'ApisController@excel');

Route::resource('siguns', 'SigunsController');


Route::get('user_histories/export', [
    'as' => 'user_histories.export',
    'uses' => 'UserHistoriesController@export'
]);
Route::resource('user_histories', 'UserHistoriesController');
// export를 resource 보다 아래에 두면 routing 되지 않는다.
// 사용자관리(참여농협정보)
Route::get('users/export', [
    'as' => 'users.export',
    'uses' => 'UsersController@export'
]);
Route::post('users/import', [
    'as' => 'users.import',
    'uses' => 'UsersController@import'
]);
Route::get('users/example', [
    'as'  => 'users.example',
    'uses' => 'UsersController@example'
]);
// Route::get('users/copy/{business_year}', [
//     'as' => 'users.copy',
//     'uses' => 'UsersController@copy'
// ]);
Route::patch('users/activate/{id}', [
    'as' => 'users.activate',
    'uses' => 'UsersController@activate'
]);
Route::get('users/{id}/password/', [
    'as' => 'users.password',
    'uses' => 'ChangePasswordController@index'
]);
Route::patch('users/{id}/password/', [
    'as' => 'users.changePassword',
    'uses' => 'ChangePasswordController@store'
]);
Route::delete('users/delete-multiple', [
    'as'  => 'users.multiple-delete',
    'uses' => 'UsersController@deleteMultiple'
]);
Route::patch('users/toggle-activated', [
    'as'  => 'users.toggle-activated',
    'uses' => 'UsersController@toggleActivated'
]);
Route::patch('users/toggle-allowed', [
    'as'  => 'users.toggle-allowed',
    'uses' => 'UsersController@toggleAllowed'
]);
// 시군에 따라 농협 목록을 가져오기 위한 Ajax 대응
Route::get('users/list/', [
    'as' => 'users.list',
    'uses' => 'UsersController@list'
]);
Route::resource('users', 'UsersController');

// 사업비관리
Route::get('budgets/export', [
    'as' => 'budgets.export',
    'uses' => 'BudgetsController@export'
]);
Route::delete('budgets/delete-multiple', [
    'as'  => 'budgets.multiple-delete',
    'uses' => 'BudgetsController@deleteMultiple'
]);
Route::resource('budgets', 'BudgetsController');

// 소규모·영세소농 모집현황
// Route::resource('small_farmers', 'SmallFarmersController');
// Route::get('small_farmers', [
//     'as' => 'small_farmers.index',
//     'uses' => 'SmallFarmersController@index'
// ]);
// Route::get('small_farmers/create', [
//     'as' => 'small_farmers.create',
//     'uses' => 'SmallFarmersController@create'
// ]);
// Route::post('small_farmers', [
//     'as' => 'small_farmers.store',
//     'uses' => 'SmallFarmersController@store'
// ]);
// // 다중 primary 키를 사용한 경우(농협id, 농가명)
// // Route::get('small_farmers/show/{id}/{name}', [
// //     'as' => 'small_farmers.show',
// //     'uses' => 'SmallFarmersController@show'
// // ]);
// Route::get('small_farmers/{farmer}', [
//     'as' => 'small_farmers.show',
//     'uses' => 'SmallFarmersController@show'
// ]);
// Route::get('small_farmers/{farmer}/edit', [
//     'as' => 'small_farmers.edit',
//     'uses' => 'SmallFarmersController@edit'
// ]);
// Route::put('small_farmers/{farmer}', [
//     'as' => 'small_farmers.update',
//     'uses' => 'SmallFarmersController@update'
// ]);
// Route::delete('small_farmers/{id}', [
//     'as' => 'small_farmers.destroy',
//     'uses' => 'SmallFarmersController@destroy'
// ]);
Route::get('small_farmers/list/', [
    'as' => 'small_farmers.list',
    'uses' => 'SmallFarmersController@list'
]);
Route::get('small_farmers/export', [
    'as' => 'small_farmers.export',
    'uses' => 'SmallFarmersController@export'
]);
// Route::get('small_farmers/import/{file}', [
//     'as' => 'small_farmers.import',
//     'uses' => 'SmallFarmersController@import'
// ]);
Route::post('small_farmers/import', [
    'as' => 'small_farmers.import',
    'uses' => 'SmallFarmersController@import'
]);
Route::delete('small_farmers/delete-multiple', [
    'as'  => 'small_farmers.multiple-delete',
    'uses' => 'SmallFarmersController@deleteMultiple'
]);
Route::get('small_farmers/example', [
    'as'  => 'small_farmers.example',
    'uses' => 'SmallFarmersController@example'
]);
Route::resource('small_farmers', 'SmallFarmersController');

// 대규모전업농 모집현황
Route::get('large_farmers/list/', [
    'as' => 'large_farmers.list',
    'uses' => 'LargeFarmersController@list'
]);
Route::get('large_farmers/export', [
    'as' => 'large_farmers.export',
    'uses' => 'LargeFarmersController@export'
]);
Route::post('large_farmers/import', [
    'as' => 'large_farmers.import',
    'uses' => 'LargeFarmersController@import'
]);
Route::delete('large_farmers/delete-multiple', [
    'as'  => 'large_farmers.multiple-delete',
    'uses' => 'LargeFarmersController@deleteMultiple'
]);
Route::get('large_farmers/example', [
    'as'  => 'large_farmers.example',
    'uses' => 'LargeFarmersController@example'
]);
Route::resource('large_farmers', 'LargeFarmersController');

// 농기계지원반 모집현황
Route::get('machine_supporters/list/', [
    'as' => 'machine_supporters.list',
    'uses' => 'MachineSupportersController@list'
]);
Route::get('machine_supporters/export', [
    'as' => 'machine_supporters.export',
    'uses' => 'MachineSupportersController@export'
]);
Route::post('machine_supporters/import', [
    'as' => 'machine_supporters.import',
    'uses' => 'MachineSupportersController@import'
]);
Route::delete('machine_supporters/delete-multiple', [
    'as'  => 'machine_supporters.multiple-delete',
    'uses' => 'MachineSupportersController@deleteMultiple'
]);
Route::get('machine_supporters/example', [
    'as'  => 'machine_supporters.example',
    'uses' => 'MachineSupportersController@example'
]);
Route::resource('machine_supporters', 'MachineSupportersController');

// 인력지원반 모집현황
Route::get('manpower_supporters/list/', [
    'as' => 'manpower_supporters.list',
    'uses' => 'ManpowerSupportersController@list'
]);
Route::get('manpower_supporters/export', [
    'as' => 'manpower_supporters.export',
    'uses' => 'ManpowerSupportersController@export'
]);
Route::post('manpower_supporters/import', [
    'as' => 'manpower_supporters.import',
    'uses' => 'ManpowerSupportersController@import'
]);
Route::delete('manpower_supporters/delete-multiple', [
    'as'  => 'manpower_supporters.multiple-delete',
    'uses' => 'ManpowerSupportersController@deleteMultiple'
]);
Route::get('manpower_supporters/example', [
    'as'  => 'manpower_supporters.example',
    'uses' => 'ManpowerSupportersController@example'
]);
Route::resource('manpower_supporters', 'ManpowerSupportersController');

// 교육/홍보비 지출현황
Route::get('status_education_promotions/export', [
    'as' => 'status_education_promotions.export',
    'uses' => 'StatusEducationPromotionsController@export'
]);
Route::post('status_education_promotions/import', [
    'as' => 'status_education_promotions.import',
    'uses' => 'StatusEducationPromotionsController@import'
]);
Route::delete('status_education_promotions/delete-multiple', [
    'as'  => 'status_education_promotions.multiple-delete',
    'uses' => 'StatusEducationPromotionsController@deleteMultiple'
]);
Route::get('status_education_promotions/example', [
    'as'  => 'status_education_promotions.example',
    'uses' => 'StatusEducationPromotionsController@example'
]);
Route::resource('status_education_promotions', 'StatusEducationPromotionsController');

// 농기계작업반 지원현황
Route::get('status_machine_supporters/export', [
    'as' => 'status_machine_supporters.export',
    'uses' => 'StatusMachineSupportersController@export'
]);
Route::post('status_machine_supporters/import', [
    'as' => 'status_machine_supporters.import',
    'uses' => 'StatusMachineSupportersController@import'
]);
Route::get('status_machine_supporters/calc', [
    'as' => 'status_machine_supporters.calc',
    'uses' => 'StatusMachineSupportersController@calc'
]);
Route::delete('status_machine_supporters/delete-multiple', [
    'as'  => 'status_machine_supporters.multiple-delete',
    'uses' => 'StatusMachineSupportersController@deleteMultiple'
]);
Route::get('status_machine_supporters/example', [
    'as'  => 'status_machine_supporters.example',
    'uses' => 'StatusMachineSupportersController@example'
]);
Route::resource('status_machine_supporters', 'StatusMachineSupportersController');

// 인력지원반 지원현황
Route::get('status_manpower_supporters/export', [
    'as' => 'status_manpower_supporters.export',
    'uses' => 'StatusManpowerSupportersController@export'
]);
Route::post('status_manpower_supporters/import', [
    'as' => 'status_manpower_supporters.import',
    'uses' => 'StatusManpowerSupportersController@import'
]);
Route::delete('status_manpower_supporters/delete-multiple', [
    'as'  => 'status_manpower_supporters.multiple-delete',
    'uses' => 'StatusManpowerSupportersController@deleteMultiple'
]);
Route::get('status_manpower_supporters/example', [
    'as'  => 'status_manpower_supporters.example',
    'uses' => 'StatusManpowerSupportersController@example'
]);
Route::resource('status_manpower_supporters', 'StatusManpowerSupportersController');

// 센터운영비(인건비) 지급현황
Route::get('status_labor_payments/export', [
    'as' => 'status_labor_payments.export',
    'uses' => 'StatusLaborPaymentsController@export'
]);
Route::post('status_labor_payments/import', [
    'as' => 'status_labor_payments.import',
    'uses' => 'StatusLaborPaymentsController@import'
]);
Route::delete('status_labor_payments/delete-multiple', [
    'as'  => 'status_labor_payments.multiple-delete',
    'uses' => 'StatusLaborPaymentsController@deleteMultiple'
]);
Route::delete('status_labor_payments/delete-multiple', [
    'as'  => 'status_labor_payments.multiple-delete',
    'uses' => 'StatusLaborPaymentsController@deleteMultiple'
]);
Route::get('status_labor_payments/example', [
    'as'  => 'status_labor_payments.example',
    'uses' => 'StatusLaborPaymentsController@example'
]);
Route::resource('status_labor_payments', 'StatusLaborPaymentsController');

// 센터운영비(운영비) 지급현황
Route::get('status_operating_costs/export', [
    'as' => 'status_operating_costs.export',
    'uses' => 'StatusOperatingCostsController@export'
]);
Route::post('status_operating_costs/import', [
    'as' => 'status_operating_costs.import',
    'uses' => 'StatusOperatingCostsController@import'
]);
Route::delete('status_operating_costs/delete-multiple', [
    'as'  => 'status_operating_costs.multiple-delete',
    'uses' => 'StatusOperatingCostsController@deleteMultiple'
]);
Route::delete('status_operating_costs/delete-multiple', [
    'as'  => 'status_operating_costs.multiple-delete',
    'uses' => 'StatusOperatingCostsController@deleteMultiple'
]);
Route::get('status_operating_costs/example', [
    'as'  => 'status_operating_costs.example',
    'uses' => 'StatusOperatingCostsController@example'
]);
Route::resource('status_operating_costs', 'StatusOperatingCostsController');

// 농작업지원 운영실적(통계)
Route::get('performance_operating', [
    'as' => 'performance_operating.index',
    'uses' => 'PerformanceOperatingController@index'
]);
Route::get('performance_operating/export/', [
    'as' => 'performance_operating.export',
    'uses' => 'PerformanceOperatingController@export'
]);

// 농작업지원 집행실적(통계)
Route::get('performance_executive', [
    'as' => 'performance_executive.index',
    'uses' => 'PerformanceExecutiveController@index'
]);
Route::get('performance_executive/export/', [
    'as' => 'performance_executive.export',
    'uses' => 'PerformanceExecutiveController@export'
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

/* 데이터 입력 스케쥴 */
Route::get('schedules/', [
  'as'    => 'schedules.show',
  'uses'  => 'SchedulesController@show',
]);
Route::get('schedules/{id}/edit', [
  'as'    => 'schedules.edit',
  'uses'  => 'SchedulesController@edit',
]);
Route::put('schedules/{id}', [
  'as'    => 'schedules.update',
  'uses'  => 'SchedulesController@update',
]);
