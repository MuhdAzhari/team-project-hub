<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'client_id'   => $this->client_id,
            'name'        => $this->name,
            'description' => $this->description,
            'status'      => $this->status,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'client'      => $this->whenLoaded('client', function () {
                return [
                    'id'   => $this->client->id,
                    'name' => $this->client->name,
                ];
            }),
            'tasks'       => TaskResource::collection(
                $this->whenLoaded('tasks')
            ),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
