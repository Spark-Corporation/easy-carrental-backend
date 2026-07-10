<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'user_id'   => 'required|exists:users,id',
            'car_id'      => 'required|exists:cars,id',
            'driver_id'   => 'nullable|exists:drivers,id',
            'dateStart'  => 'required|date|after_or_equal:today',
            'dateBack'   => 'nullable|date|after_or_equal:dateStart',
            'dayAmount'      => 'nullable|numeric|min:0',
            'driverAmount'   => 'nullable|numeric|min:0',
            'type'         => 'required|in:reservation,leasing',
            'status'      => 'required|in:En attente,validé,annulée',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'   => 'L\'utilisateur n\'est pas spécifié.',
            'user_id.exists'     => 'L\'utilisateur sélectionné n\'existe pas.',
            'car_id.required'   => 'La voiture est obligatoire.',
            'car_id.exists'     => 'La voiture sélectionnée n\'existe pas.',
            'dateStart.required'  => 'La date de début est obligatoire.',
            'dateStart.date'      => 'La date de début doit être une date valide.',
            'dateStart.after_or_equal' => 'La date de début doit être aujourd\'hui ou une date future.',
            'dateBack.date'       => 'La date de retour doit être une date valide.',
            'dateBack.after'      => 'La date de retour doit être après la date de début.',
            'driver_id.exists'   => 'Le chauffeur sélectionné n\'existe pas.',
            'driverAmount.numeric' => 'Le montant du chauffeur doit être un nombre.',
            'driverAmount.min'     => 'Le montant du chauffeur doit être supérieur ou égal à 0.',
            'type.required'       => 'Le type de réservation est obligatoire.',
            'type.in'             => 'Le type de réservation doit être "reservation" ou "leasing".',
            'status.required'     => 'Le statut est obligatoire.',
            'status.in'           => 'Le statut doit être "En attente", "validé" ou "annulée".',
        ];
    }
}
