<?php

namespace App\Services\Admin;

use App\Models\CV;
use App\Models\User;
use App\Services\Candidate\CandidateService;
use App\Services\Company\CompanyService;
use App\Services\CVDetail\CVDetailService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class AdminServiceImpl implements AdminService
{
    protected $_repository, $candidateService, $companyService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(CandidateService $candidateService, CompanyService $companyService)
    {
        $this->_repository = app()->make(User::class);
        $this->candidateService = $candidateService;
        $this->companyService = $companyService;
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
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
