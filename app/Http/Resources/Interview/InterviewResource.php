<?php

namespace App\Http\Resources\Interview;

use App\Http\Resources\Project_Process\ProjectResource;
use App\Http\Resources\QAT\QuestionCollection;
use App\Http\Resources\User\CandidateResource;
use App\Http\Resources\User\UserResource;
use App\Models\GroupQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $groupQuestionTest = $this->gqTest ?? new GroupQuestion();
        if ($this->time) {
            $status = 'Scheduled';
            if ($groupQuestionTest->questions && $groupQuestionTest->questions->count() > 0) {
                $status = 'Have test';
                if ($this->result && isset($this->result['candidate'])) {
                    $status = 'Done test';
                }
            }
            if (($this->result && isset($this->result['company'])) || $this->record) {
                $status = 'Completed';
            }
        } else if (date('Y-m-d H:i:s', strtotime($this->created_at)) !== date('Y-m-d H:i:s', strtotime($this->updated_at))) {
            $status = 'Created';
            if ($groupQuestionTest->questions && $groupQuestionTest->questions->count() > 0) {
                $status = 'Have test';
                if ($this->result && isset($this->result['candidate'])) {
                    $status = 'Done test';
                }
            }
        } else {
            $status = 'Created';
            if ($groupQuestionTest->questions && $groupQuestionTest->questions->count() > 0) {
                $status = 'Have test';
                if ($this->result && isset($this->result['candidate'])) {
                    $status = 'Done test';
                }
            }
        }
        
        if(!is_null($this->is_success)) {
            $status = $this->is_success ? 'Passed' : 'Failed';
        }

        return [
            'id' => $this->id,
            'record' => $this->record,
            'result' => $this->result,
            'address' => $this->address,
            'form' => $this->form,
            'room' => $this->room,
            'is_success' => $this->is_success,
            'time' => $this->time ? date('Y-m-d H:i:s', strtotime($this->time)) : null,
            'candidate' => new CandidateResource($this->candidate),
            'company' => $this->company ? [
                'id' => $this->company->id,
                'general' => new UserResource($this->company->user),
            ] : null,
            'news' => $this->news,
            'project' => $this->news ? new ProjectResource($this->news->project) : null,
            'process' => $this->process,
            'status' => $status,
            'group_question' => $this->gqTest,
            'interview_question' => $this->gqInterview ? [
                'id' => $this->gqInterview->id,
                'title' => $this->gqInterview->title,
            ] : null,
            'interview_question_data' => $this->gqInterview ? new QuestionCollection($this->gqInterview->questions) : null,
            'questions' => $groupQuestionTest->questions->count() ? new QuestionCollection($groupQuestionTest->questions) : null,
            'url' => '',
            'title' => $this->candidate->user->name . ' - ' . $this->id,
            'start' => $this->time ? date('Y-m-d H:i:s', strtotime($this->time)) : null,
            'end' => $this->time ? date('Y-m-d H:i', strtotime('+1 hour', strtotime($this->time))) : null,
            'allDay' => false,
            'calendar' => $status
        ];
    }
}
