<?php

namespace App\Http\Resources\Project_Process;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProcessCollection extends ResourceCollection
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
            $interviews = $data->interviews;
            $pass = 0;
            $fail = 0;
            foreach ($interviews as $interview) {
                if (!is_null($interview->is_success)) {
                    $interview->is_success ? $pass++ : $fail++;
                }
            }
            return [
                'id' => $data->id,
                'title' => $data->title,
                'description' => $data->description,
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'project' => $data->project,
                'user' => $data->project->company->user,
                'next_step' => $data->next_step,
                'prev_step' => $data->prev_step,
                'total' => $interviews ? $interviews->count() : 0,
                'passed' => $pass,
                'failed' => $fail,
            ];
        })->toArray();
    }
}
