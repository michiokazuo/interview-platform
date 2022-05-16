<?php

namespace App\Providers;

use App\Services\Candidate\CandidateService;
use App\Services\Candidate\CandidateServiceImpl;
use App\Services\Company\CompanyService;
use App\Services\Company\CompanyServiceImpl;
use App\Services\CV\CVService;
use App\Services\CV\CVServiceImpl;
use App\Services\CVDetail\CVDetailService;
use App\Services\CVDetail\CVDetailServiceImpl;
use App\Services\Mail\MailService;
use App\Services\Mail\MailServiceImpl;
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
    }
}
