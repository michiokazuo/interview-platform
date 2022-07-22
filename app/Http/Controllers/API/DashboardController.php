<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Services\Admin\AdminService;
use App\Services\Mail\MailService;
use App\Services\Setting\SettingService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse, CurrentUser;

    private $dashboardService, $adminService, $mailService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(SettingService $dashboardService, AdminService $adminService,
                                MailService    $mailService)
    {
        $this->dashboardService = $dashboardService;
        $this->adminService = $adminService;
        $this->mailService = $mailService;
    }

    /**
     * Display dashboard of candidate.
     *
     * @return JsonResponse
     */
    public function dashboardCandidate(): JsonResponse
    {
        $user = $this->user();
        $dashboard = $this->dashboardService->dashboardCandidate($user, []);

        if ($dashboard) {
            return $this->successfulResult('$dashboard display successfully!!!', $user, $dashboard);
        }

        return $this->failedResult('$dashboard display failed!!!', 500);
    }

    /**
     * Display dashboard of company.
     *
     * @return JsonResponse
     */
    public function dashboardCompany(): JsonResponse
    {
        $user = $this->user();
        $dashboard = $this->dashboardService->dashboardCompany($user, []);

        if ($dashboard) {
            return $this->successfulResult('$dashboard display successfully!!!', $user, $dashboard);
        }

        return $this->failedResult('$dashboard display failed!!!', 500);
    }

    /**
     * Display dashboard of admin.
     *
     * @return JsonResponse
     */
    public function dashboardAdmin(): JsonResponse
    {
        $user = $this->user();
        $dashboard = $this->dashboardService->dashboardAdmin($user, []);

        if ($dashboard) {
            return $this->successfulResult('$dashboard display successfully!!!', $user, $dashboard);
        }

        return $this->failedResult('$dashboard display failed!!!', 500);
    }

    /**
     * Display graph of admin.
     *
     * @return JsonResponse
     */
    public function graphAdmin(): JsonResponse
    {
        $user = $this->user();
        $data = request()->all();
        $graph = $this->adminService->graphInterview($user, $data);

        if ($graph) {
            return $this->successfulResult('$graph display successfully!!!', $user, $graph);
        }

        return $this->failedResult('$graph display failed!!!', 500);
    }

    /**
     * Notify user action.
     *
     * @return void
     */
    public function notifyUser()
    {
        $data = request()->all();
        $emails = request('emails');
        if ($emails) {
            $this->mailService->send($emails, NotifyMail::class, $data);
        } else {
            logger()->error('$emails is empty!!!');
        }
    }
}
