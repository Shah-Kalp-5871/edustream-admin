<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Standard JSON response formatter
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function response($success, $message, $data = [], $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
