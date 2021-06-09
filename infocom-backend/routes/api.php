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

//helptopics
Route::get('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'index']);

Route::middleware(['auth:api'])->group(function(){
    //helptopics
    Route::post('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'create']);

    //departments
    Route::get('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'index']);
    Route::post('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'create']);

    //slaplans
    Route::get('slaplans', []);
    Route::post('slaplans', []);
});
