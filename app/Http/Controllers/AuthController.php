<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 *
 * Controller ini menangani seluruh proses autentikasi pengguna,
 * termasuk login, registrasi, dan logout.
 *
 * Fitur utama:
 * - Menampilkan halaman login dan register
 * - Memproses autentikasi pengguna (login)
 * - Membuat akun baru (register)
 * - Logout dan manajemen sesi pengguna
 */
class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     *
     * Jika pengguna sudah login, maka akan diarahkan ke halaman utama.
     *
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Jika user sudah login, arahkan ke halaman utama
            return redirect()->intended('/');
        }

        // Jika belum login, tampilkan view login
        return view('auth.login');
    }

    /**
     * Proses login pengguna.
     *
     * Validasi input:
     * - `email`: wajib, format email
     * - `password`: wajib
     *
     * Langkah:
     * 1. Validasi input.
     * 2. Ambil kredensial dan periksa dengan `Auth::attempt()`.
     * 3. Regenerasi session untuk mencegah session fixation attack.
     * 4. Redirect sesuai role pengguna:
     *    - Admin → /admin
     *    - User biasa → /
     *
     * Jika gagal login, kembalikan ke halaman sebelumnya dengan pesan error.
     *
     */
    public function login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Ambil hanya field email dan password dari request
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Coba autentikasi user menggunakan Auth facade
        if (Auth::attempt($credentials, $remember)) {
            // Regenerasi session ID untuk keamanan
            $request->session()->regenerate();

            // Redirect berdasarkan role user
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin!');
            }

            return redirect()->intended('/')
                ->with('success', 'Welcome back!');
        }

        // Jika login gagal, kirim pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Tampilkan halaman registrasi.
     *
     * Jika pengguna sudah login, diarahkan ke halaman utama.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     *
     * Validasi input:
     * - `name`: wajib, string, maksimal 255 karakter
     * - `email`: wajib, email valid, unik di tabel users
     * - `password`: wajib, minimal 8 karakter, harus dikonfirmasi
     *
     * Langkah:
     * 1. Validasi input form.
     * 2. Buat user baru dengan password yang di-hash menggunakan `Hash::make()`.
     * 3. Set role default sebagai `user`.
     * 4. Login otomatis setelah pendaftaran berhasil.
     * 5. Redirect ke halaman utama dengan pesan sukses.
     *
     */
    public function register(Request $request)
    {
        // Validasi input pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat user baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Role default
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        return redirect('/')
            ->with('success', 'Account created successfully!');
    }

    /**
     * Logout pengguna dari sistem.
     *
     * Langkah:
     * 1. Logout dengan `Auth::logout()`.
     * 2. Hapus sesi (invalidate).
     * 3. Regenerasi token CSRF untuk keamanan.
     * 4. Redirect ke halaman login dengan pesan sukses.
     *
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session dan regenerasi token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
