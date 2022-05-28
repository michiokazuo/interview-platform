<?php

namespace App\Http\Resources\Interview;

use App\Http\Resources\User\CandidateResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

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
        $status = null;
        if ($this->time) {
            $status = 'Scheduled';
            if ($this->result) {
                $status = 'Completed';
            }
        } else if (date('Y-m-d H:i:s', strtotime($this->created_at)) !== date('Y-m-d H:i:s', strtotime($this->updated_at))) {
            $status = 'Canceled schedule';
        } else {
            $status = 'Created';
        }
        return [
            'id' => $this->id,
            'record' => $this->record,
            'result' => $this->result,
            'address' => $this->address,
            'form' => $this->form,
            'time' => $this->time ? date('Y-m-d H:i:s', strtotime($this->time)) : null,
            'candidate' => new CandidateResource($this->candidate),
            'company' => [
                'id' => $this->company->id,
                'general' => new UserResource($this->company->user),
            ],
            'news' => $this->news,
            'status' => $status,
            'end' => $this->updated_at ? date('Y-m-d H:i:s', strtotime($this->updated_at)) : null,
        ];
    }
}
