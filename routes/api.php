<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
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


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('properties', [PropertyController::class, 'getProperties']);
// Search
Route::get('properties/search', [PropertyController::class, 'search']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('logout', [AuthController::class, 'logout']);

    // Password update
    Route::post('users/password/update', [AuthController::class, 'updateMyPassword']);

    // user details update
    Route::post('users/details/update', [AuthController::class, 'updateUser']);

    // Properties
    Route::get('properties/{property}', [PropertyController::class, 'getProperty']);
    Route::put('properties/{property}', [PropertyController::class, 'updateProperty']);
    Route::post('properties', [PropertyController::class, 'createProperty']);
    Route::delete('properties/{property}', [PropertyController::class, 'deleteProperty']);

    Route::post('properties/{property}/gallery', [GalleryController::class, 'uploadImageToGallery']);
});
