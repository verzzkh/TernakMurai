<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Cache::get('admin.settings', [
            'app_name' => config('app.name'),
            'admin_email' => config('mail.from.address'),
            'openai_key' => env('OPENAI_API_KEY', ''),
        ]);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name' => ['required', 'string', 'max:150'],
            'admin_email' => ['required', 'email'],
            'openai_key' => ['nullable', 'string'],
        ]);

        Cache::put('admin.settings', $data, now()->addDays(365));

        return back()->with('success', 'Settings saved to cache (development only).');
    }
}
