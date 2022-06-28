<?php

namespace App\Services\GroupQuestion;

use App\Models\User;

interface GroupQuestionService
{
    /**
     * @param User $user
     * @param array $data
     * @param $group_id
     * @return mixed
     */
    public function store(User $user, array $data, $group_id);
    
    /**
     * @param User $user
     * @param array $data
     * @param $group_id
     * @return mixed
     */
    public function createRandom(User $user, array $data, $group_id);
    
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function delete(User $user, array $data);
}
