<?php

namespace App\Services\GroupQuestion;

use App\Models\GroupQuestion;
use App\Models\User;
use App\Services\QuestionAnswerTag\QATService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

class GroupQuestionServiceImpl implements GroupQuestionService
{
    protected $_repository, $qatService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(QATService $qatService)
    {
        $this->_repository = app()->make(GroupQuestion::class);
        $this->qatService = $qatService;
    }

    /**
     * @inheritDoc
     */
    public function getAll(User $user)
    {
        try {
            if ($user->company) {
                $company_id = $user->company_id;
                $groups = $this->_repository->where('company_id', $company_id)
                    ->orderBy('created_at', 'desc')->get();

                return $groups;
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
    public function store(User $user, array $data, $group_id)
    {
        try {
            $group = $this->_repository->find($group_id);

            if (!$group) {
                $group = $this->_repository->create([
                    'company_id' => $user->company_id,
                    'title' => $data['title'] ?? "$user->name interview " . Str::random(5),
                    'topics' => $data['topics'] ?? '',
                    'is_interview' => $data['is_interview'] ?? false,
                ]);
            } else {
                $group->update([
                    'title' => $data['title'] ?? $group->title,
                    'topics' => $data['topics'] ?? '',
                ]);
            }

            if ($group) {
                if (isset($data['edit_questions']) && !empty($data['edit_questions'])) {
                    foreach ($data['edit_questions'] as $qa) {
                        if (isset($qa['question'])) {
                            $question = $this->qatService->createQA($user, $qa);
                            if ($question) {
                                $data['interview_questions'][] = $question->id;
                            }
                        }
                    }
                }

                $group->questions()->sync($data['interview_questions'] ?? []);

                return $group;
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
    public function createRandom(User $user, array $data, $group_id)
    {
        try {
            $group = $this->_repository->find($group_id);

            if (!$group) {
                $group = $this->_repository->create([
                    'company_id' => $user->company_id,
                    'title' => $data['title'] ?? "$user->name practice " . Str::random(5),
                    'topics' => $data['topics'] ?? '',
                ]);
            } else {
                $group->update([
                    'title' => $data['title'] ?? $group->title,
                    'topics' => $data['topics'] ?? '',
                ]);
            }

            if ($group) {
                $questions = $this->qatService->createRandom($user, $data);
                $group->questions()->sync($questions ?? []);

                return $group;
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
    public function delete(User $user, int $group_id)
    {
        try {
            $group = $this->_repository->find($group_id);

            if ($group && $group->company_id == $user->company_id) {
                $group->delete();

                return true;
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
    public function findById(User $user, int $group_id)
    {
        try {
            $group = $this->_repository->find($group_id);

            if ($group && $group->company_id == $user->company_id) {
                return $group;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
