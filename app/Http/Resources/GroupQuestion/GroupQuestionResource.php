<?php

namespace App\Http\Resources\GroupQuestion;

use App\Http\Resources\Interview\InterviewResource;
use App\Http\Resources\QAT\QuestionCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'topics' => $this->topics,
            'type' => $this->is_interview ? 'Interview' : 'Candidate Test',
            'is_interview' => $this->is_interview,
            'questions' => new QuestionCollection($this->questions),
            'interview' => new InterviewResource($this->interview),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
