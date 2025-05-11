<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\House;

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
    public function store(StorePaymentRequest $request, House $house)
    {
        $data = $request->validated();
        $payment = $house->payments()->create([
            'resident_id'  => $data['resident_id'],
            'amount'       => $data['amount'],
            'payment_date' => $data['payment_date'],
            'status'       => $data['status'],
        ]);

        return new PaymentResource($payment);
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

    public function listPayments(Request $request, House $house)
    {
        $perPage = $request->query('per_page', 15);
        $page    = $request->query('page', 1);

        $paginator = $house
            ->payments()
            ->with('resident')
            ->orderByDesc('payment_date')
            ->paginate($perPage, ['*'], 'page', $page);

        return PaymentResource::collection($paginator);
    }
}
