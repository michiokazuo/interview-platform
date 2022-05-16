<?php

namespace App\Http\Resources\CV;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CVResource extends JsonResource
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
            'link' => $this->link,
            'candidate_id' => $this->candidate_id,
            'detail' => $this->cvDetail ?? []
        ];
    }
}
