<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo_acceso' => ['required', 'string'],
            'selected_role' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = \App\Models\User::where('codigo_acceso', $this->codigo_acceso)->first();

        if (! $user || ! $user->activo) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'codigo_acceso' => trans('auth.failed'),
            ]);
        }

        // Validate role matches selected role
        $roleName = $user->rol->nombre ?? '';
        $expectedRole = match($this->selected_role) {
            'Operador' => 'Operador',
            'Agente TI' => 'Agente TI',
            'Administrador' => 'Admin',
            default => null
        };

        if ($expectedRole && $roleName !== $expectedRole) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'codigo_acceso' => 'El código de acceso no corresponde al perfil seleccionado.',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'codigo_acceso' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('codigo_acceso')).'|'.$this->ip());
    }
}
