<?php

namespace App\Http\Controllers;

use App\Models\ProjectBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $bid = ProjectBid::create([
            'project_id' => $projectId,
            'user_id' => Auth::id(),
            'bid_amount' => $request->bid_amount,
            'status' => 'pending',
        ]);

        return response()->json($bid, 201);
    }

    // Update an existing bid
    public function update(Request $request, $id)
    {
        $request->validate([
            'bid_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $bid = ProjectBid::findOrFail($id);
        $this->authorize('update', $bid); // Only allow the owner to update their bid

        $bid->update($request->only('bid_amount', 'status'));

        return response()->json($bid);
    }

    // Delete a bid
    public function destroy($id)
    {
        $bid = ProjectBid::findOrFail($id);
        $this->authorize('delete', $bid); // Only allow the owner to delete their bid

        $bid->delete();

        return response()->json(null, 204);
    }
}
