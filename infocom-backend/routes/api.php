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

//users
Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);

//helptopics
Route::get('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'index']);
Route::post('complains', [\App\Http\Controllers\Api\ComplainController::class, 'create']);

Route::middleware(['auth:api'])->group(function () {
    //help topics
    Route::post('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'create']);

    //departments
    Route::get('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'index']);
    Route::post('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'create']);
    Route::put('departments/{department_id}', [\App\Http\Controllers\Api\DepartmentController::class, 'update']);

    //slaplans
    Route::get('slaplans', [\App\Http\Controllers\Api\SlaPlanController::class, 'index']);
    Route::post('slaplans', [\App\Http\Controllers\Api\SlaPlanController::class, 'create']);

    //popaddresses
    Route::get('popaddresses', [\App\Http\Controllers\Api\PopAddressController::class, 'index']);
    Route::post('popaddresses', [\App\Http\Controllers\Api\PopAddressController::class, 'create']);


    //callcenteragents
    Route::get('callcenteragents', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'index']);
    Route::post('callcenteragents', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'create']);

    //supportagents
    Route::get('supportagents', [\App\Http\Controllers\Api\SupportAgentController::class, 'index']);
    Route::post('supportagents', [\App\Http\Controllers\Api\SupportAgentController::class, 'create']);

    //customers
    Route::get('customers', [\App\Http\Controllers\Api\CustomerController::class, 'index']);
    Route::get('customers/code', [\App\Http\Controllers\Api\CustomerController::class, 'getAllCustomerCode']);
    Route::get('customers/{customer_code}', [\App\Http\Controllers\Api\CustomerController::class, 'searchCustomer']);
    Route::post('customers', [\App\Http\Controllers\Api\CustomerController::class, 'create']);
    Route::put('customers/{customer_id}', [\App\Http\Controllers\Api\CustomerController::class, 'update']);

    //complains
    Route::get('complains', [\App\Http\Controllers\Api\ComplainController::class, 'index']);
    Route::get('complains/{complain_code}', [\App\Http\Controllers\Api\ComplainController::class, 'getComplainByCode']);
    Route::put('complains/{complain_id}', [\App\Http\Controllers\Api\ComplainController::class, 'update']);
    Route::put('complains/{complain_code}/feedback', [\App\Http\Controllers\Api\ComplainController::class, 'storeFeedback']);
    Route::delete('complains/{complain_id}', [\App\Http\Controllers\Api\ComplainController::class, 'destroy']);


    //reports
    Route::get('reports/departmentlog', [\App\Http\Controllers\Api\ReportController::class, 'fetchDepartmentActivityLog']);
    Route::get('reports/complainstatus', [\App\Http\Controllers\Api\ReportController::class, 'fetchComplainStatusLog']);
    Route::get('reports/topicwisepop', [\App\Http\Controllers\Api\ReportController::class, 'fetchTopicWisePopLog']);
    Route::get('reports/servicetime', [\App\Http\Controllers\Api\ReportController::class, 'fetchServiceTimeLog']);
    Route::get('reports/pop', [\App\Http\Controllers\Api\ReportController::class, 'fetchPopLog']);
});
