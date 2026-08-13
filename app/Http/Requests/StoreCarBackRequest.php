<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reservation;

class StoreCarBackRequest extends FormRequest
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
            'reservation_id' => 'required|exists:reservations,id',
            'returnKm'       => 'required|numeric|min:0',
            'fluelLevel'     => 'required|in:Plein,1/4,1/2,3/4,Vide',
            'state'          => 'required|string|max:255',
            'damage'         => 'nullable|string|max:255',
            'comment'        => 'nullable|string|max:500',
            'returnDate'    => [
                'nullable',
                'date',
                'before_or_equal:now',
                function ($attribute, $value, $fail) {
                    if ($this->reservation_id && $value) {
                        $reservation = Reservation::find($this->reservation_id,['*']);

                        if ($reservation && $reservation->dateStart) {
                            $returnDate = \Carbon\Carbon::parse($value);
                            $startDate  = \Carbon\Carbon::parse($reservation->dateStart);

                            if ($returnDate->lt($startDate)) {
                                $fail("La date de retour doit être après ou égale à la date de début de la réservation ({$startDate->format('d/m/Y H:i')}).");
                            }
                        }
                    }
                },
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'reservation_id.required' => 'La réservation est obligatoire.',
            'reservation_id.exists'   => 'La réservation sélectionnée est invalide.',
            'returnKm.required'       => 'Le kilométrage de retour est obligatoire.',
            'returnKm.numeric'        => 'Le kilométrage doit être un nombre.',
            'fluelLevel.required'     => 'Le niveau de carburant est obligatoire.',
            'fluelLevel.in'           => 'Le niveau de carburant doit être parmi : Plein, 3/4, 1/2, 1/4 ou Vide.',
            'state.required'          => 'L’état du véhicule est obligatoire.',
            'damage.string'           => 'Les dommages doivent être une chaîne de caractères.',
            'comment.string'          => 'Le commentaire doit être une chaîne de caractères.',
        ];
    }
}
