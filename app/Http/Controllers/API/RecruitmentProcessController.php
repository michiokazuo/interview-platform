<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project_Process\StoreProcessRequest;
use App\Http\Resources\Project_Process\ProcessCollection;
use App\Http\Resources\Project_Process\ProcessResource;
use App\Services\RProcess\ProcessService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class RecruitmentProcessController extends Controller
{
    use ApiResponse, CurrentUser;

    private $processService;

    /**
     * Create a$user, new instance.
     *
     * @return void
     */
    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $project_id = request('project_id');

        $user = $this->user();

        $processList = $this->processService->showAll($user, $project_id);

        if ($processList) {
            return $this->successfulResult('Process list display successfully!!!', $user,
                new ProcessCollection($processList));
        }

        return $this->failedResult('Process list display failed!!!', 500);
    }

    /**
     * Store a$user, newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @return JsonResponse
     */
    public function store(StoreProcessRequest $request): JsonResponse
    {
        $user = $this->user();
        $process = $this->processService->store($user, $request->all());

        if ($process) {
            return $this->successfulResult('Process created successfully!!!', $user,
                new ProcessResource($process));
        }

        return $this->failedResult('Process created failed!!!', 500);
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
        $process = $this->processService->findById($user, $id);

        if ($process) {
            return $this->successfulResult('Blog display successfully!!!', $user,
                new ProcessResource($process));
        }

        return $this->failedResult('Blog display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreProcessRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreProcessRequest $request, int $id): JsonResponse
    {
        $user = $this->user();
        $process = $this->processService->update($user, $request->all(), $id);

        if ($process) {
            return $this->successfulResult('Process updated successfully!!!', $user,
                new ProcessResource($process));
        }

        return $this->failedResult('Process updated failed!!!', 500);
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
        $process = $this->processService->delete($user, $id);

        if ($process) {
            return $this->successfulResult('Process deleted successfully!!!', $user, ['delete' => true]);
        }

        return $this->failedResult('Process deleted failed!!!', 500);
    }


    /**
     * Change status or end date of process.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function changeStatus(int $id): JsonResponse
    {
        $user = $this->user();
        $process = $this->processService->changeStatus($user, $id);

        if ($process) {
            return $this->successfulResult('Change process successfully!!!', $user,
                new ProcessResource($process));
        }

        return $this->failedResult('Change process failed!!!', 500);
    }
}
