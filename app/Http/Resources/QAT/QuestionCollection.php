<?php

namespace App\Http\Resources\QAT;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionCollection extends ResourceCollection
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
                'content' => $data->content,
                'others' => $data->others,
                'tags' => $data->tags,
                'answers' => $data->answers,
                'root_question' => new QuestionResource($data->rootQuestion),
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ];
        })->toArray();
    }
}
