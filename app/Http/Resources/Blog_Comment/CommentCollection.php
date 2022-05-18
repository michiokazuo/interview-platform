<?php

namespace App\Http\Resources\Blog_Comment;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class CommentCollection extends ResourceCollection
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
            return [
                'id' => $data->id,
                'blog' => $data->blog,
                'user' => $data->user,
                'content' => $data->content,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ];
        })->toArray();
    }
}
