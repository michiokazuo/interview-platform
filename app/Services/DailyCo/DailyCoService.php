<?php

namespace App\Services\DailyCo;

use App\Models\User;

interface DailyCoService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function getAll(User $user);
    
    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function create(User $user, array $data);

    /**
     * @param User $user
     * @param mixed $room_name
     * 
     * @return mixed
     */
    public function delete(User $user, $room_name);
}
