<?php

namespace App\Services\CVDetail;

use App\Models\CV;
use App\Models\User;

interface CVDetailService
{
    /**
     * @param User $user
     * @param int $cvId
     * @param array $data
     * @return mixed
     */
    public function store(User $user, int $cvId, array $data);

    /**
     * @param User $user
     * @param int $cvId
     *
     * @return mixed
     */
    public function delete(User $user, int $cvId);
}
