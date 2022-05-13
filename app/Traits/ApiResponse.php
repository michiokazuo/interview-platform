<?php

namespace App\Traits;

use App\Http\Resources\User\UserTokenResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * @param string $message
     * @param User $user
     * @param $data
     * @return JsonResponse
     */
    function successfulResult(string $message, User $user, $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'user' => new UserTokenResource($user),
            'data' => $data,
        ]);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    function failedResult(string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'errors' => [
                'message' => $message
            ]
        ], $statusCode);
    }
}
