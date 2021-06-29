<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlaPlan\CreateSlaPlan;
use App\Http\Requests\SlaPlan\UpdatePlanRequest;
use App\Models\SlaPlan;
use Illuminate\Http\Request;

class SlaPlanController extends Controller
{
    public function index()
    {
        $slaplans = SlaPlan::with('helptopic')->get();
        return response()->json($slaplans);
    }

    public function create(CreateSlaPlan $request)
    {
        $slaplan = SlaPlan::create($request->validated());
        return response()->json($slaplan, 201);
    }

    public function update(UpdatePlanRequest $request){
        $slaplan = SlaPlan::findOrFail($request->route('slaplan_id'));
        $slaplan->update($request->validated());
        return response()->noContent();
    }

    public function destroy(Request $request){
        $slaplan = SlaPlan::findOrFail($request->route('slaplan_id'));
        $slaplan->delete();
        return response()->noContent();
    }
}
