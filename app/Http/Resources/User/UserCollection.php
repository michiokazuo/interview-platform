<?php

namespace App\Http\Resources\User;

use App\Http\Resources\CV\CVResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
            $cv = null;
            $url = null;
            $owner = null;
            $status = 'Active';
            if ($data->role->name === 'ROLE_COMPANY') {
                $url = $data->company->url;
                $owner = $data->company;
                $status = $data->company->is_active ? 'Active' : 'Inactive';
            } else if ($data->role->name === 'ROLE_CANDIDATE') {
                $owner = $data->candidate;
                $cv = new CvResource($data->candidate->cv);
            }

            return [
                'general' => [
                    'id' => $data->id,
                    "name" => $data->name,
                    "email" => $data->email,
                    "username" => $data->name,
                    "role" => $data->role,
                    'address' => $data->address,
                    'phone' => $data->phone,
                    'avatar' => $data->avatar,
                    'introduction' => $data->introduction,
                    'social_network' => empty($data->social_network) ? null : $data->social_network,
                    'major' => $data->major,
                    'url' => $url,
                    'owner' => $owner,
                    'status' => $status,
                    'created_at' => date('Y-m-d H:i:s', strtotime($data->created_at)),
                ],
                'cv' => $cv,

            ];
        })->toArray();
    }
}
