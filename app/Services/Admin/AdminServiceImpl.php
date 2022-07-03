<?php

namespace App\Services\Admin;

use App\Models\BlogComment;
use App\Models\Interview;
use App\Models\InterviewBlog;
use App\Models\RecruitmentNews;
use App\Models\User;
use App\Services\Candidate\CandidateService;
use App\Services\Company\CompanyService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class AdminServiceImpl implements AdminService
{
    protected $_repository, $blogRepo, $cmtRepo, $interviewRepo, $newsRepo,
        $candidateService, $companyService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(CandidateService $candidateService, CompanyService $companyService)
    {
        $this->_repository = app()->make(User::class);
        $this->candidateService = $candidateService;
        $this->companyService = $companyService;
        $this->blogRepo = app()->make(InterviewBlog::class);
        $this->cmtRepo = app()->make(BlogComment::class);
        $this->interviewRepo = app()->make(Interview::class);
        $this->newsRepo = app()->make(RecruitmentNews::class);
    }

    /**
     * @inheritDoc
     */
    public function activeCompany(User $user, int $companyId)
    {
        try {
            if ($user->role_id == 1) {
                return $this->companyService->activeCompany($user, $companyId);
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
    public function deleteUser(User $user, $id)
    {
        try {
            if ($user->role_id == 1 && $user->id != $id) {
                $userDelete = $this->_repository->findOrFail($id);
                if ($userDelete->company_id) {
                    return $userDelete->company->delete();
                } else if ($userDelete->candidate_id) {
                    return $userDelete->candidate->delete();
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
    public function getAll(User $user, int $per_page = 10)
    {
        try {
            if ($user->role_id == 1) {
                $data = $this->_repository->where('role_id', '<>', 1)->get();
                $inactive = $this->companyService->getAllInactiveCompany($user);

                return [
                    'data' => $data,
                    'inactive' => $inactive,
                    'total' => $data ? $data->count() : 0,
                    'candidates' => $data ? $data->where('candidate_id', '<>', null)->count() : 0,
                    'companies' => $data ? $data->where('company_id', '<>', null)->count() : 0,
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
    public function graph7Days(User $user)
    {
        try {
            if ($user->role_id == 1) {
                $now = now();
                $start = now()->subDays(7);

                $period = new CarbonPeriod($start->format('Y-m-d'), $now->format('Y-m-d'));
                $arrDate = [];
                foreach ($period as $data) {
                    $arrDate[] = $data->format('Y-m-d');
                }
                
                $users = $this->_repository->where('role_id', '<>', 1)
                    ->whereBetween('created_at', [
                        $start->format('Y-m-d 00:00:00'),
                        now(),
                    ])->groupByRaw('date(created_at), role_id')
                    ->selectRaw('date(created_at) as date, role_id, count(*) as total')
                    ->get();

                $dataUser = [];
                foreach ($users as $user) {
                    if (isset($dataUser[$user->date])) {
                        $dataUser[$user->date] = [];
                    }
                    $dataUser[$user->date][$user->role_id] = $user->total;
                }

                $blogs = $this->blogRepo->whereBetween('created_at', [
                    $start->format('Y-m-d 00:00:00'),
                    now(),
                ])
                    ->groupByRaw('date(created_at)')
                    ->selectRaw('date(created_at) as date, count(*) as total')
                    ->get();

                $dataBlog = [];
                foreach ($blogs as $blog) {
                    $dataBlog[$blog->date] = $blog->total;
                }

                $cmtS = $this->cmtRepo->whereBetween('created_at', [
                    $start->format('Y-m-d 00:00:00'),
                    now(),
                ])
                    ->groupByRaw('date(created_at)')
                    ->selectRaw('date(created_at) as date, count(*) as total')
                    ->get();

                $dataCmt = [];
                foreach ($cmtS as $cmt) {
                    $dataCmt[$cmt->date] = $cmt->total;
                }

                $graphUser = [
                    'candidates' => [],
                    'companies' => [],
                ];
                $graphBlogCmt = [
                    'blogs' => [],
                    'comments' => [],
                ];

                foreach ($arrDate as $date) {
                    $tmpCandidates = 0;
                    $tmpCompanies = 0;
                    $tmpBlogs = 0;
                    $tmpCmtS = 0;

                    if (isset($dataUser[$date])) {
                        $data = $dataUser[$date];
                        $tmpCandidates = $data['2'] ?? 0;
                        $tmpCompanies = $data['3'] ?? 0;
                    }

                    if (isset($dataBlog[$date])) {
                        $tmpBlogs = $dataBlog[$date];
                    }
                    if (isset($dataCmt[$date])) {
                        $tmpCmtS = $dataCmt[$date];
                    }

                    $graphUser['candidates'][] = $tmpCandidates;
                    $graphUser['companies'][] = $tmpCompanies;
                    $graphBlogCmt['blogs'][] = $tmpBlogs;
                    $graphBlogCmt['comments'][] = $tmpCmtS;
                }

                return [
                    'user' => [
                        'data' => $graphUser,
                        'title' => 'Users',
                    ],
                    'blog_cmt' => [
                        'data' => $graphBlogCmt,
                        'title' => 'Blog & Comment',
                    ],
                    'categories' => $arrDate,
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
    public function graphInterview(User $user, array $data)
    {
        try {
            $defaultTypes = ['daily', 'monthly', 'yearly'];
            if ($user->role_id == 1) {
                $start = isset($data['start']) ? new Carbon($data['start']) : now()->subDays(7);
                $end = isset($data['end']) ? new Carbon($data['end']) : now();
                $period = new CarbonPeriod($start, $end);

                $type = 'daily';
                if (isset($data['type']) && in_array($data['type'], $defaultTypes)) {
                    $type = $data['type'];
                }

                $arrDate = [];
                foreach ($period as $data) {
                    if ($type == 'monthly') {
                        $arrDate[] = $data->format('Y-m');
                    } else if ($type == 'yearly') {
                        $arrDate[] = $data->format('Y');
                    } else {
                        $arrDate[] = $data->format('Y-m-d');
                    }
                }
                $arrDate = array_values(array_unique($arrDate));


                $interviews = $this->interviewRepo->where('news_id', '<>', null)
                    ->whereBetween('created_at', [
                        $start->format('Y-m-d 00:00:00'),
                        $end->format('Y-m-d 23:59:59'),
                    ]);

                $practices = $this->interviewRepo->where('news_id', null)
                    ->whereBetween('created_at', [
                        $start->format('Y-m-d 00:00:00'),
                        $end->format('Y-m-d 23:59:59'),
                    ]);

                $newsList = $this->newsRepo->whereBetween('created_at', [
                    $start->format('Y-m-d 00:00:00'),
                    $end->format('Y-m-d 23:59:59'),
                ]);

                if ($type == 'monthly') {
                    $interviews->groupByRaw("date_format(created_at, '%Y-%m')")
                        ->selectRaw("date_format(created_at, '%Y-%m') as date, count(*) as total");
                    $practices->groupByRaw("date_format(created_at, '%Y-%m')")
                        ->selectRaw("date_format(created_at, '%Y-%m') as date, count(*) as total");
                    $newsList->groupByRaw("date_format(created_at, '%Y-%m')")
                        ->selectRaw("date_format(created_at, '%Y-%m') as date, count(*) as total");
                } else if ($type == 'yearly') {
                    $interviews->groupByRaw('year(created_at)')
                        ->selectRaw('year(created_at) as date, count(*) as total');
                    $practices->groupByRaw('year(created_at)')
                        ->selectRaw('year(created_at) as date, count(*) as total');
                    $newsList->groupByRaw('year(created_at)')
                        ->selectRaw('year(created_at) as date, count(*) as total');
                } else {
                    $interviews->groupByRaw('date(created_at)')
                        ->selectRaw('date(created_at) as date, count(*) as total');
                    $practices->groupByRaw('date(created_at)')
                        ->selectRaw('date(created_at) as date, count(*) as total');
                    $newsList->groupByRaw('date(created_at)')
                        ->selectRaw('date(created_at) as date, count(*) as total');
                }
                
                $interviews = $interviews->get();
                $practices = $practices->get();
                $newsList = $newsList->get();

                $dataInterview = [];
                foreach ($interviews as $interview) {
                    $dataInterview[$interview->date] = $interview->total;
                }

                $dataPractice = [];
                foreach ($practices as $practice) {
                    $dataPractice[$practice->date] = $practice->total;
                }

                $dataNews = [];
                foreach ($newsList as $news) {
                    $dataNews[$news->date] = $news->total;
                }

                foreach ($arrDate as $date) {
                    $tmpInterview = 0;
                    $tmpPractice = 0;
                    $tmpNews = 0;

                    if (isset($dataInterview[$date])) {
                        $tmpInterview = $dataInterview[$date] ?? 0;
                    }
                    if (isset($dataPractice[$date])) {
                        $tmpPractice = $dataPractice[$date] ?? 0;
                    }
                    if (isset($dataNews[$date])) {
                        $tmpNews = $dataNews[$date] ?? 0;
                    }

                    $graphInterview['interviews'][] = $tmpInterview;
                    $graphInterview['practices'][] = $tmpPractice;
                    $graphInterview['news'][] = $tmpNews;
                }

                return [
                    'interviews' => $graphInterview,
                    'categories' => $arrDate,
                    'title' => 'Interviews Statistics',
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
