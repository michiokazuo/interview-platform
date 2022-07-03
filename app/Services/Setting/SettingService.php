<?php

namespace App\Services\Setting;

use App\Models\User;

interface SettingService
{
    /**
     * @param User $user
     * @param array $tags
     * @param int $numbers
     * @return mixed
     */
    public function crawler(User $user, array $tags, int $numbers);
    
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function dashboardCandidate(User $user, array $data);
    
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function dashboardCompany(User $user, array $data);
    
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function dashboardAdmin(User $user, array $data);
}
