<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return Application|RedirectResponse|Redirector|JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $login = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($login)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $url = "/";
        $role = auth()->user()->role ?? null;
        if ($role == "ROLE_ADMIN") {
            $url = "/admin";
        } else if ($role == "ROLE_CANDIDATE") {
            $url = "/candidate";
        } else if ($role == "ROLE_COMPANY") {
            $url = "/company";
        }

        return redirect("$url?accessToken=$token");
    }

    /**
     * Register a User.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(StoreRequest $request): JsonResponse
    {
        $dataUser = $request->all();
        
        $dataUser->role_id = Role::where('name', $dataUser->role_name)->first()->id;
        
        if($dataUser->role_name == "ROLE_CANDIDATE") {
            $candidate = Candidate::create();
        } else if($dataUser->role_name == "ROLE_COMPANY") {
            $company = Company::create([
                'url' => $dataUser->url,
            ]);
        }
        
        $user = User::create($dataUser);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }


    public function changePassWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $userId = auth()->user()->id;

        $user = User::where('id', $userId)->update(
            ['password' => bcrypt($request->new_password)]
        );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }
}
