<?php

namespace App\Services\Comment;

use App\Models\User;

interface CommentService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function showAllByUser(User $user);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @param int $cmt_id
     * @return mixed
     */
    public function update(User $user, array $data, int $cmt_id);

    /**
     * @param User $user
     * @param int $cmt_id
     *
     * @return mixed
     */
    public function findById(User $user, int $cmt_id);

    /**
     * @param User $user
     * @param int $cmt_id
     *
     * @return bool
     */
    public function delete(User $user, int $cmt_id): bool;
}
