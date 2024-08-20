<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException; // Import for handling authorization exceptions


class ProjectController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function show($id)
    {
        return Project::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'total_cost' => 'required|numeric',
        ]);

        $project = Project::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'total_cost' => $request->total_cost,
        ]);

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'total_cost' => 'sometimes|required|numeric',
        ]);

        $project->update($request->all());

        return response()->json($project, 200);
    }

    public function destroy($id)
    {
        $project = Project::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $project->delete();

        return response()->json(null, 204);
    }
}