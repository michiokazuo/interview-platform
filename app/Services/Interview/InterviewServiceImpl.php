<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\User;
use App\Services\DailyCo\DailyCoService;
use App\Services\GroupQuestion\GroupQuestionService;
use App\Services\RNews\NewsService;
use App\Services\RProcess\ProcessService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class InterviewServiceImpl implements InterviewService
{
    protected $_repository, $newsService, $dailyCoService, $gqService, $processService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(NewsService    $newsService, ProcessService $processService,
                                DailyCoService $dailyCoService, GroupQuestionService $gqService)
    {
        $this->_repository = app()->make(Interview::class);
        $this->newsService = $newsService;
        $this->dailyCoService = $dailyCoService;
        $this->gqService = $gqService;
        $this->processService = $processService;
    }

    /**
     * @param User $user
     * @return mixed
     */
    private function createRoom(User $user, $is_now)
    {
        try {
            $data = [];
            $rooms = $this->dailyCoService->getAll($user);
            if ($rooms) {
                if ($rooms['total_count'] < config('app.daily_co_room')) {
                    $room = $this->dailyCoService->create($user, $data);
                    if ($room) {
                        return $room['url'];
                    }
                } else if ($is_now) {
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
                    ['process_id', $data['process_id'] ?? null],
                ])->first();

                if ($interview) {
                    return $interview;
                }
            }

            if (($user->company_id && $user->company_id == $data['company_id'])
                || ($user->candidate_id && $user->candidate_id == $data['candidate_id'])) {

                if (empty($data['company_id']) && empty($data['news_id']) && !empty($data['interview_meeting'])) {
                    $data['time'] = now();
                    $room = $this->createRoom($user, true);
                    if ($room) {
                        $data['room'] = $room;
                    }
                }

                if (!empty($data['interview_test'])) {
                    $data['time'] = now();
                    $group = $this->gqService->createRandom($user, $data, null);
                    if ($group) {
                        $data = array_merge($data, ['gq_test_id' => $group->id]);
                    }
                }

                if (isset($data['process_id'])) {
                    $process = $this->processService->findById($user, $data['process_id']);
                    if ($process) {
                        $prevStep = $this->_repository->where([
                            ['news_id', $data['news_id']],
                            ['candidate_id', $data['candidate_id']],
                            ['company_id', $data['company_id']],
                        ])->where('process_id', '<', $data['process_id'])
                            ->orderBy('process_id', 'desc')->first();
                        $nextStep = $this->_repository->where([
                            ['news_id', $data['news_id']],
                            ['candidate_id', $data['candidate_id']],
                            ['company_id', $data['company_id']],
                        ])->where('process_id', '>', $data['process_id'])
                            ->orderBy('process_id', 'asc')->first();

                        if (empty($nextStep) && (empty($prevStep) || $prevStep->is_success)) {
                            $data['process_id'] = $process->id;
                        } else {
                            return false;
                        }

                    }
                }

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
                $news = $this->newsService->findById($user, $news_id);
                if ($news) {
                    $interview = $this->_repository->join('recruitment_news', 'interview.news_id', '=', 'recruitment_news.id')
                        ->where([
                            ['project_id', $news->project_id],
                            ['candidate_id', $user->candidate_id],
                        ])->selectRaw("interview.*")->first();

                    if ($interview) {
                        return $interview;
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
                        if ($user->company_id) {
                            $record['company'] = $link;
                        } else {
                            $record['candidate'] = $link;
                        }
                    } else if ($interview->candidate_id) {
                        $record['candidate'] = $link;
                    }

                    $data['record'] = ($record);
                }

                if (isset($data['result'])) {
                    $result = $interview->result ?? [];
                    if ($interview->company_id) {
                        if ($user->company_id) {
                            $result['company'] = $data['result'];
                        } else {
                            $result['candidate'] = $data['result'];
                        }
                    } else if ($interview->candidate_id) {
                        $result['candidate'] = $data['result'];
                    }

                    $data['result'] = ($result);
                }

                if (isset($data['process_id']) && !$interview->process_id) {
                    $process = $this->processService->findById($user, $data['process_id']);
                    if ($process) {
                        $data['process_id'] = $process->id;
                    }
                } else {
                    unset($data['process_id']);
                }

                if (isset($data['gq_interview_id'])) {
                    $interviewQs = $this->gqService->findById($user, $data['gq_interview_id']);
                    if ($interviewQs) {
                        $data['gq_interview_id'] = $interviewQs->id;
                    }
                }

                if ($interview->room && (!empty($data['result']) || !empty($data['record']))) {
                    $array = explode('/', $interview->room);
                    $room_name = end($array);
                    $roomExists = $this->_repository->where('room', $interview->room)->count();
                    if ($roomExists == 1 && ($user->company_id || !$interview->company_id)) {
                        $this->dailyCoService->delete($user, $room_name);
                    }
                    $data['room'] = null;
                } else if (empty($data['result']) && empty($data['record'])) {
                    if (isset($data['form']) && $data['form'] === 'Online' && !$interview->room) {
                        $room = $this->createRoom($user, false);
                        if ($room) {
                            $data['room'] = $room;
                        }
                    } else if (isset($data['form']) && $data['form'] === 'Offline' && $interview->room) {
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

                $this->_repository->where([
                    ['candidate_id', $interview->candidate_id],
                    ['company_id', $interview->company_id],
                    ['news_id', $interview->news_id],
                ])->delete();

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
    public function showAllByNews(User $user, $news_id, $process_id)
    {
        try {
            $data = [];
            $news = null;
            if (!empty($news_id)) {
                $news = $this->newsService->findById($user, $news_id);

                if ($news && $news->company->id == $user->company_id) {
                    $data = $this->_repository->where([
                        ['company_id', $user->company_id],
                        ['news_id', $news_id]
                    ])->orderBy('created_at', 'desc')
                        ->get();
                }
            } else if (!empty($process_id)) {
                $process = $this->processService->findById($user, $process_id);

                if ($process && $process->project && $process->project->company_id == $user->company_id) {
                    $interviews = $this->_repository->join('recruitment_news', 'interview.news_id', '=', 'recruitment_news.id')
                        ->where([
                            ['project_id', $process->project_id],
                            ['interview.company_id', $user->company_id]
                        ])
                        ->where(function ($query) use ($process) {
                            $query->where('process_id', '<=', $process->id)
                                ->orWhere('process_id', null);
                        })->selectRaw("interview.*")->orderBy('created_at', 'desc')->get();

                    foreach ($interviews as $interview) {
                        if ($interview->process_id == $process_id || empty($interview->process_id)) {
                            $data[] = $interview;
                        } else {
                            $nextStep = $this->_repository->where([
                                ['news_id', $interview->news_id],
                                ['candidate_id', $interview->candidate_id],
                                ['company_id', $interview->company_id],
                            ])->where('process_id', '>', $interview->process_id)
                                ->orderBy('process_id', 'asc')->first();

                            if ($interview->is_success && empty($nextStep)) {
                                $data[] = $interview;
                            }
                        }
                    }
                }
            }

            if ($data) {
                return [
                    'data' => $data,
                    'news' => $news
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
                    $group = $this->gqService->store($user, $data, $interview->gq_test_id);
                } else {
                    $group = $this->gqService->createRandom($user, $data, $interview->gq_test_id);
                }

                if ($group) {
                    $interview->update([
                        'gq_test_id' => $group->id,
                    ]);
                    return $this->findById($user, $interview_id);
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

    /**
     * @inheritDoc
     */
    public function showAllByCompany(User $user)
    {
        try {
            if ($user->company) {
                return $this->_repository->where([
                    ['company_id', $user->company_id]
                ])
                    ->orderBy('created_at', 'desc')
                    ->get();
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
    public function deleteOnly(User $user, int $interview_id): bool
    {
        try {
            $interview = $this->_repository->find($interview_id);

            if ($interview && ($interview->company_id === $user->company_id
                    || $interview->candidate_id === $user->candidate_id)) {
                $interview->delete();
                return true;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
