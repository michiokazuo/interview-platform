<?php

namespace App\Services\QuestionAnswerTag;

use App\Models\User;

interface QATService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function getAllTag(User $user);

    /**
     * @param User $user
     * @param mixed $data
     * @param int $per_page
     * @return mixed
     */
    public function getQuestions(User $user, $data, int $per_page);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function createRandom(User $user, array $data);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function createQA(User $user, array $data);
    
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function delete(User $user, array $data);
}
