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


Route::post('/Produtos', 'ProdutosController@create');

Route::post('/Produtos/edit', 'ProdutosController@edit');

Route::post('/Produtos/deleteProd', 'ProdutosController@delete');

Route::post('/Pedido/add', 'PedidosController@create');

Route::get('/admin_produtos','ProdutosController@gerenciaProdutos');

Route::get('/meusPedidos','PedidosController@index');

Route::get('datatable/getDadosProduto','ProdutosController@retrieveProdutos')->name('datatable/getDadosProduto');

Route::get('datatable/getDadosPedidos','PedidosController@retrievePedidos')->name('datatable/getDadosPedidos');

Auth::routes();

Route::get('/home', 'ProdutosController@index');
