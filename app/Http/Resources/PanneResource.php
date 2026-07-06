<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'panneAmount'=>$this->panneAmount,
            'created_at'=>$this->created_at->format('d/m/Y H:i'),
            'update_at'=>$this->update_at->format('d/m/Y H:i'),
        ];
    }
}
