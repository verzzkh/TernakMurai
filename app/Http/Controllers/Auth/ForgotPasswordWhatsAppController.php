<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordWhatsAppRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ForgotPasswordWhatsAppController extends Controller
{
    public function showRequestForm(): View
    {
        return view('auth.passwords.whatsapp-request');
    }

    public function submitRequest(ForgotPasswordWhatsAppRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $login = (string) $data['login'];
        $nomorHandphone = (string) $data['nomor_handphone'];

        $loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        /** @var \App\Models\User|null $user */
        try {
            $user = User::where($loginField, $login)
                ->whereHas('peternak', function ($query) use ($nomorHandphone): void {
                    $query->where('nomor_handphone', $nomorHandphone);
                })
                ->with('peternak')
                ->first();
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Lupa Password WhatsApp Controller Error: ' . $e->getMessage());
            return back()
                ->withErrors([
                    'login' => 'Sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.',
                ])
                ->withInput();
        }

        if (! $user || ! $user->peternak) {
            return back()
                ->withErrors([
                    'login' => 'Data akun tidak ditemukan atau kombinasi nama/nomor WhatsApp tidak cocok.',
                ])
                ->withInput();
        }

        $request->session()->put('forgot_whatsapp', [
            'user_id' => $user->id,
            'name' => $user->name,
            'login' => $login,
            'nomor_handphone' => $user->peternak->nomor_handphone,
        ]);

        return redirect()->route('password.whatsapp.confirm');
    }

    public function showConfirmation(Request $request): RedirectResponse|View
    {
        $data = $request->session()->get('forgot_whatsapp');

        if (! is_array($data) || ! isset($data['user_id'])) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Sesi permintaan lupa password tidak ditemukan. Silakan ulangi proses.',
                ]);
        }

        $adminNumber = (string) (config('services.admin_whatsapp') ?? '');

        $message = "Halo Admin Ternak Murai,\n\n".
            "Saya ingin melakukan reset password akun.\n\n".
            "Nama: {$data['name']}\n".
            "Login: {$data['login']}\n".
            "No HP terdaftar: {$data['nomor_handphone']}\n\n".
            "Mohon bantuannya untuk mengirimkan link ganti password.\n\n".
            'Terima kasih.';

        $whatsappUrl = $adminNumber !== ''
            ? 'https://wa.me/'.$adminNumber.'?text='.urlencode($message)
            : null;

        return view('auth.passwords.whatsapp-confirm', [
            'data' => $data,
            'whatsappUrl' => $whatsappUrl,
            'adminNumber' => $adminNumber,
            'message' => $message,
        ]);
    }
}
