<?php

namespace App\Http\Controllers;

use App\Models\ProjectBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectBidController extends Controller
{
    // Get all bids for a specific project
    public function index($projectId)
    {
        $bids = ProjectBid::where('project_id', $projectId)->get();
        return response()->json($bids);
    }

    // Store a new bid for a project
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'bid_amount' => 'required|numeric',
        ]);

        // Ensure the project exists (optional)
        // You might want to validate the project exists before allowing bids
        // $project = Project::findOrFail($projectId);

        $bid = ProjectBid::create([
            'project_id' => $projectId,
            'user_id' => Auth::id(),
            'bid_amount' => $request->bid_amount,
            'status' => 'pending',
        ]);

        return response()->json($bid, 201); // Return a 201 Created status
    }

    // Update an existing bid
    public function update(Request $request, $id)
    {
        $request->validate([
            'bid_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $bid = ProjectBid::findOrFail($id);

        // Authorize the update operation
        $this->authorize('update', $bid);

        $bid->update($request->only('bid_amount', 'status'));

        return response()->json($bid);
    }

    // Delete a bid
    public function destroy($id)
    {
        $bid = ProjectBid::findOrFail($id);

        // Authorize the delete operation
        $this->authorize('delete', $bid);

        $bid->delete();

        return response()->json(null, 204); // Return a 204 No Content status
    }
}
