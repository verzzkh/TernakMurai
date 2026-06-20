<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Throwable;

class ForgotPasswordWhatsAppRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('nomor_handphone')) {
            $this->merge([
                'nomor_handphone' => preg_replace('/[^0-9]/', '', (string) $this->nomor_handphone),
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'nomor_handphone' => ['required', 'string', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Mohon isi email atau nama akun.',
            'nomor_handphone.required' => 'Mohon isi nomor WhatsApp yang terdaftar.',
            'nomor_handphone.numeric' => 'Nomor WhatsApp hanya boleh berisi angka.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->fails()) {
                return;
            }

            try {
                $login = (string) $this->input('login');
                $nomorHandphone = (string) $this->input('nomor_handphone');

                $loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
                
                $user = User::where($loginField, $login)
                    ->whereHas('peternak', function ($query) use ($nomorHandphone): void {
                        $query->where('nomor_handphone', $nomorHandphone);
                    })
                    ->first();

                if (! $user) {
                    $validator->errors()->add(
                        'login',
                        'Data akun tidak ditemukan atau kombinasi nama/nomor WhatsApp tidak cocok.'
                    );
                }
            } catch (Throwable $e) {
                // Log exception for debugging internally
                \Illuminate\Support\Facades\Log::error('Lupa Password WhatsApp Error: ' . $e->getMessage());
                
                $validator->errors()->add(
                    'login',
                    'Sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.'
                );
            }
        });
    }
}
