<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Historic;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('reservation','reservation.car.category')->orderBy('created_at', 'desc')->paginate(15);
        return  InvoiceResource::collection($invoices);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = Invoice::create($request->validated());
        return new InvoiceResource($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $invoice = Invoice::with('reservation','reservation.car.category')->findOrFail($id);
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInvoiceRequest $request, int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->validated());

         //Historique
        Historic::create([
            'action'      => 'Mise à jour de facture',
            'description' => "Facture #{$invoice->invoiceNumber} mise à jour pour la réservation #{$invoice->reservationNumber}, montant {$invoice->totalAmount}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return new InvoiceResource($invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        //Historique
        Historic::create([
            'action'      => 'Suppression de facture',
            'description' => "Facture #{$invoice->invoiceNumber} supprimée pour la réservation #{$invoice->reservationNumber}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return response()->json(['message' => 'Facture supprimée avec succès'], 200);
    }
}
