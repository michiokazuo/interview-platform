<?php

namespace App\Services\QuestionAnswerTag;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTag;
use App\Models\SettingCrawler;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;

class QATServiceImpl implements QATService
{
    protected $questionRepo, $answerRepo, $tagRepo, $settingRepo;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->questionRepo = app()->make(Question::class);
        $this->answerRepo = app()->make(QuestionAnswer::class);
        $this->tagRepo = app()->make(QuestionTag::class);
        $this->settingRepo = app()->make(SettingCrawler::class);
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
                if ($user->role_id == 1) {
                    $questions = $this->questionRepo->paginate($per_page);
                } else {
                    $questions = $this->questionRepo->where('company_id', null)
                        ->orWhere('company_id', $user->company_id)
                        ->paginate($per_page);
                }

                $data = $questions->items();
            }

            $status = $this->settingRepo->where('key', 'status_run_crawler')->first();
            $crawler = $this->settingRepo->where('key', 'crawler')->orderBy('created_at', 'desc')->first();
            
            return [
                'current_page' => $questions->currentPage(),
                'data' => $data,
                'total' => $questions->total(),
                'last_page' => $questions->lastPage(),
                'status' => $status ? $status->value : 'stop',
                'last_time' => $crawler ? date('Y-m-d H:i:s', strtotime($crawler->created_at)) : null
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

    /**
     * @inheritDoc
     */
    public function createQA(User $user, array $data)
    {
        try {
            $question = null;
            if (isset($data['question'])) {
                $questionData = $data['question'];
                $rootQs = $this->questionRepo->find($questionData['root_question_id'] ?? null);

                if (isset($questionData['id'])) {
                    $question = $this->questionRepo->find($questionData['id']);
                }

                if ($question) {
                    $question->update([
                        'company_id' => $user->company_id,
                        'title' => $data['title'] ?? $rootQs->title ?? '',
                        'root_question_id' => $rootQs ? $rootQs->id : null,
                        'content' => $questionData['content'],
                    ]);
                } else {
                    $question = $this->questionRepo->create([
                        'company_id' => $user->company_id,
                        'title' => $data['title'] ?? $rootQs->title ?? '',
                        'root_question_id' => $rootQs ? $rootQs->id : null,
                        'content' => $questionData['content'],
                    ]);
                }
            }

            if ($question && isset($data['answer'])) {
                $answerData = $data['answer'];
                $answers = $question->answers;
                if ($answers->count() > 0) {
                    $answers[0]->update([
                        'content' => $answerData['content'],
                    ]);
                    
                } else {
                    $this->answerRepo->create([
                        'question_id' => $question->id,
                        'content' => $answerData['content'],
                        'score' => 0,
                        'answered' => false,
                    ]);
                }
            }

            if ($question) {
                return $question;
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
    public function delete(User $user, array $data)
    {
        // TODO: Implement delete() method.
    }
}
