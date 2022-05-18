<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog_Comment\StoreCommentRequest;
use App\Http\Resources\Blog_Comment\CommentCollection;
use App\Http\Resources\Blog_Comment\CommentResource;
use App\Services\Comment\CommentService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse, CurrentUser;

    private $commentService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = $this->user();
        $comments = $this->commentService->showAllByUser($user);

        if ($comments) {
            return $this->successfulResultWithoutAuth('Blogs display successfully!!!'
                , new CommentCollection($comments));
        }

        return $this->failedResult('Blogs display failed!!!', 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCommentRequest $request
     * @return JsonResponse
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $user = $this->user();
        $comment = $this->commentService->store($user, $request->all());

        if ($comment) {
            return $this->successfulResultWithoutAuth('Comment created successfully!!!', new CommentResource($comment));
        }

        return $this->failedResult('Comment created failed!!!', 500);
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
        $comment = $this->commentService->findById($user, $id);

        if ($comment) {
            return $this->successfulResultWithoutAuth('Comment display successfully!!!', new CommentResource($comment));
        }

        return $this->failedResult('Comment display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->user();
        $comment = $this->commentService->update($user, $request->all(), $id);

        if ($comment) {
            return $this->successfulResultWithoutAuth('Comment updated successfully!!!', new CommentResource($comment));
        }

        return $this->failedResult('Comment updated failed!!!', 500);
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
        $comment = $this->commentService->delete($user, $id);

        if ($comment) {
            return $this->successfulResultWithoutAuth('Comment deleted successfully!!!', ['id' => $id]);
        }

        return $this->failedResult('Comment deleted failed!!!', 500);
    }
}
