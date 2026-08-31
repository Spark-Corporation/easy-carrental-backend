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
        $driverId = $this->route('driver') instanceof \App\Models\Driver
            ? $this->route('driver')->id
            : $this->route('driver');
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers')
                    ->where(fn($query) => $query->where('firstname', $this->input('firstname')))
                    ->ignore($driverId, 'id'),
            ],
            'phone' => 'required|string|max:20',
            'photo' => 'required|image|mimes:jpg,jpeg,png,avif,webp|max:2048',
            'status' => 'string|max:255|in:Disponible,Affecté,En congé,Inactif,Indisponible',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required'  => 'Le nom est obligatoire.',
            'lastname.unique'    => 'Un chauffeur avec ce prénom et ce nom existe déjà.',
            'phone.required'     => 'Le numéro de téléphone est obligatoire.',
            'status.string'      => 'Le statut doit être une chaîne de caractères.',
            'status.in'          => 'Le statut doit être l\'un des suivants : Disponible, Affecté, En congé, Inactif, Indisponible.',

            // Photo
            'photo.required'  => 'La photo est obligatoire.',
            'photo.image'     => 'Le fichier doit être une image.',
            'photo.mimes'     => 'La photo doit être au format jpg, jpeg ou png.',
            'photo.max'       => 'La photo ne doit pas dépasser 2 Mo.',

        ];
    }
}
