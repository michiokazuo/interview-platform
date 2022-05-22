<?php

namespace App\Services\Blog;

use App\Models\User;

interface BlogService
{
    /**
     * @param User $user
     * @param int $per_page
     * @return mixed
     */
    public function showAll(User $user, int $per_page);

    /**
     * @param User $user
     * @param int $user_id
     * @param int $per_page
     * @return mixed
     */
    public function showAllByUser(User $user, int $user_id, int $per_page);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @param int $blog_id
     * @return mixed
     */
    public function update(User $user, array $data, int $blog_id);

    /**
     * @param User $user
     * @param int $blog_id
     *
     * @return mixed
     */
    public function findById(User $user, int $blog_id);

    /**
     * @param User $user
     * @param int $blog_id
     *
     * @return bool
     */
    public function delete(User $user, int $blog_id): bool;
}
