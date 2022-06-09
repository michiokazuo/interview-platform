<?php

namespace App\Services\QuestionAnswerTag;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTag;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;

class QATServiceImpl implements QATService
{
    protected $questionRepo, $answerRepo, $tagRepo;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->questionRepo = app()->make(Question::class);
        $this->answerRepo = app()->make(QuestionAnswer::class);
        $this->tagRepo = app()->make(QuestionTag::class);
    }

    /**
     * @inheritDoc
     */
    public function getAllTag(User $user)
    {
        try {
            return $this->tagRepo->all();
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getQuestions(User $user, $data, int $per_page = 20)
    {
        try {
            if (!empty($data['tags'])) {
                $questions = DB::table('question_tags')->whereIn('tag_id', $data['tags'])
                    ->selectRaw("distinct question_id")
                    ->paginate($per_page);

                $data = $this->questionRepo->whereIn('id', $questions->pluck('question_id'))->get();
            } else {
                $questions = $this->questionRepo->paginate($per_page);
                $data = $questions->items();
            }

            return [
                'current_page' => $questions->currentPage(),
                'data' => $data,
                'total' => $questions->total(),
                'last_page' => $questions->lastPage(),
            ];
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function createRandom(User $user, array $data)
    {
        try {
            $number_of_questions = $data['number_of_questions'] ?? 20;
            if (!empty($data['tags'])) {
                $questions = DB::table('question_tags')->whereIn('tag_id', $data['tags'])
                    ->selectRaw("distinct question_id")
                    ->inRandomOrder()->limit($number_of_questions)->get();

                $data = $questions->pluck('question_id');
            } else {
                $questions = $this->questionRepo->inRandomOrder()->limit($number_of_questions)->get();
                $data = $questions->pluck('id');
            }

            if ($data) {
                return $data;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
