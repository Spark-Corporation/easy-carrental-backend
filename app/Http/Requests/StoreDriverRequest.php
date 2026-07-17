<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'firstname' => 'required|string|max:255',
            'lastname' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers')->where(fn ($query) =>
                    $query->where('firstname', $this->firstname)
                ),
            ],
            'phone' => 'required|string|max:20',
            'photo' => 'nullable|string',
            'status' => 'string|max:255|in:disponible,affecté,en congé,inactif,indisponible',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required'  => 'Le nom est obligatoire.',
            'lastname.unique'    => 'Un chauffeur avec ce prénom et ce nom existe déjà.',
            'phone.required'     => 'Le numéro de téléphone est obligatoire.',
            'photo.string'       => 'La photo doit être une chaîne de caractères (chemin du fichier).',
            'status.string'      => 'Le statut doit être une chaîne de caractères.',
            'status.in'          => 'Le statut doit être l\'un des suivants : disponible, affecté, en congé, inactif, indisponible.',
        ];
    }

}
