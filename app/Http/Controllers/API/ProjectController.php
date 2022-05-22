<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project_Process\StoreProjectRequest;
use App\Http\Resources\Project_Process\ProjectCollection;
use App\Http\Resources\Project_Process\ProjectResource;
use App\Services\Project\ProjectService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    use ApiResponse, CurrentUser;

    private $projectService;

    /**
     * Create a $user, new instance.
     *
     * @return void
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
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
        $projects = $this->projectService->showAll($user, $per_page);

        if ($projects) {
            $projects['data'] = !empty($projects['data']) ? new ProjectCollection($projects['data']) : [];
            return $this->successfulResult('Projects display successfully!!!', $user, $projects);
        }

        return $this->failedResult('Projects display failed!!!', 500);
    }

    /**
     * Store a $user, newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @return JsonResponse
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $user = $this->user();
        $project = $this->projectService->store($user, $request->all());

        if ($project) {
            return $this->successfulResult('Project created successfully!!!', $user,
                new ProjectResource($project));
        }

        return $this->failedResult('Project created failed!!!', 500);
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
        $project = $this->projectService->findById($user, $id);

        if ($project) {
            return $this->successfulResult('Project display successfully!!!', $user,
                new ProjectResource($project));
        }

        return $this->failedResult('Project display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreProjectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreProjectRequest $request, int $id): JsonResponse
    {
        $user = $this->user();
        $project = $this->projectService->update($user, $request->all(), $id);

        if ($project) {
            return $this->successfulResult('Project updated successfully!!!', $user,
                new ProjectResource($project));
        }

        return $this->failedResult('Project updated failed!!!', 500);
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
        $project = $this->projectService->delete($user, $id);

        if ($project) {
            return $this->successfulResult('Project deleted successfully!!!', $user, ['delete' => true]);
        }

        return $this->failedResult('Project deleted failed!!!', 500);
    }

    /**
     * Change status or end date of project.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function changeStatus(int $id): JsonResponse
    {
        $user = $this->user();
        $project = $this->projectService->changeStatus($user, $id);

        if ($project) {
            return $this->successfulResult('Change project successfully!!!', $user,
                new ProjectResource($project));
        }

        return $this->failedResult('Change project failed!!!', 500);
    }
}
