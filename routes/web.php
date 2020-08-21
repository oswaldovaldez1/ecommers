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

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');
//Route::view('/{any}', 'app')->where('any', '.*');

/*Route::get('/', function () {
return view('welcome');
});*/

/*Route::get('/','Api\webController@getarticulos');*/
// Route::get('/', function () {
//     return view('admin.reporteindividual');
// });
// Route::get('/', 'Api\ReportesController@pdf');
// Route::get('/{id}', 'Api\ReportesController@getReporteunitario');