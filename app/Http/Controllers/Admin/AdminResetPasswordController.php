<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminResetPasswordController extends Controller
{
    public function index(Request $request): View
    {
        $search = (string) $request->query('q', '');

        $users = collect();

        if ($search !== '') {
            $users = User::with('peternak')
                ->where(function ($query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhereHas('peternak', function ($q) use ($search): void {
                            $q->where('nama_peternakan', 'like', '%'.$search.'%')
                                ->orWhere('nomor_handphone', 'like', '%'.$search.'%');
                        });
                })
                ->orderBy('name')
                ->limit(20)
                ->get();
        }

        return view('admin.auth.reset-password.index', [
            'search' => $search,
            'users' => $users,
        ]);
    }

    public function generate(Request $request, User $user): RedirectResponse
    {
        $token = Password::broker()->createToken($user);

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        $request->session()->flash('reset_link_generated_for', $user->id);
        $request->session()->flash('reset_link_url', $resetUrl);

        return redirect()->route('admin.reset-password.index', ['q' => $request->query('q')]);
    }
}
