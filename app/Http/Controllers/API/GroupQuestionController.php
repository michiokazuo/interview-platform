<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupQuestion\StoreGroupQuestion;
use App\Http\Resources\GroupQuestion\GroupQuestionCollection;
use App\Http\Resources\GroupQuestion\GroupQuestionRescource;
use App\Http\Resources\GroupQuestion\GroupQuestionResource;
use App\Services\GroupQuestion\GroupQuestionService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class GroupQuestionController extends Controller
{
    use ApiResponse, CurrentUser;

    private $gqService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(GroupQuestionService $gqService)
    {
        $this->gqService = $gqService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = $this->user();
        $groups = $this->gqService->getAll($user);

        if ($groups) {
            return $this->successfulResult('GroupQuestion display successfully!!!', $user,
                new GroupQuestionCollection($groups));
        }

        return $this->failedResult('GroupQuestion display failed!!!', 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGroupQuestion $request
     * @return JsonResponse
     */
    public function store(StoreGroupQuestion $request): JsonResponse
    {
        $user = $this->user();
        $blog = $this->gqService->store($user, $request->all(), null);

        if ($blog) {
            return $this->successfulResult('GroupQuestion created successfully!!!', $user,
                new GroupQuestionResource($blog));
        }

        return $this->failedResult('GroupQuestion created failed!!!', 500);
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
        $group = $this->gqService->findById($user, $id);

        if ($group) {
            return $this->successfulResult('GroupQuestion display successfully!!!', $user,
                new GroupQuestionResource($group));
        }

        return $this->failedResult('GroupQuestion display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreGroupQuestion $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreGroupQuestion $request, int $id): JsonResponse
    {
        $user = $this->user();
        $blog = $this->gqService->store($user, $request->all(), $id);

        if ($blog) {
            return $this->successfulResult('GroupQuestion updated successfully!!!', $user,
                new GroupQuestionResource($blog));
        }

        return $this->failedResult('GroupQuestion updated failed!!!', 500);
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
        $blog = $this->gqService->delete($user, $id);

        if ($blog) {
            return $this->successfulResult('GroupQuestion deleted successfully!!!', $user, ['delete' => true]);
        }

        return $this->failedResult('GroupQuestion deleted failed!!!', 500);
    }
}
