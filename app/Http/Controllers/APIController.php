<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\RefreshToken;

class APIController extends Controller
{
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('auth_token')->plainTextToken;
        $refreshToken = Str::random(64);

        // Store refresh token
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $refreshToken,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'refresh_token' => $refreshToken,
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $token = RefreshToken::where('token', $request->refresh_token)
                             ->where('expires_at', '>', Carbon::now())
                             ->first();

        if (!$token) {
            return response()->json([
                'message' => 'Invalid or expired refresh token.',
            ], 401);
        }

        $user = $token->user;
        $newAccessToken = $user->createToken('auth_token')->plainTextToken;
        $newRefreshToken = Str::random(64);

        // Invalidate the old refresh token
        $token->delete();

        // Store new refresh token
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $newRefreshToken,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        return response()->json([
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'refresh_token' => $newRefreshToken,
        ]);
    }

    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        RefreshToken::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
