<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    private mixed $id;
    private mixed $title;
    private mixed $description;
    private mixed $status;
    private mixed $deadline;
    private mixed $completed_at;
    private mixed $user;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user" => new UserResource($this->user),
            "title" => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "deadline" => $this->deadline,
            "completed_at" => $this->completed_at,
        ];
    }
}
