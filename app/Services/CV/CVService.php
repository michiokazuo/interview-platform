<?php

namespace App\Services\CV;

use App\Models\User;

interface CVService
{
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param mixed $id
     * 
     * @return mixed
     */
    public function findById(User $user, $id);
}
