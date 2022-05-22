<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog_Comment\StoreBlogRequest;
use App\Http\Resources\Blog_Comment\BlogCollection;
use App\Http\Resources\Blog_Comment\BlogResource;
use App\Http\Resources\User\UserResource;
use App\Services\Blog\BlogService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    use ApiResponse, CurrentUser;

    private $blogService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 8);

        $user = $this->user();

        $blogs = $this->blogService->showAll($user, $per_page);

        if ($blogs) {
            $blogs['data'] = !empty($blogs['data']) ? new BlogCollection($blogs['data']) : [];
            return $this->successfulResult('Blogs display successfully!!!', $user, $blogs);
        }

        return $this->failedResult('Blogs display failed!!!', 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogRequest $request
     * @return JsonResponse
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $user = $this->user();
        $blog = $this->blogService->store($user, $request->all());

        if ($blog) {
            return $this->successfulResult('Blog created successfully!!!', $user, new BlogResource($blog));
        }

        return $this->failedResult('Blog created failed!!!', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->user();
        $blog = $this->blogService->findById($user, $id);

        if ($blog) {
            return $this->successfulResult('Blog display successfully!!!', $user, new BlogResource($blog));
        }

        return $this->failedResult('Blog display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreBlogRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreBlogRequest $request, int $id): JsonResponse
    {
        $user = $this->user();
        $blog = $this->blogService->update($user, $request->all(), $id);

        if ($blog) {
            return $this->successfulResult('Blog updated successfully!!!', $user, new BlogResource($blog));
        }

        return $this->failedResult('Blog updated failed!!!', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->user();
        $blog = $this->blogService->delete($user, $id);

        if ($blog) {
            return $this->successfulResult('Blog deleted successfully!!!', $user, ['delete' => true]);
        }

        return $this->failedResult('Blog deleted failed!!!', 500);
    }

    /**
     * Display a listing of user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showAllByUser(int $id): JsonResponse
    {
        $per_page = request('per_page', 8);

        $user = $this->user();
        $blogs = $this->blogService->showAllByUser($user, $id, $per_page);

        if ($blogs) {
            $blogs['data'] = !empty($blogs['data']) ? new BlogCollection($blogs['data']) : [];
            $blogs['user'] = new UserResource($blogs['user']);
            return $this->successfulResult('Blogs display successfully!!!', $user, $blogs);
        }

        return $this->failedResult('Blogs display failed!!!', 500);
    }

    /**
     * Display the specified resource to edit.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showToEdit(int $id): JsonResponse
    {
        $user = $this->user();
        $blog = $this->blogService->findById($user, $id);

        if ($blog && $blog->user_id === $user->id) {
            return $this->successfulResult('Blog display successfully!!!', $user, new BlogResource($blog));
        }

        return $this->failedResult('Blog display failed!!!', 500);
    }
}
