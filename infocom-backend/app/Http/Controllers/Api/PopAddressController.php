<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PopAddress\CreatePopAddress;
use App\Models\PopAddress;

class PopAddressController extends Controller {
    public function index() {
        $popAddresses = PopAddress::all();
        return response()->json($popAddresses);
    }

    public function create(CreatePopAddress $request) {
        $popaddress = PopAddress::create($request->validated());
        return response()->json($popaddress, 201);
    }
}