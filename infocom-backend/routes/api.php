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
Route::put('login/forget', [\App\Http\Controllers\Api\UserController::class, 'forgetPassword']);

//helptopics
Route::get('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'index']);

//complains
Route::post('complains', [\App\Http\Controllers\Api\ComplainController::class, 'create']);



Route::middleware(['auth:api'])->group(function () {
    //users
    Route::put('users/password', [\App\Http\Controllers\Api\UserController::class, 'changePassword']);

    //help topics
    Route::post('helptopics', [\App\Http\Controllers\Api\HelpTopicController::class, 'create']);
    Route::put('helptopics/{helptopic_id}', [\App\Http\Controllers\Api\HelpTopicController::class, 'update']);
    Route::delete('helptopics/{helptopic_id}', [\App\Http\Controllers\Api\HelpTopicController::class, 'destroy'])->middleware('role:super_admin');

    //departments
    Route::get('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'index']);
    Route::post('departments', [\App\Http\Controllers\Api\DepartmentController::class, 'create']);
    Route::put('departments/{department_id}', [\App\Http\Controllers\Api\DepartmentController::class, 'update']);
    Route::delete('departments/{department_id}', [\App\Http\Controllers\Api\DepartmentController::class, 'destroy'])->middleware('role:super_admin');

    //slaplans
    Route::get('slaplans', [\App\Http\Controllers\Api\SlaPlanController::class, 'index']);
    Route::post('slaplans', [\App\Http\Controllers\Api\SlaPlanController::class, 'create']);
    Route::put('slaplans/{slaplan_id}', [\App\Http\Controllers\Api\SlaPlanController::class, 'update']);
    Route::delete('slaplans/{slaplan_id}', [\App\Http\Controllers\Api\SlaPlanController::class, 'destroy'])->middleware('role:super_admin');

    //popaddresses
    Route::get('popaddresses', [\App\Http\Controllers\Api\PopAddressController::class, 'index']);
    Route::post('popaddresses', [\App\Http\Controllers\Api\PopAddressController::class, 'create']);
    Route::put('popaddresses/{popaddress_id}', [\App\Http\Controllers\Api\PopAddressController::class, 'update']);
    Route::delete('popaddresses/{popaddress_id}', [\App\Http\Controllers\Api\PopAddressController::class, 'destroy'])->middleware('role:super_admin');

    //services
    Route::get('services', [\App\Http\Controllers\Api\ServicesController::class, 'index']);
    Route::post('services', [\App\Http\Controllers\Api\ServicesController::class, 'create']);
    Route::put('services/{service_id}', [\App\Http\Controllers\Api\ServicesController::class, 'update']);
    Route::delete('services/{service_id}', [\App\Http\Controllers\Api\ServicesController::class, 'destroy'])->middleware('role:super_admin');

    //callcenteragents
    Route::get('callcenteragents', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'index']);
    Route::post('callcenteragents', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'create']);
    Route::put('callcenteragents/{callcenteragent_id}', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'update']);
    Route::delete('callcenteragents/{callcenteragent_id}', [\App\Http\Controllers\Api\CallcenterAgentController::class, 'destroy'])->middleware('role:super_admin');

    //supportagents
    Route::get('supportagents', [\App\Http\Controllers\Api\SupportAgentController::class, 'index']);
    Route::post('supportagents', [\App\Http\Controllers\Api\SupportAgentController::class, 'create']);
    Route::put('supportagents/{supportagent_id}', [\App\Http\Controllers\Api\SupportAgentController::class, 'update']);
    Route::delete('supportagents/{supportagent_id}', [\App\Http\Controllers\Api\SupportAgentController::class, 'destroy'])->middleware('role:super_admin');

    //customers
    Route::get('customers', [\App\Http\Controllers\Api\CustomerController::class, 'index']);
    Route::get('customers/code', [\App\Http\Controllers\Api\CustomerController::class, 'getAllCustomerCode']);
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
