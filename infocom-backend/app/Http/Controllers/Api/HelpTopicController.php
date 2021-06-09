<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HelpTopic\CreateHelpTopic;
use App\Models\HelpTopic;

class HelpTopicController extends Controller
{
    public function index()
    {
        $helptopics = HelpTopic::all();
        return response()->json($helptopics);
    }

    public function create(CreateHelpTopic $request)
    {
        $helptopic = HelpTopic::create($request->validated());
        return response()->json($helptopic, 201);
    }
}
