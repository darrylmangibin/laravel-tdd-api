<?php

namespace App\Http\Resources;

use App\Models\TodoList;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'status' => $this->status,
            'title' => $this->title,
            'todo_list' => $this->whenLoaded(TodoList::class)
        ];
    }
}
