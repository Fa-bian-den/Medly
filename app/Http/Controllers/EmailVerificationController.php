<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VerifyCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;

class EmailVerificationController extends Controller
{
    protected int $codeLength = 6;
    protected int $expiresMinutes = 10;
    protected int $maxAttempts = 5;
    protected int $maxRequestsPerHour = 6;

    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = strtolower($request->email);
        $key = 'send-code|' . $email;

        if (RateLimiter::tooManyAttempts($key, $this->maxRequestsPerHour)) {
            return response()->json(['message' => 'Demasiadas solicitudes. Intenta más tarde.'], 429);
        }
        RateLimiter::hit($key, 3600);

        // Ajusta si no quieres crear usuarios automáticamente
        $user = User::firstOrCreate(
            ['email' => $email],
            ['first_name' => null, 'last_name' => null, 'password' => bcrypt(Str::random(32)), 'status' => 'active']
        );

        // Reusar código no usado y no expirado si existe
        $existing = EmailVerificationCode::where('user_id', $user->id)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if ($existing) {
            // opcional: extender expiración
            $existing->update(['expires_at' => Carbon::now()->addMinutes($this->expiresMinutes)]);
            Mail::to($user->email)->queue(new VerifyCodeMail($existing->code, $this->expiresMinutes));
            return response()->json(['message' => 'Código reenviado. Revisa tu correo.'], 200);
        }

        // Generar código numérico fijo, almacenar en claro (según tu migración actual)
        $plainCode = str_pad((string) random_int(0, (int) pow(10, $this->codeLength) - 1), $this->codeLength, '0', STR_PAD_LEFT);

        EmailVerificationCode::create([
            'user_id'    => $user->id,
            'code'       => $plainCode,
            'expires_at' => Carbon::now()->addMinutes($this->expiresMinutes),
            'used'       => false,
            'attempts'   => 0,
        ]);

        Mail::to($user->email)->queue(new VerifyCodeMail($plainCode, $this->expiresMinutes));

        return response()->json(['message' => 'Código enviado. Revisa tu correo.'], 200);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['email' => 'required|email', 'code' => 'required|string']);
        $email = strtolower($request->email);

        $user = User::where('email', $email)->first();
        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $evc = EmailVerificationCode::where('user_id', $user->id)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (! $evc) {
            return response()->json(['message' => 'Código inválido o expirado.'], 422);
        }

        if ($evc->attempts >= $this->maxAttempts) {
            return response()->json(['message' => 'Máximo de intentos alcanzado. Solicita un nuevo código.'], 429);
        }

        if (! hash_equals((string) $evc->code, (string) $request->code)) {
            $evc->increment('attempts');
            return response()->json(['message' => 'Código incorrecto.'], 422);
        }

        // Verificado: marcar usado y emitir token (ejemplo con Sanctum)
        $evc->update(['used' => true]);

        $token = $user->createToken('login-token')->plainTextToken;

        return response()->json([
            'message' => 'Verificación correcta.',
            'token'   => $token,
            'user'    => $user->only('id','email','first_name','last_name'),
        ], 200);
    }
}