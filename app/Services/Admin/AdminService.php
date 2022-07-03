<?php

namespace App\Services\Admin;

use App\Models\User;

interface AdminService
{
    /**
     * @param User $user
     * @param int $per_page
     * @return mixed
     */
    public function getAll(User $user, int $per_page = 10);
    
    /**
     * @param User $user
     * @param int $companyId
     * @return mixed
     */
    public function activeCompany(User $user, int $companyId);

    /**
     * @param User $user
     * @param mixed $id
     * 
     * @return mixed
     */
    public function deleteUser(User $user, $id);
    
    /**
     * @param User $user
     * 
     * @return mixed
     */
    public function graph7Days(User $user);
    
    /**
     * @param User $user
     * @param array $data
     * 
     * @return mixed
     */
    public function graphInterview(User $user, array $data);
}
