<?php

namespace App\Http\Resources\User;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserTokenResource extends JsonResource
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
            "fullName" => $this->name,
            'avatar' => $this->avatar,
            "email" => $this->email,
            "username" => $this->name,
            "role" => $this->role->name,
            'company_id' => $this->company_id,
            'candidate_id' => $this->candidate_id,
        ];
    }
}
