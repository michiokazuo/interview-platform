<?php

namespace App\Services\Company;

use App\Models\User;

interface CompanyService
{
    /**
     * @param array $inputCompany
     * @return mixed
     */
    public function store(array $inputCompany);

    /**
     * @param User $user
     * @param int $company_id
     *
     * @return bool
     */
    public function activeCompany(User $user, int $company_id): bool;
    
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getAllInactiveCompany(User $user);
}
