<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reservation_id' => 'required|exists:reservations,id|unique:invoices,reservation_id',
            //'invoiceNumber' => 'string|unique:invoices,invoiceNumber',
            'driverAmount' => 'numeric|min:0',
            'reductionAmount' => 'nullable|numeric|min:0',
            'tvaAmount' => 'nullable|numeric|min:0',
            'amount' => 'numeric|min:0',
            'totalAmount' => 'numeric|min:0',
            'status' => 'required|in:payé,en attente,non payé,annulé,partiellement payé',
        ];
    }
    public function messages(): array
    {
        return [
            'reservation_id.required' => 'La réservation est obligatoire.',
            'reservation_id.exists' => 'La réservation sélectionnée n\'existe pas.',
            'reservation_id.unique' => 'Une facture existe déjà pour cette réservation.',
            //'invoiceNumber.required' => 'Le numéro de facture est obligatoire.',
            'invoiceNumber.string' => 'Le numéro de facture doit être une chaîne de caractères.',
            'invoiceNumber.unique' => 'Le numéro de facture doit être unique.',
            'driverAmount.numeric' => 'Le montant du chauffeur doit être un nombre.',
            'driverAmount.min' => 'Le montant du chauffeur doit être supérieur ou égal à 0.',
            'reductionAmount.numeric' => 'Le montant de la réduction doit être un nombre.',
            'reductionAmount.min' => 'Le montant de la réduction doit être supérieur ou égal à 0.',
            'tvaAmount.numeric' => 'Le montant de la TVA doit être un nombre.',
            'tvaAmount.min' => 'Le montant de la TVA doit être supérieur ou égal à 0.',
            //'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur ou égal à 0.',
            //'totalAmount.required' => 'Le montant total est obligatoire.',
            'totalAmount.numeric' => 'Le montant total doit être un nombre.',
            'totalAmount.min' => 'Le montant total doit être supérieur ou égal à 0.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être l\'un des suivants : payé, en attente, non payé, annulé, partiellement payé.',
        ];
    }
}
