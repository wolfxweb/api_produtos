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

Route::post('/login', [\App\Http\Controllers\CadastroController::class,'login'])->name('user.login');
Route::apiResource('/cadastro','App\Http\Controllers\CadastroController');
Route::middleware('auth:api')->apiResource('/loja','App\Http\Controllers\LojaController');
Route::middleware('auth:api')->apiResource('/categoria','App\Http\Controllers\CategoriaController');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
