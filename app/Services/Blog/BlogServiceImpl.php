<?php

namespace App\Services\Blog;

use App\Models\InterviewBlog;
use App\Models\User;
use App\Services\Comment\CommentService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class BlogServiceImpl implements BlogService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(InterviewBlog::class);
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, array $data)
    {
        try {
            $blog = $this->_repository->create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'topics' => $data['topics'],
                'content' => $data['content'],
            ]);

            if ($blog) {
                return $blog;
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
    public function findById(User $user, $id)
    {
        try {
            $blog = $this->_repository->find($id);

            if ($blog) {
                return $blog;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    public function showAll(User $user, int $per_page = 8)
    {
        try {
            $blogs = $this->_repository->orderBy('created_at', 'desc')->paginate($per_page);
            
            if ($blogs) {
                return [
                    'current_page' => $blogs->currentPage(),
                    'data' => $blogs->items(),
                    'total' => $blogs->total(),
                    'last_page' => $blogs->lastPage(),
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    public function showAllByUser(User $user, int $per_page = 8)
    {
        try {
            $blogs = $this->_repository->where('user_id', $user->id)->orderBy('created_at', 'desc')
                ->paginate($per_page);

            if ($blogs) {
                return [
                    'current_page' => $blogs->currentPage(),
                    'data' => $blogs->items(),
                    'total' => $blogs->total(),
                    'last_page' => $blogs->lastPage(),
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    public function update(User $user, array $data, int $blog_id)
    {
        try {
            $blog = $this->_repository->find($blog_id);

            if ($blog && $blog->user_id == $user->id) {
                $blog->update([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'topics' => $data['topics'],
                ]);

                return $this->findById($user, $blog_id);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    public function delete(User $user, int $blog_id): bool
    {
        try {
            $blog = $this->_repository->find($blog_id);

            if ($blog && $blog->user_id == $user->id) {
                $blog->delete();

                return true;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
