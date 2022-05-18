<?php

namespace App\Http\Resources\Blog_Comment;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $comments = $this->comments->sortBy([['created_at', 'desc']]) ?? [];
        $rsCmt = [];
        foreach ($comments as $comment) {
            $rsCmt[] = [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => $comment->user,
                'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            ];
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'topics' => $this->topics,
            'content' => $this->content,
            'user' => $this->user,
            'comments' => $rsCmt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
