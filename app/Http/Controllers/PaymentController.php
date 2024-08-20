<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::whereHas('project', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Payment::whereHas('project', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        return response()->json($payment);
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
        $payment = Payment::whereHas('project', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'amount' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
        ]);

        $payment->update($request->all());

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::whereHas('project', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $payment->delete();

        return response()->json(null, 204);
    }
}
