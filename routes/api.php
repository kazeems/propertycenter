<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
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

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('properties', [PropertyController::class, 'getProperties']);
    Route::get('properties/{propertyId}', [PropertyController::class, 'getProperty']);
    Route::put('properties/{propertyId}', [PropertyController::class, 'updateProperty']);
    Route::post('properties', [PropertyController::class, 'createProperty']);
    Route::delete('properties/{propertyId}', [PropertyController::class, 'deleteProperty']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);