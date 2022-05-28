<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewRequest;
use App\Http\Resources\Interview\InterviewCollection;
use App\Http\Resources\Interview\InterviewResource;
use App\Services\Interview\InterviewService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class InterviewController extends Controller
{
    use ApiResponse, CurrentUser;

    private $interviewService;

    /**
     * Create a interview instance.
     *
     * @return void
     */
    public function __construct(InterviewService $interviewService)
    {
        $this->interviewService = $interviewService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showAllByNews(int $id): JsonResponse
    {
        $per_page = request('per_page', 8);

        $user = $this->user();
        $interviews = $this->interviewService->showAllByNews($user, $id, $per_page);

        if ($interviews) {
            $interviews['data'] = !empty($interviews['data']) ? new InterviewCollection($interviews['data']) : [];
            return $this->successfulResult('interviews display successfully!!!', $user, $interviews);
        }

        return $this->failedResult('interviews display failed!!!', 500);
    }

    /**
     * Store a new created resource in storage.
     *
     * @param StoreInterviewRequest $request
     * @return JsonResponse
     */
    public function store(StoreInterviewRequest $request): JsonResponse
    {
        $user = $this->user();
        $interview = $this->interviewService->store($user, $request->all());

        if ($interview) {
            return $this->successfulResult('interviews created successfully!!!', $user,
                new InterviewResource($interview));
        }

        return $this->failedResult('interviews created failed!!!', 500);
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
        $interview = $this->interviewService->findById($user, $id);

        if ($interview) {
            return $this->successfulResult('interviews display successfully!!!', $user,
                new InterviewResource($interview));
        }

        return $this->failedResult('interviews display failed!!!', 500);
    }

    /**
     * Display the specified news_id resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function findByNewsId(int $id): JsonResponse
    {
        $user = $this->user();
        $interview = $this->interviewService->findByNewsId($user, $id);

        if ($interview) {
            return $this->successfulResult('interviews display successfully!!!', $user,
                new InterviewResource($interview));
        }

        return $this->failedResult('interviews display failed!!!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreInterviewRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreInterviewRequest $request, int $id): JsonResponse
    {
        $user = $this->user();
        $interview = $this->interviewService->update($user, $request->all(), $id);

        if ($interview) {
            return $this->successfulResult('interviews updated successfully!!!', $user,
                new InterviewResource($interview));
        }

        return $this->failedResult('interviews updated failed!!!', 500);
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

        $interview = $this->interviewService->delete($user, $id);

        if ($interview) {
            return $this->successfulResult('interviews deleted successfully!!!', $user, ['delete' => true]);
        }

        return $this->failedResult('interviews deleted failed!!!', 500);
    }

    /**
     * Display a listing of user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showAllByUser(int $id): JsonResponse
    {
        $user = $this->user();
        $interview = $this->interviewService->showAllByUser($user, $id);

        if ($interview) {
            return $this->successfulResult('interviews display successfully!!!', $user,
                [
                    'applications' => new InterviewCollection($interview['applications']),
                    'practices' => new InterviewCollection($interview['practices']),
                ]);
        }

        return $this->failedResult('interviews display failed!!!', 500);
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
        $interview = $this->interviewService->findById($user, $id);

        if ($interview && ($interview->company_id === $user->company_id
                || $interview->candidate_id === $user->candidate_id)) {
            return $this->successfulResult('interviews display successfully!!!', $user,
                new InterviewResource($interview));
        }

        return $this->failedResult('interviews display failed!!!', 500);
    }
}
