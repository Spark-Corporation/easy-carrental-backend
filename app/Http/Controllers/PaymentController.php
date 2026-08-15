<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with('invoice')->orderBy('created_at', 'desc')->paginate(15);
        return PaymentResource::collection($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = Payment::create($request->validated());
        // Récupérer la facture liée
        $invoice = $payment->invoice;

        if ($invoice) {
            $invoice->status = 'Payé';
            $invoice->save();
        }
        return new PaymentResource($payment);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $payment = Payment::with('invoice','invoice.reservation.car.category')->findOrFail($id);
        return new PaymentResource($payment);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StorePaymentRequest $request, int $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->validated());
        return new PaymentResource($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Paiement supprimé avec succès'], 200);
    }
}
