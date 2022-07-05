<?php

namespace App\Services\RNews;

use App\Models\User;

interface NewsService
{
    /**
     * @param User $user
     * @param array $data
     * @param int $per_page
     * @return mixed
     */
    public function showAll(User $user, array $data, int $per_page);

    /**
     * @param User $user
     * @param int $company_id
     * @param int $per_page
     * @return mixed
     */
    public function showAllByUser(User $user, int $company_id, int $per_page);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @param int $news_id
     * @return mixed
     */
    public function update(User $user, array $data, int $news_id);

    /**
     * @param User $user
     * @param int $news_id
     *
     * @return mixed
     */
    public function findById(User $user, int $news_id);

    /**
     * @param User $user
     * @param int $news_id
     *
     * @return bool
     */
    public function delete(User $user, int $news_id): bool;

    /**
     * @param User $user
     * @param int $news_id
     *
     * @return mixed
     */
    public function changeStatus(User $user, int $news_id);


    /**
     * @param User $user
     * @param int $project_id
     * @return mixed
     */
    public function showAllByProject(User $user, int $project_id);
    
    /**
     * @param User $user
     * @return mixed
     */
    public function getSelectOptions(User $user);
}
