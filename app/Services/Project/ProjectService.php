<?php

namespace App\Services\Project;

use App\Models\User;

interface ProjectService
{
    /**
     * @param User $user
     * @param int $per_page
     * @return mixed
     */
    public function showAll(User $user, int $per_page);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @param int $project_id
     * @return mixed
     */
    public function update(User $user, array $data, int $project_id);

    /**
     * @param User $user
     * @param int $project_id
     *
     * @return mixed
     */
    public function findById(User $user, int $project_id);

    /**
     * @param User $user
     * @param int $project_id
     *
     * @return bool
     */
    public function delete(User $user, int $project_id): bool;
    
    /**
     * @param User $user
     * @param int $project_id
     *
     * @return mixed
     */
    public function changeStatus(User $user, int $project_id);
}
