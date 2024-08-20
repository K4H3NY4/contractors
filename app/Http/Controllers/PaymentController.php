<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $userProjects = Auth::user()->projects()->pluck('id');
        return Payment::whereIn('project_id', $userProjects)->get();
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        $this->authorize('view', $payment);
        return $payment;
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $this->authorize('update', $payment);

        $request->validate([
            'amount' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
        ]);

        $payment->update($request->all());
        return response()->json($payment, 200);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $this->authorize('delete', $payment);
        $payment->delete();
        return response()->json(null, 204);
    }
}
