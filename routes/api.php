<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransfereController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function () {

    Route::get('transferencias', [TransfereController::class, 'index'])->name('transfere.index');

    Route::post('transfere', [TransfereController::class, 'transfere'])->name('transfere.');

    Route::name('carteira.')->group(function () {
        Route::resource('carteira', 'CarteiraController');
    });

    Route::name('users.')->group(function () {
        Route::resource('users', 'UserController');
    });
});
