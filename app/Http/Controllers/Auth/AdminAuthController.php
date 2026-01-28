<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.login_admin');
    }

    /**
     * Handle admin login authentication using external API.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        try {
            // Call external API
            $response = Http::post(Config::get('api.base_url') . 'auth/login', [
                'username' => $request->email,
                'password' => $request->password,
            ]);

            if (!$response->successful()) {
                throw ValidationException::withMessages([
                    'email' => 'Email atau password salah',
                ]);
            }

            $data = $response->json();

            if (!$data['success']) {
                throw ValidationException::withMessages([
                    'email' => $data['message'] ?? 'Login gagal',
                ]);
            }

            // Store tokens in session
            session([
                'access_token' => $data['data']['access_token'],
                'refresh_token' => $data['data']['refresh_token'],
                'user' => $data['data']['user'],
                'logged_in' => true
            ]);

            // Check user role with /auth/me endpoint
            $profileResponse = Http::withToken($data['data']['access_token'])
                ->get(Config::get('api.base_url') . 'auth/me');

            if ($profileResponse->successful()) {
                $profile = $profileResponse->json();
                $userRoles = $profile['user']['role'] ?? [];
                $userName = $profile['user']['name'] ?? 'User';
                
                if (!in_array('admin', $userRoles)) {
                    // Clear session and show specific error
                    session()->forget(['access_token', 'refresh_token', 'user', 'logged_in']);
                    
                    // Create specific error message based on role
                    $roleText = implode(', ', $userRoles);
                    $errorMessage = "Akses ditolak. User '{$userName}' memiliki role '{$roleText}', bukan 'admin'. Hanya user dengan role admin yang dapat masuk ke panel ini.";
                    
                    // Log untuk debugging
                    Log::info('Admin login rejected: ' . $errorMessage);
                    
                    // Redirect dengan error message
                    return redirect()->route('login.admin')
                        ->with('error', $errorMessage)
                        ->withInput();
                }
            } else {
                // Failed to validate profile
                session()->forget(['access_token', 'refresh_token', 'user', 'logged_in']);
                throw ValidationException::withMessages([
                    'email' => 'Gagal memvalidasi profil user. Silakan coba kembali.',
                ]);
            }

            // Regenerate session for security
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.index'));

        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'email' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
            ]);
        }
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        // Clear session
        session()->forget(['access_token', 'refresh_token', 'user', 'logged_in']);
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
