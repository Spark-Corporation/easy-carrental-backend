<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreHistoricRequest extends FormRequest
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
            'activite'        => 'required|string|max:255',
            'dateConnexion'   => 'required|date',
            'heureDeconnexion'=> 'nullable|date|after_or_equal:dateConnexion',
            'user_id' => 'required|exists:users,id',
        ];
    }


    public function messages(): array
    {
        return [
            'activite.required'       => 'L\'activité est obligatoire.',
            'activite.string'         => 'L\'activité doit être une chaîne de caractères.',

            'dateConnexion.required'  => 'La date de connexion est obligatoire.',
            'dateConnexion.date'      => 'La date de connexion doit être une date valide.',

            'heureDeconnexion.date'   => 'L\'heure de déconnexion doit être une date valide.',
            'heureDeconnexion.after_or_equal' => 'L\'heure de déconnexion doit être postérieure ou égale à la date de connexion.',

            'user_id.exists'   => 'L\'utilisateur n\'existe pas.',
        ];
    }
}
