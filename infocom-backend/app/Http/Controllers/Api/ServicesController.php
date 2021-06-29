<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CreateService;
use App\Models\Service;

class ServicesController extends Controller {
    public function index() {
        $services = Service::all();
        return response()->json($services);
    }

    public function create(CreateService $request) {
        $service = Service::create($request->validated());
        return response()->json($service, 201);
    }
}
