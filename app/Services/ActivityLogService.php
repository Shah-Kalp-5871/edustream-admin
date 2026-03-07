<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Log an admin activity.
     *
     * @param string $action
     * @param string|null $modelType
     * @param int|null $modelId
     * @param array $payload
     * @return ActivityLog
     */
    public static function log($action, $modelType = null, $modelId = null, $payload = [])
    {
        $request = request();

        return ActivityLog::create([
            'admin_id' => Auth::guard('web')->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'payload' => $payload,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
