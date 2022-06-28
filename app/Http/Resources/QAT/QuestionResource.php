<?php

namespace App\Http\Resources\QAT;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'others' => $this->others,
            'tags' => $this->tags,
            'answers' => $this->answers,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
