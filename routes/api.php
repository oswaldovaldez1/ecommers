<?php

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});
 */

use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

function resource($uri, $controller, $router, $options)
{
    //$verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];
    $router->get($uri . '/busqueda/{search}', $controller . '@busqueda');
    switch ($options) {
        case 0: {
                $router->get($uri, $controller . '@index');
                $router->get($uri . '/{id}', $controller . '@show');
                $router->post($uri, $controller . '@store');
                $router->put($uri . '/{id}', $controller . '@update');
                $router->patch($uri . '/{id}', $controller . '@update');
                $router->delete($uri . '/{id}', $controller . '@destroy');
                break;
            }
        case 1: {
                $router->get($uri, $controller . '@index');
                $router->get($uri . '/{id}', $controller . '@show');
                $router->group(['middleware' => 'auth:api'], function () use ($uri, $controller, $router) {
                    $router->post($uri, $controller . '@store');
                    $router->put($uri . '/{id}', $controller . '@update');
                    $router->patch($uri . '/{id}', $controller . '@update');
                    $router->delete($uri . '/{id}', $controller . '@destroy');
                });
                break;
            }
        case 2: {
                $router->group(['middleware' => 'auth:api'], function () use ($uri, $controller, $router) {
                    $router->get($uri, $controller . '@index');
                    $router->get($uri . '/{id}', $controller . '@show');
                    $router->post($uri, $controller . '@store');
                    $router->put($uri . '/{id}', $controller . '@update');
                    $router->patch($uri . '/{id}', $controller . '@update');
                    $router->delete($uri . '/{id}', $controller . '@destroy');
                });
                break;
            }
        case 3: {
                $router->post($uri, $controller . '@store');
                $router->group(['middleware' => 'auth:api'], function () use ($uri, $controller, $router) {
                    $router->get($uri, $controller . '@index');
                    $router->get($uri . '/{id}', $controller . '@show');
                    $router->put($uri . '/{id}', $controller . '@update');
                    $router->patch($uri . '/{id}', $controller . '@update');
                    $router->delete($uri . '/{id}', $controller . '@destroy');
                });
                break;
            }
    }
}

//Route::group(['middleware' => ['cors']], function () {
Route::post('postproduct', 'ProductController@postProduct')->middleware(['auth:api']);
Route::get('get/{busqueda}', 'webController@getarticulos');
Route::get('getvalores', 'webController@getvalores');
Route::get('getdetalle/{busqueda}', 'webController@getarticulo');
Route::get('getdetalle1/{busqueda}', 'webController@getarticulo1');
Route::get('getbusqueda/{busqueda}', 'webController@getBusqueda');
Route::get('getdata', 'webController@getData');
Route::get('getslider', 'webController@getslider');
Route::get('category/all', 'CategoryController@allCategory');
Route::get('category/menu', 'CategoryController@getMenuCategories');

Route::get('dashboard', 'webController@dashboard')->middleware(['auth:api']);

Route::get('marca/all', 'MarcasController@allMarcas');
Route::get('provedor/all', 'ProvedorController@allProvedor');

Route::get('search/{busqueda}', 'ProductController@search');

Route::get('gethead/{busqueda}', 'webController@getHead');

Route::get('/orden/detalle/{id}', 'OrderController@detalle');

Route::get('getcategory/{category}/{index}', 'webController@getByCategory');
Route::get('searchproduct/{busqueda}/{index}', 'webController@searchproduct');

Route::post('product/upimg', 'ProductController@upImg');
Route::get('product/getimg/{id}', 'ProductController@getImgs');
Route::delete('product/removeImg/{id}/{idpr}', 'ProductController@removeImg');

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return response()->json(['message' => 'Ok'], 200);
});

//});

resource('rol', 'RolController', $router, 2);
resource('user', 'UserController', $router, 2);
resource('product', 'ProductController', $router, 0);
resource('comentario', 'ComentariosController', $router, 0);
resource('config', 'ConfiguracionController', $router, 0);
resource('order', 'OrderController', $router, 0); //3
resource('cart', 'CartController', $router, 0);
resource('category', 'CategoryController', $router, 2); //2
resource('marca', 'MarcasController', $router, 2); //2
resource('provedor', 'ProvedorController', $router, 2); //2



Route::put('auth/update/{email}', 'AuthController@updatePassword');
Route::get('/auth/restore/{token}', 'AuthController@getRestore');
Route::put('/auth/restore/{email}', 'AuthController@setRestore');

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::post('/charge', 'OpenPayController@store');
Route::get('/tienda', 'OpenPayController@tienda');
Route::get('/banco', 'OpenPayController@banco');

Route::get('/reporte/{status}/{inicio}/{fin}', 'ReportesController@pdf');

//valida los pagos pendientes
Route::get('/validar', 'OpenPayController@validar');
Route::get('sitemap', 'webController@sitemap');
