<?php

namespace App\Http\Resources\Project_Process;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map(function ($data) {
            return [
                'id' => $data->id,
                'title' => $data->title,
                'description' => substr($data->description ?? '', 0, 50) . "...",
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'status' => $data->status,
                'company' => $data->company,
                'user' => $data->company->user,
            ];
        })->toArray();
    }
}
