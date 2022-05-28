<?php

namespace App\Http\Resources\User;

use App\Http\Resources\CV\CVResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
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
           'general' => new UserResource($this->user),   
            'cv' => new CvResource($this->cv),
        ];
    }
}
