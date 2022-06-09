<?php

namespace App\Services\Interview;

use App\Models\User;

interface InterviewService
{
    /**
     * @param User $user
     * @param int $candidate_id
     * @return mixed
     */
    public function showAllByUser(User $user, int $candidate_id);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function store(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @param int $interview_id
     * @return mixed
     */
    public function update(User $user, array $data, int $interview_id);

    /**
     * @param User $user
     * @param int $interview_id
     *
     * @return mixed
     */
    public function findById(User $user, int $interview_id);

    /**
     * @param User $user
     * @param int $interview_id
     *
     * @return bool
     */
    public function delete(User $user, int $interview_id): bool;

    /**
     * @param User $user
     * @param int $news_id
     * @param int $per_page
     *
     * @return mixed
     */
    public function showAllByNews(User $user, int $news_id, int $per_page);

    /**
     * @param User $user
     * @param int $news_id
     *
     * @return mixed
     */
    public function findByNewsId(User $user, int $news_id);

    /**
     * @param User $user
     * @param int $interview_id
     * @param array $data
     *
     * @return mixed
     */
    public function createTest(User $user, int $interview_id, array $data);

    /**
     * @param User $user
     * @param int $interview_id
     * @param array $data
     * 
     * @return mixed
     */
    public function saveResultTest(User $user, int $interview_id, array $data);
}
