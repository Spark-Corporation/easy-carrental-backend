<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'mark'             => $this->mark,
            'type'             => $this->type,
            'model'            => $this->model,
            'color'            => $this->color,
            'photo'            => $this->photo,
            'imatriculation'   => $this->imatriculation,
            'description'      => $this->description,
            'status'           => $this->status,
            'kmAmount'         => $this->kmAmount,
            'dayAmount'        => $this->dayAmount,
            'state'            => $this->state,
            'place'            => $this->place,
            'door'             => $this->door,
            'kilometrage'      => $this->kilometrage,
            'niveauCarburant'  => $this->niveauCarburant,
            'domage'           => $this->domage,

            'category' => new CategoryResource($this->whenLoaded('category')),

            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
