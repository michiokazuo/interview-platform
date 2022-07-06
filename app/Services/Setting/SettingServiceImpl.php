<?php

namespace App\Services\Setting;

use App\Jobs\CrawlData;
use App\Models\BlogComment;
use App\Models\RecruitmentProcess;
use App\Models\SettingCrawler;
use App\Models\User;
use App\Services\Admin\AdminService;
use App\Services\Blog\BlogService;
use App\Services\QuestionAnswerTag\QATService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class SettingServiceImpl implements SettingService
{
    protected $_repository, $cmtRepo, $processRepo, $adminService, $blogService, $qatService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(BlogService $blogService, AdminService $adminService, QATService $qatService)
    {
        $this->_repository = app()->make(SettingCrawler::class);
        $this->blogService = $blogService;
        $this->cmtRepo = app()->make(BlogComment::class);
        $this->processRepo = app()->make(RecruitmentProcess::class);
        $this->adminService = $adminService;
        $this->qatService = $qatService;
    }

    /**
     * @inheritDoc
     */
    public function crawler(User $user, array $tags, int $numbers)
    {
        try {
            if ($user->role_id === 1) {
                $run = "running";
                $stop = "stop";

                $status = $stop;
                $status_key = 'status_run_crawler';
                $dataStatus = $this->_repository->where('key', $status_key)->first();

                if ($dataStatus) {
                    $status = $dataStatus->value;
                } else {
                    $this->_repository->create([
                        'key' => $status_key,
                        'value' => $run
                    ]);
                }
                $dataStatus = $this->_repository->where('key', $status_key)->first();

                if ($status == $stop) {
                    $this->_repository->create([
                        'key' => 'crawler',
                        'value' => json_encode([
                            'user_id' => $user->id,
                            'tags' => $tags,
                            'numbers' => $numbers
                        ])
                    ]);
                    CrawlData::dispatch($dataStatus, $tags, $numbers);

                    return true;
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
    public function dashboardCandidate(User $user, array $data)
    {
        try {
            if ($user->role_id === 2) {
                $interviews = $user->candidate->interviews;
                $blogCount = $user->blogs->count();
                $cmtCount = $user->comments->count();

                $ongoing = 0;
                $passed = 0;
                $failed = 0;

                $record = 0;
                $question = 0;
                $none = 0;
                $both = 0;
                foreach ($interviews as $interview) {
                    if ($interview->news) {
                        if (is_null($interview->is_success)) {
                            $ongoing++;
                        } else {
                            $interview->is_success ? $passed++ : $failed++;
                        }
                    } else {
                        $recordRS = $interview->record;
                        $groupTest = $interview->gq_test_id;
                        if ($recordRS && $groupTest) {
                            $both++;
                        } elseif ($recordRS) {
                            $record++;
                        } elseif ($groupTest) {
                            $question++;
                        } else {
                            $none++;
                        }
                    }
                }

                return [
                    'application' => [
                        'data' => [$ongoing, $passed, $failed],
                        'total' => $ongoing + $passed + $failed,
                        'label' => ['Ongoing', 'Passed', 'Failed'],
                        'title' => 'Interviews'
                    ],
                    'blog_cmt' => [
                        'data' => [$blogCount, $cmtCount],
                        'total' => $blogCount + $cmtCount,
                        'label' => ['Blogs', 'Comments'],
                        'title' => 'Blog & Comment'
                    ],
                    'practice' => [
                        'data' => [$record, $question, $none, $both],
                        'total' => $record + $question + $none + $both,
                        'label' => ['Has record', 'Has questions', 'None', 'Both'],
                        'title' => 'Practice'
                    ],
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
    public function dashboardCompany(User $user, array $data)
    {
        try {
            if ($user->role_id === 3) {
                $company = $user->company;
                $interviews = $company->interviews;
                $groupQuestions = $company->groupQuestions;
                $newsList = $company->news;
                $projects = $company->projects;
                $projectCount = $projects->count();
                $projects_id = $projects->pluck('id')->toArray();
                $processCount = $this->processRepo->whereIn('project_id', $projects_id)->count();
                $cmtCount = $user->comments->count();
                $blogCount = $user->blogs->count();

                $ongoing = 0;
                $passed = 0;
                $failed = 0;

                $for_candidate = 0;
                $for_interview = 0;

                $has_interview = 0;
                $none_interview = 0;

                $arr_candidates = [];

                foreach ($interviews as $interview) {
                    $arr_candidates[] = $interview->candidate_id;
                    $arr_candidates = array_unique($arr_candidates);
                    if (is_null($interview->is_success)) {
                        $ongoing++;
                    } else {
                        $interview->is_success ? $passed++ : $failed++;
                    }
                }

                foreach ($groupQuestions as $groupQuestion) {
                    $groupQuestion->is_interview ? $for_interview++ : $for_candidate++;
                }

                foreach ($newsList as $news) {
                    $news->interviews->count() ? $has_interview++ : $none_interview++;
                }

                return [
                    'interview' => [
                        'data' => [$ongoing, $passed, $failed],
                        'total' => $interviews->count(),
                        'label' => ['Ongoing', 'Passed', 'Failed'],
                        'title' => 'Interviews'
                    ],
                    'blog_cmt' => [
                        'data' => [$blogCount, $cmtCount],
                        'total' => $blogCount + $cmtCount,
                        'label' => ['Blogs', 'Comments'],
                        'title' => 'Blog & Comment'
                    ],
                    'questions' => [
                        'data' => [$for_candidate, $for_interview],
                        'total' => $groupQuestions->count(),
                        'label' => ['For candidate', 'For interview'],
                        'title' => 'Group questions'
                    ],
                    'news' => [
                        'data' => [$has_interview, $none_interview],
                        'total' => $newsList->count(),
                        'label' => ['Has interview', 'None interview'],
                        'title' => 'News'
                    ],
                    'others' => [
                        'project' => $projectCount,
                        'process' => $processCount,
                        'candidate' => count($arr_candidates),
                        'title' => 'Others'
                    ],
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
    public function dashboardAdmin(User $user, array $data)
    {
        try {
            if ($user->role_id === 1) {
                $allUsers = $this->adminService->getAll($user);
                $blogs = $this->blogService->showAll($user, $data);
                $qat = $this->qatService->countQAT($user);

                $cmtCount = $this->cmtRepo->count();

                $candidates = 0;
                $companies_active = 0;
                $companies_inactive = 0;

                $blogCount = 0;

                $questionCount = 0;
                $answerCount = 0;
                $tagCount = 0;

                if ($blogs) {
                    $blogCount = $blogs['total'];
                }

                if ($allUsers) {
                    $candidates = $allUsers['candidates'];
                    $companies_inactive = $allUsers['inactive'];
                    $companies_active = $allUsers['companies'] - $companies_inactive;
                }

                if ($qat) {
                    $questionCount = $qat['questions'];
                    $answerCount = $qat['answers'];
                    $tagCount = $qat['tags'];
                }

                $graphNew = $this->adminService->graph7Days($user);

                return [
                    'blog_cmt' => [
                        'data' => [$blogCount, $cmtCount],
                        'total' => $blogCount + $cmtCount,
                        'label' => ['Blogs', 'Comments'],
                        'title' => 'Blog & Comment'

                    ],
                    'users' => [
                        'data' => [$candidates, $companies_active, $companies_inactive],
                        'total' => $candidates + $companies_active + $companies_inactive,
                        'label' => ['Candidates', 'Companies active', 'Companies inactive'],
                        'title' => 'Users'
                    ],
                    'qat' => [
                        'data' => [$questionCount, $answerCount, $tagCount],
                        'total' => $questionCount + $answerCount + $tagCount,
                        'label' => ['Questions', 'Answers', 'Tags'],
                        'title' => 'QAT'
                    ],
                    'graph' => $graphNew,
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
