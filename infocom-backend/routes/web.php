<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'app');

Route::get('/mail', function () {
    return view('mail.complainapproval', ['complain' => \App\Models\Complain::inRandomOrder()->first()]);
});
Route::get('reports/pop', [\App\Http\Controllers\Api\ReportController::class, 'fetchPopLog']);

Route::get('/poplog', function (){
    $pdf = PDF::loadView(
        'report.generic',
//        ['start' => $this->start, 'end' => $this->end]
    );
    $fileName = 'PopLogReport' . '.pdf';
    return $pdf->stream($fileName);
});
