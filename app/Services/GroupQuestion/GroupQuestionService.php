<?php

namespace App\Services\GroupQuestion;

use App\Models\User;

interface GroupQuestionService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function getAll(User $user);

    /**
     * group question only show for company
     * 
     * @param User $user
     * @return mixed
     */
    public function getGroupInterview(User $user);
    
    /**
     * @param User $user
     * @param array $data
     * @param $group_id
     * @return mixed
     */
    public function store(User $user, array $data, $group_id);

    /**
     * @param User $user
     * @param int $group_id
     * @return mixed
     */
    public function findById(User $user, int $group_id);
    
    /**
     * @param User $user
     * @param array $data
     * @param $group_id
     * @return mixed
     */
    public function createRandom(User $user, array $data, $group_id);
    
    /**
     * @param User $user
     * @param int $group_id
     * @return mixed
     */
    public function delete(User $user, int $group_id);
}
