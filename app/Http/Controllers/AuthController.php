<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function showRegister()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $users = User::all();
        return view('auth.register', compact('users'));
    }

    public function register(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,admin',
        ]);

        $password = "password123";
        $data['password'] = Hash::make($password);

        $user = User::create($data);

        
        Log::info("New user created: {$user->email}, Password: {$password}");

        return redirect()->route('register')->with('success', 'User created successfully. Password logged.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}