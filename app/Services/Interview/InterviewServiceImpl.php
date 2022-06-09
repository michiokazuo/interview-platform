<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\User;
use App\Services\DailyCo\DailyCoService;
use App\Services\QuestionAnswerTag\QATService;
use App\Services\RNews\NewsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class InterviewServiceImpl implements InterviewService
{
    protected $_repository, $newsService, $dailyCoService, $qatService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(NewsService $newsService, DailyCoService $dailyCoService, QATService $qatService)
    {
        $this->_repository = app()->make(Interview::class);
        $this->newsService = $newsService;
        $this->dailyCoService = $dailyCoService;
        $this->qatService = $qatService;
    }

    /**
     * @param User $user
     * @return mixed
     */
    private function createRoom(User $user)
    {
        try {
            $data = [];
            $rooms = $this->dailyCoService->getAll($user);
            if ($rooms) {
                if ($rooms['total_count'] < env('MAX_ROOM')) {
                    $room = $this->dailyCoService->create($user, $data);
                    if ($room) {
                        return $room['url'];
                    }
                } else {
                    $interviewNotRun = $this->_repository->whereNotNull('room')
                        ->where('time', '>=', (new Carbon(now()))->addDay())
                        ->first();

                    if ($interviewNotRun) {
                        return $interviewNotRun->room;
                    }
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
    public function store(User $user, array $data)
    {
        try {
            if (!empty($data['news_id']) && ($user->company_id == $data['company_id']
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

                if (empty($data['company_id']) && empty($data['news_id']) && !empty($data['interview_meeting'])) {
                    $data['time'] = now();
                    $room = $this->createRoom($user);
                    if ($room) {
                        $data['room'] = $room;
                    }
                }

                if ($data['interview_test']) {
                    $data['time'] = now();
                    $questions = $this->qatService->createRandom($user, $data);
                }

                $interview = $this->_repository->create($data);

                if ($interview) {
                    if (!empty($data['interview_test'])) {
                        $interview->questions()->sync($questions ?? []);
                    }
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

                if (isset($data['record'])) {
                    $time = strtotime("now");
                    $path = "records/local/$time";
                    $data['record']->move(public_path($path), $data['record']->getClientOriginalName());
                    $link = '/' . $path . '/' . $data['record']->getClientOriginalName();
                    $record = $interview->record ?? [];
                    if ($interview->company_id) {
                        $record['company'] = $link;
                    } else if ($interview->candidate_id) {
                        $record['candidate'] = $link;
                    }

                    $data['record'] = ($record);
                }

                if (isset($data['result'])) {
                    $result = $interview->result ?? [];
                    if ($interview->company_id) {
                        $result['company'] = $data['result'];
                    } else if ($interview->candidate_id) {
                        $result['candidate'] = $data['result'];
                    }

                    $data['result'] = ($result);
                }

                if ($interview->room && (!empty($data['result']) || !empty($data['record']))) {
                    $array = explode('/', $interview->room);
                    $room_name = end($array);
                    $this->dailyCoService->delete($user, $room_name);
                    $data['room'] = null;
                } else if (empty($data['result']) && empty($data['record'])) {
                    if ($data['form'] === 'Online' && !$interview->room) {
                        $room = $this->createRoom($user);
                        if ($room) {
                            $data['room'] = $room;
                        }
                    } else if ($data['form'] === 'Offline' && $interview->room) {
                        $array = explode('/', $interview->room);
                        $room_name = end($array);
                        $this->dailyCoService->delete($user, $room_name);
                        $data['room'] = null;
                    }
                }

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
                if ($interview->room) {
                    $array = explode('/', $interview->room);
                    $room_name = end($array);
                    $this->dailyCoService->delete($user, $room_name);
                }
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

    /**
     * @inheritDoc
     */
    public function createTest(User $user, int $interview_id, array $data)
    {
        try {
            $interview = $this->_repository->find($interview_id);

            if ($interview && ($interview->company_id === $user->company_id
                    || $interview->candidate_id === $user->candidate_id)) {
                $data['candidate_id'] = $interview->candidate_id;
                $data['company_id'] = $interview->company_id;
                $data['news_id'] = $interview->news_id;

                if (!empty($data['interview_questions'])) {
                    $interview->questions()->sync($data['interview_questions']);
                } else {
                    $questions = $this->qatService->createRandom($user, $data);
                    $interview->questions()->sync($questions);
                }

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
    public function saveResultTest(User $user, int $interview_id, array $data)
    {
        try {
            $interview = $this->_repository->find($interview_id);

            if ($interview && ($interview->company_id === $user->company_id
                    || $interview->candidate_id === $user->candidate_id)) {
                $data['candidate_id'] = $interview->candidate_id;
                $data['company_id'] = $interview->company_id;
                $data['news_id'] = $interview->news_id;

                if (isset($data['result'])) {
                    $result = $interview->result ?? [];
                    $result['candidate'] = $data['result'];

                    $data['result'] = ($result);

                    $interview->update($data);
                }


                return $this->findById($user, $interview_id);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
