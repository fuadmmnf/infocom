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
    //help topics
    Route::post('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'create']);

    //departments
    Route::get('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'index']);
    Route::post('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'create']);

    //slaplans
    Route::get('slaplans', [\App\Http\Controllers\Api\SlaPlanController::class, 'index']);
    Route::post('slaplans', [\App\Http\Controllers\Api\SlaPlanController::class, 'create']);

    //users
    Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);

    //callcenteragents
    Route::post('callcenteragents', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'create']);

    //supportagents
    Route::post('supportagents', [\App\Http\Controllers\Api\SupportAgentController::class, 'create']);

    //customers
    Route::post('customers', [\App\Http\Controllers\Api\CustomerController::class, 'create']);

    //complains
    Route::get('complains', [\App\Http\Controllers\Api\ComplainController::class, 'index']);
    Route::post('complains', [\App\Http\Controllers\Api\ComplainController::class, 'create']);
    Route::put('complains/{complain_id}', [\App\Http\Controllers\Api\ComplainController::class, 'update']);
    Route::delete('complains/{complain_id}', [\App\Http\Controllers\Api\ComplainController::class, 'destroy']);
});
