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
    return view('auth.login');
});
// Route::get('/admin', function() {
//     return view('/admin/login');
// });


Auth::routes();


Route::group(['prefix'=>'painel','middleware' => 'auth'], function() {
  Route::get('/home', 'HomeController@index')->name('home');


  Route::resource('lote', 'Painel\LotController');
  Route::resource('animais', 'Painel\AnimalController');
  Route::resource('producao', 'Painel\ProductionController');
  Route::resource('aplicacoes', 'Painel\AnimalMedicineController');
  Route::resource('entrega', 'Painel\DeliveryController');
  Route::resource('reproducao', 'Painel\ReproductionController');
  Route::resource('desafio', 'Painel\ChallengeController');
  Route::resource('estoque', 'Painel\StockController');
  Route::resource('inseminacao', 'Painel\InseminationController');
  Route::resource('semem', 'Painel\SemenController');
  Route::resource('configuracao', 'Painel\SettingController');

  Route::resource('medicamento', 'Painel\MedicineController');
  Route::resource('cobertura', 'Painel\CoverageController');
  Route::get('cobertura/{status?}', 'Painel\CoverageController@show');


  Route::get('fechamento_desafio', 'Painel\ChallengeController@closeDay');
  Route::get('fechamento_desafio/{date?}', 'Painel\ChallengeController@show');
  Route::get('fechamento_desafio/pdf/{date}', 'Painel\ChallengeController@downloadPDF');

  Route::get('fechamento_dia', 'Painel\ReproductionController@closeDay');
  Route::get('fechamento_dia/{date?}', 'Painel\ReproductionController@show');
  Route::get('fechamento_dia/pdf/{date}', 'Painel\ReproductionController@downloadPDF');


  Route::get('fechamento_animais', 'Painel\AnimalController@downloadPDF');



  Route::resource('permissions', 'Painel\PermissionController');
  Route::get('permission/{id}/roles', 'Painel\PermissionController@roles');

  Route::resource('roles', 'Painel\RoleController');
  Route::get('role/{id}/permissions', 'Painel\RoleController@permissions');

  Route::resource('users', 'Painel\UserController');
  Route::get('user/{id}/roles', 'Painel\UserController@roles');

  Route::get('changeDiagnostic', 'Painel\CoverageController@changeDiagnostic');

Route::get('changeStatus', 'Painel\UserController@changeStatus');

Route::get('desafio_get/{id}', 'Painel\ChallengeController@getProducao'); // busca a producao do animal para o novo desafio
Route::get('stocks_get/{id}', 'Painel\ChallengeController@getStock'); // busca o resultado do insumo
Route::get('cobertura_get/{id}', 'Painel\CoverageController@getLastDelivery'); // busca da data do ultimo parto se existir
Route::get('cobertura_apto_get/{id}', 'Painel\CoverageController@getLastCoverage'); // busca se o animal está apto a receber o procedimento

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
});
