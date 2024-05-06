<?php

namespace App\Http\Controllers;

use App\Mail\EmailAddressConfirmation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        do {
            $emailVerificationCode = hash("sha512", rand());
            $codeAlreadyExists = User::where('email_verification_code', $emailVerificationCode)->exists();
        } while ($codeAlreadyExists);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->is_admin = $request->input('is_admin') ?? false;
        $user->email_verification_code = $emailVerificationCode;
        $user->save();

        Mail::to($user->email)->send(new EmailAddressConfirmation($user));

        return response()->json([
            'name' => $user->name,
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'name' => $user->name,
            'isAdmin' => $user->isAdmin,
            'userId' => $user->id
        ]);
    }

    public function verifyEmail(string $code)
    {
        $user = User::where('email_verification_code', $code)->first();
        if (isNull($user)) {
            return "Nigga";
        }
        $user->email_verification_code = null;
        $user->email_verified_at = time();
        $user->save();

        return 'Verified';
    }

    public function showEmailVerificationView()
    {
        return view('emails.emailAddressConfirmation', ['user' => User::first()]);
    }
}
