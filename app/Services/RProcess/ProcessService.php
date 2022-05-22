<?php

namespace App\Services\RProcess;

use App\Models\User;

interface ProcessService
{
    /**
     * @param User $user
     * @param int $project_id
     * @return mixed
     */
    public function showAll(User $user, int $project_id);
    

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @param int $process_id
     * @return mixed
     */
    public function update(User $user, array $data, int $process_id);

    /**
     * @param User $user
     * @param int $process_id
     *
     * @return mixed
     */
    public function findById(User $user, int $process_id);

    /**
     * @param User $user
     * @param int $process_id
     *
     * @return bool
     */
    public function delete(User $user, int $process_id): bool;

    /**
     * @param User $user
     * @param int $process_id
     *
     * @return mixed
     */
    public function changeStatus(User $user, int $process_id);
}
