<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CompanyServiceImpl implements CompanyService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(Company::class);
    }

    /**
     * @inheritDoc
     */
    public function store(array $inputCompany)
    {
        try {
            $urls = $this->_repository->where('url', $inputCompany['url'])->get();

            if (!empty($inputCompany['id'])) {
                $company = $this->_repository->findOrFail($inputCompany['id']) ?? [];
                if ($urls->count() == 1 && $company['id'] == $urls[0]['id']) {
                    $company->update([
                        'url' => $inputCompany['url'],
                    ]);

                    return $this->_repository->find($company->id);
                }
            } else if (!$urls->count()) {
                return $this->_repository->create([
                    'url' => $inputCompany['url'],
                    'is_active' => false,
                ]);
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
    public function activeCompany(User $user, int $company_id): bool
    {
        try {
            if ($user->role_id == 1) {
                $company = $this->_repository->findOrFail($company_id);
                if ($company) {
                    $company->update([
                        'is_active' => !$company->is_active,
                    ]);
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
    public function getAllInactiveCompany(User $user)
    {
        try {
            if ($user->role_id == 1) {
                return $this->_repository->where('is_active', false)->count();
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
