<?php

namespace App\Providers;

use App\Services\Blog\BlogService;
use App\Services\Blog\BlogServiceImpl;
use App\Services\Candidate\CandidateService;
use App\Services\Candidate\CandidateServiceImpl;
use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceImpl;
use App\Services\Company\CompanyService;
use App\Services\Company\CompanyServiceImpl;
use App\Services\CV\CVService;
use App\Services\CV\CVServiceImpl;
use App\Services\CVDetail\CVDetailService;
use App\Services\CVDetail\CVDetailServiceImpl;
use App\Services\Interview\InterviewService;
use App\Services\Interview\InterviewServiceImpl;
use App\Services\Mail\MailService;
use App\Services\Mail\MailServiceImpl;
use App\Services\Project\ProjectService;
use App\Services\Project\ProjectServiceImpl;
use App\Services\RNews\NewsService;
use App\Services\RNews\NewsServiceImpl;
use App\Services\RProcess\ProcessService;
use App\Services\RProcess\ProcessServiceImpl;
use App\Services\User\UserService;
use App\Services\User\UserServiceImpl;
use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserService::class, UserServiceImpl::class);
        $this->app->bind(CandidateService::class, CandidateServiceImpl::class);
        $this->app->bind(CompanyService::class, CompanyServiceImpl::class);
        $this->app->bind(MailService::class, MailServiceImpl::class);
        $this->app->bind(CVService::class, CVServiceImpl::class);
        $this->app->bind(CVDetailService::class, CVDetailServiceImpl::class);
        $this->app->bind(BlogService::class, BlogServiceImpl::class);
        $this->app->bind(CommentService::class, CommentServiceImpl::class);
        $this->app->bind(ProjectService::class, ProjectServiceImpl::class);
        $this->app->bind(NewsService::class, NewsServiceImpl::class);
        $this->app->bind(ProcessService::class, ProcessServiceImpl::class);
        $this->app->bind(InterviewService::class, InterviewServiceImpl::class);
    }
}
