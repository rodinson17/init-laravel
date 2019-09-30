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
Route::post('/categoria/actualizar', 'CategoriaController@update')->name('actualizar-categoria');
Route::post('/categoria/desactivar', 'CategoriaController@desactivar')->name('desactivar-categoria');
Route::post('/categoria/activar', 'CategoriaController@activar')->name('activer-categoria');
