<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['resident', 'house'])
            ->orderByDesc('payment_date')
            ->paginate();

        return PaymentResource::collection($payments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        $payment = Payment::create($data);

        return response()->json([
            'message' => 'Payment recorded',
            'data'    => new PaymentResource($payment->load(['resident', 'house'])),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return new PaymentResource(
            $payment->load(['resident', 'house'])
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->validated();
        $ok   = $payment->update($data);

        if ($ok) {
            $payment->refresh();
            return response()->json([
                'message' => 'Payment updated',
                'data'    => new PaymentResource($payment->load(['resident', 'house'])),
            ], 200);
        }

        return response()->json([
            'message' => 'Update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted',
        ], 200);
    }
}
