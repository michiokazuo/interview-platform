<?php

namespace App\Http\Resources\Project_Process;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'project' => $this->project,
            'company' => $this->company,
            'user' => $this->company->user,
            'job_position' => $this->job_position,
            'benefits' => $this->benefits,
            'requirements' => $this->requirements,
            'salary' => $this->salary,
            'working_form' => $this->working_form,
            'gender' => $this->gender,
            'experience' => $this->experience,
            'workplace' => $this->workplace,
            'number_of_recruits' => $this->number_of_recruits,
        ];
    }
}
