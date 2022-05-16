<?php

namespace App\Services\User;

use App\Models\User;

interface UserService
{
    /**
     * @param array $login
     * @param bool $remember
     * @return mixed
     */
    public function login(array $login, bool $remember = false);
    
    /**
     * @param array $inputUser
     * @return mixed
     */
    public function register(array $inputUser);

    /**
     * @param User $user
     * @param array &$updateUser
     * @return mixed
     */
    public function update(User $user, array &$updateUser);
    
    /**
     * @param string $email
     * @return bool
     */
    public function forgotPassword(string $email): bool; 
    
    
    /**
     * @param string $email
     * @param string $password
     * @param string $token
     * @return bool
     */
    public function resetPassword(string $email, string $password, string $token): bool;
    
    /**
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function changePassword(User $user, array $data): bool;
    
}
