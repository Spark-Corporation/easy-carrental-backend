<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreCarRequest extends FormRequest
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
            'mark'=>'string|required|max:255',
            'type'=>'string|required|max:255',
            'model'=>'string|required|max:255',
            'color'=>'string|required|max:255',
            'photo'=>'string|required|max:255',
            'imatriculation' => 'string|required|max:255|unique:cars,imatriculation',
            'description'=>'string|max:255',
            'status'=>'string|required|max:255|in:disponible,loué,indisponible,en maintenance,en panne',
            'kmAmount'=>'decimal:10,2|required|min:0',
            'dayAmount'=>'decimal:10,2|required|min:0',
            'state'=>'string|required|max:255',
            'place'=>'numeric|required|min:0',
            'door'=>'numeric|required|min:0',
            'kilometrage'=>'decimal:10,2|required|min:0',
            'niveauCarburant'=>'decimal:10,2|required|min:0',
            'domage'=>'string|max:255',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'mark.required'            => 'La marque est obligatoire.',
            'mark.string'              => 'La marque doit être une chaîne de caractères.',

            'type.required'            => 'Le type est obligatoire.',
            'type.string'              => 'Le type doit être une chaîne de caractères.',

            'model.required'           => 'Le modèle est obligatoire.',
            'model.string'             => 'Le modèle doit être une chaîne de caractères.',

            'color.required'           => 'La couleur est obligatoire.',
            'color.string'             => 'La couleur doit être une chaîne de caractères.',

            'photo.required'           => 'La photo est obligatoire.',
            'photo.string'             => 'La photo doit être une chaîne de caractères.',

            'imatriculation.required'  => 'L’immatriculation est obligatoire.',
            'imatriculation.string'    => 'L’immatriculation doit être une chaîne de caractères.',
            'imatriculation.unique'    => 'Cette immatriculation existe déjà.',

            'description.string'       => 'La description doit être une chaîne de caractères.',

            'kmAmount.required'         => 'Le prix par kilomètre est obligatoire.',
            'kmAmount.decimal'          => 'Le prix par kilomètre doit être un nombre décimal.',

            'dayAmount.required'         => 'Le prix par jour est obligatoire.',
            'dayAmount.decimal'          => 'Le prix par jour doit être un nombre décimal.',

            'state.required'           => 'L’état du véhicule est obligatoire.',
            'state.string'             => 'L’état doit être une chaîne de caractères.',

            'place.required'           => 'Le nombre de places est obligatoire.',
            'place.numeric'            => 'Le nombre de places doit être un nombre.',

            'door.required'            => 'Le nombre de portes est obligatoire.',
            'door.numeric'             => 'Le nombre de portes doit être un nombre.',

            'kilometrage.required'     => 'Le kilométrage est obligatoire.',
            'kilometrage.decimal'      => 'Le kilométrage doit être un nombre décimal.',

            'niveauCarburant.required' => 'Le niveau de carburant est obligatoire.',
            'niveauCarburant.decimal'  => 'Le niveau de carburant doit être un nombre décimal.',

            'domage.string'            => 'Le champ dommage doit être une chaîne de caractères.',

            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists'   => 'La catégorie sélectionnée n\'existe pas.',

            'status.required'    => 'Le statut est obligatoire.',
            'status.string'      => 'Le statut doit être une chaîne de caractères.',
        ];
    }

}
