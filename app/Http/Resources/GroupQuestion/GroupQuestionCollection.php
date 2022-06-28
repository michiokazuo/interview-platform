<?php

namespace App\Http\Resources\GroupQuestion;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class GroupQuestionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map(function ($data) {
            return [
                'id' => $data->id,
                'title' => $data->title,
                'topics' => $data->topics,
                'type' => $data->is_interview ? 'Interview' : 'Candidate Test',
                'is_interview' => $data->is_interview,
                'number_question' => $data->questions->count(),
                'interview' => $data->interview,
                'created_at' => date('Y-m-d H:i', strtotime($data->created_at)),
                'updated_at' => $data->updated_at,
            ];
        })->toArray();
    }
}
