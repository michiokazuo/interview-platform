<?php

namespace App\Http\Resources\Blog_Comment;

use App\Models\InterviewBlog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class BlogCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = InterviewBlog::class;

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
                'title' => $data->title,
                'topics' => $data->topics,
                'content' => substr($data->content, 0, 50) . "...",
                'user' => $data->user,
                'comments' => $data->comments->count(),
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ];
        })->toArray();
    }
}
