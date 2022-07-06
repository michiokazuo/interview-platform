<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project_Process\StoreNewsRequest;
use App\Http\Resources\Project_Process\NewsCollection;
use App\Http\Resources\Project_Process\NewsResource;
use App\Http\Resources\User\UserResource;
use App\Services\RNews\NewsService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class RecruitmentNewsController extends Controller
{
    use ApiResponse, CurrentUser;

    private $newsService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 8);
        $data = request()->all();

        $user = $this->user();

        $news = $this->newsService->showAll($user, $data, $per_page);

        if ($news) {
            $news['data'] = !empty($news['data']) ? new NewsCollection($news['data']) : [];
            return $this->successfulResult('News display successfully!!!', $user, $news);
        }

        return $this->failedResult('News display failed!!!', 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @return JsonResponse
     */
    public function store(StoreNewsRequest $request): JsonResponse
    {
        $user = $this->user();
        $news = $this->newsService->store($user, $request->all());

        if ($news) {
            return $this->successfulResult('News created successfully!!!', $user, new NewsResource($news));
        }

        return $this->failedResult('News created failed!!!', 500);
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
        $news = $this->newsService->findById($user, $id);

        if ($news) {
            return $this->successfulResult('News display successfully!!!', $user, new NewsResource($news));
        }

        return $this->failedResult('News display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreNewsRequest $request, int $id): JsonResponse
    {
        $user = $this->user();
        $news = $this->newsService->update($user, $request->all(), $id);

        if ($news) {
            return $this->successfulResult('News updated successfully!!!', $user, new NewsResource($news));
        }

        return $this->failedResult('News updated failed!!!', 500);
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
        $news = $this->newsService->delete($user, $id);

        if ($news) {
            return $this->successfulResult('News deleted successfully!!!', $user, ['delete' => true]);
        }

        return $this->failedResult('News deleted failed!!!', 500);
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
        $data = request()->all();

        $user = $this->user();
        $news = $this->newsService->showAllByUser($user, $id, $data, $per_page);

        if ($news) {
            $news['data'] = !empty($news['data']) ? new NewsCollection($news['data']) : [];
            $news['user'] = new UserResource($news['user']);
            return $this->successfulResult('News display successfully!!!', $user, $news);
        }

        return $this->failedResult('News display failed!!!', 500);
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
        $news = $this->newsService->findById($user, $id);

        if ($news && $news->company_id === $user->company_id) {
            return $this->successfulResult('News display successfully!!!', $user, new NewsResource($news));
        }

        return $this->failedResult('News display failed!!!', 500);
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
        $news = $this->newsService->changeStatus($user, $id);

        if ($news) {
            return $this->successfulResult('Change news successfully!!!', $user, new NewsResource($news));
        }

        return $this->failedResult('Change news failed!!!', 500);
    }

    /**
     * Display a listing of user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showAllByProject(int $id): JsonResponse
    {
        $user = $this->user();
        $news = $this->newsService->showAllByProject($user, $id);

        if ($news) {
            return $this->successfulResult('News display successfully!!!', $user, new NewsCollection(($news)));
        }

        return $this->failedResult('News display failed!!!', 500);
    }
    
    /**
     * Display selectable options.
     *
     * @return JsonResponse
     */
    public function getSelectOptions(): JsonResponse
    {
        $user = $this->user();
        $options = $this->newsService->getSelectOptions($user);

        if ($options) {
            return $this->successfulResult('options display successfully!!!', $user, $options);
        }

        return $this->failedResult('options display failed!!!', 500);
    }
}
