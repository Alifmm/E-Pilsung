<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Web-based methods
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin');
        } else {
            if (Hash::check('00000000', $user->password)) {
                return redirect()->route('confirmpassword');
            }
            return redirect()->route('vote.page');
        }
    }

    public function showConfirmPassword()
    {
        $user = Auth::user();
        if (!Hash::check('00000000', $user->password)) {
            return redirect()->route('vote.page');
        }

        return view('user.confirmpassword');
    }

    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $errors = [];

        if (!preg_match('/[A-Z]/', $request->password)) {
            $errors[] = 'The password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $request->password)) {
            $errors[] = 'The password must contain at least one lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $request->password)) {
            $errors[] = 'The password must contain at least one number.';
        }
        if (!preg_match('/[@$!%*?&.]/', $request->password)) {
            $errors[] = 'The password must contain at least one special character.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('vote.page')->with('success', 'Password has been updated.');
    }

}
