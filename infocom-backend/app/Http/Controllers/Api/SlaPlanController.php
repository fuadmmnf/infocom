<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlaPlan\CreateSlaPlan;
use App\Models\SlaPlan;

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
}
