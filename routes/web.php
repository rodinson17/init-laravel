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

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    return view('contenido/contenido');
});

/* Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); */

Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store')->name('registrar-categoria');
Route::put('/categoria/actualizar', 'CategoriaController@update')->name('actualizar-categoria');
Route::put('/categoria/desactivar', 'CategoriaController@desactivar')->name('desactivar-categoria');
Route::put('/categoria/activar', 'CategoriaController@activar')->name('activer-categoria');
Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

Route::get('/articulo', 'ArticuloController@index');
Route::post('/articulo/registrar', 'ArticuloController@store')->name('registrar-articulo');
Route::put('/articulo/actualizar', 'ArticuloController@update')->name('actualizar-articulo');
Route::put('/articulo/desactivar', 'ArticuloController@desactivar')->name('desactivar-articulo');
Route::put('/articulo/activar', 'ArticuloController@activar')->name('activer-articulo');
