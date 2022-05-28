<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\User;
use App\Services\RNews\NewsService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class InterviewServiceImpl implements InterviewService
{
    protected $_repository, $newsService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(NewsService $newsService)
    {
        $this->_repository = app()->make(Interview::class);
        $this->newsService = $newsService;
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, array $data)
    {
        try {
            if ($data['news_id'] && ($user->company_id == $data['company_id']
                    || $user->candidate_id == $data['candidate_id'])) {
                $news = $this->newsService->findById($user, $data['news_id']);

                if (!$news) {
                    return false;
                }
                $interview = $this->_repository->where([
                    ['news_id', $data['news_id']],
                    ['candidate_id', $data['candidate_id']],
                    ['company_id', $data['company_id']],
                ])->first();

                if ($interview) {
                    return $interview;
                }
            }

            if (($user->company_id && $user->company_id == $data['company_id'])
                || ($user->candidate_id && $user->candidate_id == $data['candidate_id'])) {
                $interview = $this->_repository->create($data);

                if ($interview) {
                    return $interview;
                }
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
    public function findById(User $user, int $interview_id)
    {
        try {
            $interview = $this->_repository->find($interview_id);

            if ($interview && ($interview->company_id === $user->company_id
                    || $interview->candidate_id === $user->candidate_id)) {
                return $interview;
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
    public function findByNewsId(User $user, int $news_id)
    {
        try {
            if ($user->candidate_id) {
                $interview = $this->_repository->where([
                    ['news_id', $news_id],
                    ['candidate_id', $user->candidate_id],
                ])->first();

                if ($interview) {
                    return $interview;
                }
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
    public function showAllByUser(User $user, int $candidate_id)
    {
        try {
            if ($user->candidate_id === $candidate_id) {
                $applications = $this->_repository->where([
                    ['candidate_id', $candidate_id],
                    ['news_id', '<>', null],
                ])->orderBy('created_at', 'desc')->get();
                
                $practices = $this->_repository->where([
                    ['candidate_id', $candidate_id],
                    ['news_id', null],
                ])->orderBy('created_at', 'desc')->get();

                return [
                    'applications' => $applications,
                    'practices' => $practices,
                ];
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
    public function update(User $user, array $data, int $interview_id)
    {
        try {
            $interview = $this->_repository->find($interview_id);

            if ($interview && ($interview->company_id === $user->company_id
                    || $interview->candidate_id === $user->candidate_id)) {
                $data['candidate_id'] = $interview->candidate_id;
                $data['company_id'] = $interview->company_id;
                $data['news_id'] = $interview->news_id;

                $interview->update($data);

                return $this->findById($user, $interview_id);
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
    public function delete(User $user, int $interview_id): bool
    {
        try {
            $interview = $this->_repository->find($interview_id);

            if ($interview && ($interview->company_id === $user->company_id
                    || $interview->candidate_id === $user->candidate_id)) {

                return $interview->delete();
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
    public function showAllByNews(User $user, int $news_id, int $per_page = 8)
    {
        try {
            $news = $this->newsService->findById($user, $news_id);

            if ($news && $news->company->id == $user->company_id) {
                $interviews = $this->_repository->where([
                    ['company_id', $user->company_id],
                    ['news_id', $news_id]
                ])
                    ->orderBy('created_at', 'desc')
                    ->get();

                return [
                    'data' => $interviews,
                    'news' => $news,
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
