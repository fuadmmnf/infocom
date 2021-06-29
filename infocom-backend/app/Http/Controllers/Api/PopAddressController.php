<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PopAddress\CreatePopAddress;
use App\Models\PopAddress;
use Illuminate\Http\Request;

class PopAddressController extends Controller {
    public function index() {
        $popAddresses = PopAddress::all();
        return response()->json($popAddresses);
    }

    public function create(CreatePopAddress $request) {
        $popaddress = PopAddress::create($request->validated());
        return response()->json($popaddress, 201);
    }

    public function update( $request){
        $popaddress = PopAddress::findOrFail($request->route('popaddress_id'));
        $popaddress->update($request->validated());
        return response()->noContent();
    }

    public function destroy(Request $request){
        $popaddress = PopAddress::findOrFail($request->route('popaddress_id'));
        $popaddress->delete();
        return response()->noContent();
    }
}
