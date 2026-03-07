<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends BaseApiController
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return $this->response(true, 'Settings fetched successfully', $settings);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return $this->response(true, 'Settings updated successfully');
    }
}
