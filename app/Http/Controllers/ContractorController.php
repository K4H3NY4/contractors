<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractorController extends Controller
{
    // List all contractors
    public function index()
    {
        $contractors = Contractor::all();
        return response()->json($contractors);
    }

    // Get a specific contractor
    public function show($id)
    {
        $contractor = Contractor::findOrFail($id);
        return response()->json($contractor);
    }

    // Create a new contractor
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty' => 'required|string|max:255',
            'availability' => 'required|string|max:255',
        ]);

        $contractor = Contractor::create($request->all());

        return response()->json($contractor, 201);
    }

    // Update an existing contractor
    public function update(Request $request, $id)
    {
        $request->validate([
            'specialty' => 'sometimes|string|max:255',
            'availability' => 'sometimes|string|max:255',
        ]);

        $contractor = Contractor::findOrFail($id);
        $contractor->update($request->only('specialty', 'availability'));

        return response()->json($contractor);
    }

    // Delete a contractor
    public function destroy($id)
    {
        $contractor = Contractor::findOrFail($id);
        $contractor->delete();

        return response()->json(null, 204);
    }
}
