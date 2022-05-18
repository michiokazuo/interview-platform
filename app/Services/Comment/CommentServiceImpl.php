<?php

namespace App\Services\Comment;

use App\Models\BlogComment;
use App\Models\User;
use App\Services\Blog\BlogService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CommentServiceImpl implements CommentService
{
    protected $_repository, $blogService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(BlogService $blogService)
    {
        $this->_repository = app()->make(BlogComment::class);
        $this->blogService = $blogService;
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, array $data)
    {
        try {
            $user_id = $user->id;
            $blog = null;

            if ($data['blog_id']) {
                $blog = $this->blogService->findById($user, $data['blog_id']);
            }

            if ($blog) {
                $comment = $this->_repository->create([
                    'user_id' => $user_id,
                    'blog_id' => $blog->id,
                    'content' => $data['content']
                ]);

                if ($comment) {
                    return $comment;
                }
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function findById(User $user, int $cmt_id)
    {
        try {
            $comment = $this->_repository->find($cmt_id);

            if ($comment) {
                return $comment;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function showAllByUser(User $user)
    {
        try {
            $comments = $this->_repository->where('user_id', $user->id)->orderBy('created_at', 'desc')
                ->limit(6)->get();

            if ($comments) {
                return $comments;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(User $user, array $data, int $cmt_id)
    {
        try {
            $comment = $this->findById($user, $cmt_id);

            if ($comment && $comment->user_id == $user->id) {
                $comment->content = $data['content'];
                $comment->save();

                return $comment;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(User $user, int $cmt_id): bool
    {
        try {
            $comment = $this->findById($user, $cmt_id);

            if ($comment && $comment->user_id == $user->id) {
                $comment->delete();
                return true;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
