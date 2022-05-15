<?php

namespace App\Services\User;

use App\Mail\ForgotPasswordMail;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Services\Candidate\CandidateService;
use App\Services\Company\CompanyService;
use App\Services\Mail\MailService;
use Exception;
use Hash;
use Illuminate\Contracts\Container\BindingResolutionException;

class UserServiceImpl implements UserService
{
    protected $_repository, $roleService, $passwordService;
    protected $companyService, $candidateService;
    protected $mailService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(CompanyService $companyService, CandidateService $candidateService,
                                MailService    $mailService)
    {
        $this->_repository = app()->make(User::class);
        $this->roleService = app()->make(Role::class);
        $this->passwordService = app()->make(PasswordReset::class);
        $this->companyService = $companyService;
        $this->candidateService = $candidateService;
        $this->mailService = $mailService;
    }

    /**
     * @inheritDoc
     */
    public function login(array $login, bool $remember = false)
    {
        try {
            auth()->attempt($login, $remember);
            if (!$token = auth('api')->attempt($login)) {
                return false;
            }
            
            $this->passwordService->where('email', $login['email'])->delete();
            return $token;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function register(array $inputUser)
    {
        try {
            $inputUser['role_id'] = $this->roleService->where('name', $inputUser['role_name'])->first()->id;
            $inputUser['social_network'] = json_encode($inputUser['social_network']);
            $existUser = $this->_repository->where('email', $inputUser['email'])
                ->orWhere('phone', $inputUser['phone'])->exists();

            if ($existUser) {
                return false;
            }

            if (isset($inputUser['avatar'])) {
                $time = strtotime("now");
                $path = "images/local/$time";
                $inputUser['avatar']->move(public_path($path), $inputUser['avatar']->getClientOriginalName());
                $inputUser['avatar'] = $path . '/' . $inputUser['avatar']->getClientOriginalName();
            } else {
                $inputUser['avatar'] = 'images/local/avatar-user.png';
            }

            $userOwner = null;
            if ($inputUser['role_name'] == "ROLE_CANDIDATE") {
                $userOwner = $this->candidateService->store($inputUser);
                $inputUser['candidate_id'] = $userOwner ? $userOwner->id : null;
            } else if ($inputUser['role_name'] == "ROLE_COMPANY") {
                $userOwner = $this->companyService->store($inputUser);
                $inputUser['company_id'] = $userOwner ? $userOwner->id : null;
            }

            if ($userOwner) {
                $inputUser['password'] = bcrypt($inputUser['password']);
                return $this->_repository->create($inputUser);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(User $user, array &$updateUser)
    {
        try {
            $updateUser['role_id'] = $user->role_id;
            $updateUser['candidate_id'] = $user->candidate_id;
            $updateUser['company_id'] = $user->company_id;
            $updateUser['social_network'] = json_encode($updateUser['social_network']);
            $existUser = $this->_repository->where('email', $updateUser['email'])
                ->orWhere('phone', $updateUser['phone'])->get();

            if ($existUser && ($existUser->count() > 1
                    || ($existUser->count() == 1 && $existUser[0]->id != $user['id']))) {
                return false;
            }

            if (isset($inputUser['avatar'])) {
                $time = strtotime("now");
                $path = "images/local/$time";
                $inputUser['avatar']->move(public_path($path), $inputUser['avatar']->getClientOriginalName());
                $inputUser['avatar'] = $path . '/' . $inputUser['avatar']->getClientOriginalName();
            } else {
                $inputUser['avatar'] = $user->avatar;
            }

            $userOwner = null;
            $role = $user->role();
            if ($role['name'] == "ROLE_CANDIDATE") {
                $userOwner = $user->candidate();
                $userOwner['candidate_id'] = $userOwner ? $userOwner->id : null;
            } else if ($role['name'] == "ROLE_COMPANY") {
                $updateCompany = [
                    'id' => $user->company_id,
                    'url' => $updateUser['url'],
                ];
                $userOwner = $this->companyService->store($updateCompany);
                $updateUser['company_id'] = $userOwner ? $userOwner->id : null;
            }

            if ($userOwner) {
                $updateUser['password'] = bcrypt($updateUser['password']);
                return $this->_repository->find($user['id'])->update($updateUser);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function forgotPassword(string $email): bool
    {
        try {
            $user = $this->_repository->where('email', $email)->first();
            if ($user) {
                $checkReset = $this->passwordService->where('email', $email)->first();
                $token = Hash::make($email . $user->password . time());

                if ($checkReset) {
                    $this->passwordService->where('email', $email)->update(['token' => $token]);
                } else {
                    $this->passwordService->create([
                        'email' => $email,
                        'token' => $token
                    ]);
                }

                $this->mailService->send([$email], ForgotPasswordMail::class, [
                    'name' => $user->name,
                    'body' => 'This is mail to reset password. Follow on that link to reset your password.',
                    'note' => 'Note: Please do not public this link to anyone!!!',
                    'actionUrl' => env("APP_URL") . "/pages/authentication/reset-password-v1?token=" . $token,
                    'actionText' => 'Reset Password',
                ]);

                return true;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    public function resetPassword(string $email, string $password, string $token): bool
    {
        try {
            $checkReset = $this->passwordService->where([
                'email' => $email,
                'token' => $token,
            ])->first();

            if ($checkReset) {
                $user = $this->_repository->where('email', $email)->first();
                if ($user) {
                    $user->update(['password' => bcrypt($password)]);
                    $this->passwordService->where('email', $email)->delete();

                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
