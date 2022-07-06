<?php

namespace App\Services\Blog;

use App\Models\InterviewBlog;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class BlogServiceImpl implements BlogService
{
    protected $_repository, $userRepository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(InterviewBlog::class);
        $this->userRepository = app()->make(User::class);
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
    public function findById(User $user, int $blog_id)
    {
        try {
            $blog = $this->_repository->find($blog_id);

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
    public function showAll(User $user, array $data, int $per_page = 8)
    {
        try {
            $blogs = $this->_repository;
            
            if (isset($data['sort_by']) && str_contains($data['sort_by'], '-')) {
                $sort = explode('-', $data['sort_by']);
                $blogs = $blogs->orderBy($sort[0], $sort[1]);
            } else {
                $blogs = $blogs->orderBy('created_at', 'desc');
            }

            if (isset($data['keyword']) && $data['keyword']) {
                $keyword = trim($data['keyword']);
                $blogs = $blogs->where(function ($query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('topics', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                });
            }
            
            $blogs = $blogs->paginate($per_page);
            
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

    /**
     * @inheritDoc
     */
    public function showAllByUser(User $user, int $user_id, array $data, int $per_page = 8)
    {
        try {
            $userOwner = $this->userRepository->find($user_id);
            $blogs = $this->_repository->where('user_id', $user_id);

            if (isset($data['sort_by']) && str_contains($data['sort_by'], '-')) {
                $sort = explode('-', $data['sort_by']);
                $blogs = $blogs->orderBy($sort[0], $sort[1]);
            } else {
                $blogs = $blogs->orderBy('created_at', 'desc');
            }
            
            $blogs = $blogs->paginate($per_page);

            if ($blogs && $userOwner) {
                return [
                    'current_page' => $blogs->currentPage(),
                    'data' => $blogs->items(),
                    'total' => $blogs->total(),
                    'last_page' => $blogs->lastPage(),
                    'user' => $userOwner,
                ];
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

    /**
     * @inheritDoc
     */
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
