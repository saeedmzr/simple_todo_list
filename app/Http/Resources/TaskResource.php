<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 *     schema="TaskSchema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Task ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Task title"
 *     ),
 *      @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Task description"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Task status"
 *     ),
 *     @OA\Property(
 *         property="deadline",
 *         type="datetime",
 *         description="Task deadline datetime"
 *     ),
 *     @OA\Property(
 *         property="completed_at",
 *         type="datetime",
 *         description="Task completed_at datetime"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="datetime",
 *         description="Task updated_at datetime"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="datetime",
 *         description="Task created_at datetime"
 *     ),
 * )
 */


class TaskResource extends JsonResource
{



    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "deadline" => $this->deadline,
            "completed_at" => $this->completed_at,
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
        ];
    }
}
