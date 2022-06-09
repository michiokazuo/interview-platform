<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\QAT\QuestionCollection;
use App\Services\QuestionAnswerTag\QATService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QATController extends Controller
{
    use ApiResponse, CurrentUser;

    private $qatService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(QATService $qatService)
    {
        $this->qatService = $qatService;
    }

    /**
     * Display a tags of the resource.
     *
     * @return JsonResponse
     */
    public function showTags(): JsonResponse
    {
        $user = $this->user();
        $tags = $this->qatService->getAllTag($user);

        if ($tags) {
            return $this->successfulResult('Tags display successfully!!!', $user, $tags);
        }

        return $this->failedResult('Tags display failed!!!', 500);
    }

    /**
     * Display a tags of the resource.
     *
     * @return JsonResponse
     */
    public function showQuestions(Request $request): JsonResponse
    {
        $data = $request->all();

        $user = $this->user();
        $questions = $this->qatService->getQuestions($user, $data, $data['per_page']);

        if ($questions) {
            $questions['data'] = !empty($questions['data']) ? new QuestionCollection($questions['data']) : [];
            return $this->successfulResult('Question display successfully!!!', $user, $questions);
        }

        return $this->failedResult('Question display failed!!!', 500);
    }
}
