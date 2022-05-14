<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\MailForgotPass;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Role;
use App\Services\User\UserService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    use ApiResponse, CurrentUser;

    private $userService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword', 'resetPassword']]);
    }

    /**
     * Login user and create token
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function login(ForgotPasswordRequest $request): JsonResponse
    {
        $login = $request->only(['email', 'password']);
        $remember = $request->input('remember');

        $token = $this->userService->login($login, $remember ?? false);

        if (!$token) {
            return $this->failedResult('Failed to login', 401);
        }

        return $this->successfulResult('Login successfully!!!', $this->user(), ["accessToken" => $token]);
    }

    /**
     * Register a User.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function register(StoreRequest $request): JsonResponse
    {
        $dataUser = $request->all();

        $user = $this->userService->register($dataUser);

        if ($user) {
            $token = auth('api')->attempt([
                'email' => $dataUser['email'],
                'password' => $dataUser['password']
            ]);
            auth()->login(auth('api')->user());

            return $this->successfulResult('Register successfully!!!', $this->user(), ["accessToken" => $token]);
        }
        return $this->failedResult('Failed register', 500);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return Application|Redirector|RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        auth('api')->logout();

        return redirect('/');
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userProfile(): JsonResponse
    {
        $user = $this->user();
        return $this->successfulResult('Register successfully!!!', $user, $user);
    }


    /**
     * Update the authenticated User.
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function updateUser(UpdateRequest $request): JsonResponse
    {
        $dataUser = $request->all();

        $user = $this->userService->update($this->user(), $dataUser);

        if ($user) {
            return $this->successfulResult('Update successfully!!!', $user, $user);
        }
        return $this->failedResult('Failed update', 500);
    }

    /**
     * Send link forgot password.
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->input('email');

        $forgot = $this->userService->forgotPassword($email);

        if ($forgot) {
            return $this->successfulResultWithoutAuth('Send link forgot password successfully!!!', []);
        }
        return $this->failedResult('Failed send link forgot password', 500);
    }

    /**
     * Reset password.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $token = $request->input('token');

        $reset = $this->userService->resetPassword($email, $password, $token);

        if ($reset) {
            return $this->successfulResultWithoutAuth('Reset password successfully!!!', []);
        }
        return $this->failedResult('Failed reset password', 500);
    }

}
