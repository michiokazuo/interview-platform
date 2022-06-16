<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CrawlerRequest;
use App\Http\Resources\User\UserCollection;
use App\Services\Admin\AdminService;
use App\Services\SettingCrawler\SettingCrawlerService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    use ApiResponse, CurrentUser;

    private $adminService, $settingCrawlerService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(AdminService $adminService, SettingCrawlerService $settingCrawlerService)
    {
        $this->adminService = $adminService;
        $this->settingCrawlerService = $settingCrawlerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 10);

        $user = $this->user();
        $userList = $this->adminService->getAll($user, $per_page);

        if ($userList) {
            $userList['data'] = !empty($userList['data']) ? new UserCollection($userList['data']) : [];
            return $this->successfulResult('Users display successfully!!!', $user, $userList);
        }

        return $this->failedResult('Users display failed!!!', 500);
    }

    /**
     * Active company.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function activeCompany(int $id): JsonResponse
    {
        $user = $this->user();

        $active = $this->adminService->activeCompany($user, $id);

        if ($active) {
            return $this->successfulResult('Active Company successfully!!!', $user, $active);
        }
        return $this->failedResult('Failed active!!!', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->user();
        $delete = $this->adminService->deleteUser($user, $id);

        if ($delete) {
            return $this->successfulResult('User deleted successfully!!!', $user, ['id' => $id]);
        }

        return $this->failedResult('User deleted failed!!!', 500);
    }

    /**
     * Crawler data.
     * @param CrawlerRequest $request
     *
     * @return JsonResponse
     */
    public function crawler(CrawlerRequest $request): JsonResponse
    {
        $user = $this->user();
        
        $tags = $request->input('tags');
        $numbers = $request->input('numbers');

        $crawler = $this->settingCrawlerService->crawler($user, $tags, $numbers);

        if ($crawler) {
            return $this->successfulResult('Crawl successfully!!!', $user, ['status' => true]);
        }

        return $this->failedResult('Crawl failed!!!', 500);
    }
}
