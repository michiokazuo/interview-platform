<?php

namespace App\Http\Resources\User;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $url = null;
        $owner = null;
        if ($this->role->name === 'ROLE_COMPANY') {
            $url = $this->company->url;
            $owner = $this->company;
        } else if ($this->role->name === 'ROLE_CANDIDATE') {
            $owner = $this->candidate;
        }

        return [
            'id' => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->name,
            "role" => $this->role,
            'address' => $this->address,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'introduction' => $this->introduction,
            'social_network' => empty($this->social_network) ? null : $this->social_network,
            'major' => $this->major,
            'url' => $url,
            'owner' => $owner,
        ];
    }
}
