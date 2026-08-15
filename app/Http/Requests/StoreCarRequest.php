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
            'mark' => 'string|required|max:255',
            'model' => 'string|required|max:255',
            'color' => 'string|required|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png,avif,webp|max:2048',
            'imatriculation' => 'string|required|max:255|unique:cars,imatriculation',
            'description' => 'nullable|string|max:255',
            'status' => 'string|max:255|in:Disponible,Louée,Indisponible,En maintenance,En panne',
            'kmAmount' => 'numeric|required|min:0',
            'dayAmount' => 'numeric|required|min:0',
            'state' => 'string|required|max:255',
            'place' => 'numeric|required|min:0',
            'door' => 'numeric|required|min:0',
            'transmission' => 'required|in:Manuelle,Automatique',
            'kilometrage' => 'numeric|required|min:0',
            'niveauCarburant' => 'required|in:Plein,1/4,1/2,3/4,Vide',
            'domage' => 'string|max:255',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
{
    return [
        // Marque
        'mark.required'   => 'La marque est obligatoire.',
        'mark.string'     => 'La marque doit être une chaîne de caractères.',
        'mark.max'        => 'La marque ne doit pas dépasser 255 caractères.',

        // Modèle
        'model.required'  => 'Le modèle est obligatoire.',
        'model.string'    => 'Le modèle doit être une chaîne de caractères.',
        'model.max'       => 'Le modèle ne doit pas dépasser 255 caractères.',

        // Couleur
        'color.required'  => 'La couleur est obligatoire.',
        'color.string'    => 'La couleur doit être une chaîne de caractères.',
        'color.max'       => 'La couleur ne doit pas dépasser 255 caractères.',

        // Photo
        'photo.required'  => 'La photo est obligatoire.',
        'photo.image'     => 'Le fichier doit être une image.',
        'photo.mimes'     => 'La photo doit être au format jpg, jpeg ou png.',
        'photo.max'       => 'La photo ne doit pas dépasser 2 Mo.',

        // Immatriculation
        'imatriculation.required' => 'L’immatriculation est obligatoire.',
        'imatriculation.string'   => 'L’immatriculation doit être une chaîne de caractères.',
        'imatriculation.max'      => 'L’immatriculation ne doit pas dépasser 255 caractères.',
        'imatriculation.unique'   => 'Cette immatriculation existe déjà.',

        // Description
        'description.string' => 'La description doit être une chaîne de caractères.',
        'description.max'    => 'La description ne doit pas dépasser 255 caractères.',

        // Statut
        'status.string' => 'Le statut doit être une chaîne de caractères.',
        'status.in'     => 'Le statut doit être parmi : Disponible, Louée, Indisponible, En maintenance ou En panne.',

        // Prix par kilomètre
        'kmAmount.required' => 'Le prix par kilomètre est obligatoire.',
        'kmAmount.numeric'  => 'Le prix par kilomètre doit être un nombre.',
        'kmAmount.min'      => 'Le prix par kilomètre doit être supérieur ou égal à 0.',

        // Prix par jour
        'dayAmount.required' => 'Le prix par jour est obligatoire.',
        'dayAmount.numeric'  => 'Le prix par jour doit être un nombre.',
        'dayAmount.min'      => 'Le prix par jour doit être supérieur ou égal à 0.',

        // État
        'state.required' => 'L’état du véhicule est obligatoire.',
        'state.string'   => 'L’état doit être une chaîne de caractères.',
        'state.max'      => 'L’état ne doit pas dépasser 255 caractères.',

        // Places
        'place.required' => 'Le nombre de places est obligatoire.',
        'place.numeric'  => 'Le nombre de places doit être un nombre.',
        'place.min'      => 'Le nombre de places doit être supérieur ou égal à 0.',

        // Portes
        'door.required' => 'Le nombre de portes est obligatoire.',
        'door.numeric'  => 'Le nombre de portes doit être un nombre.',
        'door.min'      => 'Le nombre de portes doit être supérieur ou égal à 0.',

        // Transmission
        'transmission.required' => 'La transmission est obligatoire.',
        'transmission.in'       => 'La transmission doit être Manuelle ou Automatique.',

        // Kilométrage
        'kilometrage.required' => 'Le kilométrage est obligatoire.',
        'kilometrage.numeric'  => 'Le kilométrage doit être un nombre.',
        'kilometrage.min'      => 'Le kilométrage doit être supérieur ou égal à 0.',

        // Niveau de carburant
        'niveauCarburant.required' => 'Le niveau de carburant est obligatoire.',
        'niveauCarburant.in'       => 'Le niveau de carburant doit être parmi : Plein, 3/4, 1/2, 1/4 ou Vide.',

        // Dommages
        'domage.string' => 'Le champ dommage doit être une chaîne de caractères.',
        'domage.max'    => 'Le champ dommage ne doit pas dépasser 255 caractères.',

        // Catégorie
        'category_id.required' => 'La catégorie est obligatoire.',
        'category_id.exists'   => 'La catégorie sélectionnée n’existe pas.',
    ];
}

}
