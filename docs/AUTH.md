# 📚 Dokumentasi Lengkap Sistem Autentikasi - Mayra D'Light

## 📋 Daftar Isi
1. [Arsitektur MVC](#arsitektur-mvc)
2. [Controller](#controller)
3. [Model](#model)
4. [View](#view)
5. [Routes](#routes)
6. [Middleware](#middleware)
7. [Database & Migration](#database--migration)
8. [Seeder](#seeder)
9. [Cara Kerja](#cara-kerja)
10. [Testing](#testing)

---

## 🏗️ Arsitektur MVC

Sistem autentikasi ini menggunakan pola MVC (Model-View-Controller) Laravel:

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ↓
┌─────────────┐     ┌──────────────┐
│   Routes    │────→│  Middleware  │
│  (web.php)  │     │ (Admin/User) │
└──────┬──────┘     └──────┬───────┘
       │                   │
       ↓                   ↓
┌─────────────┐     ┌──────────────┐
│ Controller  │────→│    Model     │
│(AuthCtrl)   │←────│    (User)    │
└──────┬──────┘     └──────┬───────┘
       │                   │
       ↓                   ↓
┌─────────────┐     ┌──────────────┐
│    View     │     │   Database   │
│ (Blade)     │     │  (SQLite)    │
└─────────────┘     └──────────────┘
```

---

## 🎮 Controller

### File: `app/Http/Controllers/AuthController.php`

Controller ini menangani semua logika autentikasi.

#### Struktur Controller:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Method untuk menampilkan form login
    public function showLoginForm()
    
    // Method untuk memproses login
    public function login(Request $request)
    
    // Method untuk menampilkan form register
    public function showRegisterForm()
    
    // Method untuk memproses registrasi
    public function register(Request $request)
    
    // Method untuk logout
    public function logout(Request $request)
}
```

### 📝 Method Detail:

#### 1. `showLoginForm()`
**Fungsi:** Menampilkan halaman login

```php
public function showLoginForm()
{
    // Cek apakah user sudah login
    if (Auth::check()) {
        return redirect()->intended('/');
    }
    
    // Menampilkan view login dari resources/views/auth/login.blade.php
    return view('auth.login');
}
```

**Penjelasan:**
- Menggunakan `Auth::check()` untuk cek apakah user sudah authenticated
- Jika sudah login, redirect ke homepage
- Jika belum, tampilkan form login dengan `view('auth.login')`
- **Ini menampilkan file `resources/views/auth/login.blade.php`**

---

#### 2. `login(Request $request)`
**Fungsi:** Memproses login user

```php
public function login(Request $request)
{
    // 1. VALIDASI INPUT
    $request->validate([
        'email' => 'required|email',      // Email wajib diisi dan format email
        'password' => 'required',          // Password wajib diisi
    ]);

    // 2. AMBIL CREDENTIALS
    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    // 3. COBA LOGIN
    if (Auth::attempt($credentials, $remember)) {
        // 4. REGENERATE SESSION (keamanan)
        $request->session()->regenerate();

        // 5. REDIRECT BERDASARKAN ROLE
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin')
                   ->with('success', 'Welcome back, Admin!');
        }
        
        return redirect()->intended('/')
               ->with('success', 'Welcome back!');
    }

    // 6. JIKA GAGAL LOGIN
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput($request->only('email'));
}
```

**Penjelasan Detail:**
1. **Validasi**: Laravel memvalidasi email dan password
2. **Credentials**: Ambil email dan password dari request
3. **Auth::attempt()**: Laravel mencoba login dengan credentials
   - Otomatis hash password dan bandingkan dengan database
   - Jika cocok, buat session
4. **Session Regenerate**: Untuk keamanan, buat session ID baru
5. **Role-based Redirect**: Admin ke `/admin`, User ke `/`
6. **Error Handling**: Jika gagal, kembalikan dengan error message

---

#### 3. `showRegisterForm()`
**Fungsi:** Menampilkan halaman register

```php
public function showRegisterForm()
{
    if (Auth::check()) {
        return redirect()->intended('/');
    }
    
    // Menampilkan view register dari resources/views/auth/register.blade.php
    return view('auth.register');
}
```

**Penjelasan:**
- **Ini menampilkan file `resources/views/auth/register.blade.php`**
- Cek authentication sama seperti login form

---

#### 4. `register(Request $request)`
**Fungsi:** Memproses registrasi user baru

```php
public function register(Request $request)
{
    // 1. VALIDASI INPUT
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // 2. BUAT USER BARU
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),  // Hash password
        'role' => 'user',  // Default role = user
    ]);

    // 3. AUTO LOGIN USER BARU
    Auth::login($user);

    // 4. REDIRECT KE HOMEPAGE
    return redirect('/')->with('success', 'Account created successfully!');
}
```

**Penjelasan Detail:**
1. **Validasi**:
   - `name`: Required, string, max 255 karakter
   - `email`: Required, email format, unique (tidak boleh duplikat)
   - `password`: Required, min 8 karakter, confirmed (harus match dengan password_confirmation)
2. **Create User**:
   - `Hash::make()`: Hash password sebelum simpan ke database
   - Role default = 'user'
3. **Auto Login**: User langsung login setelah registrasi
4. **Redirect**: Ke homepage dengan success message

---

#### 5. `logout(Request $request)`
**Fungsi:** Logout user

```php
public function logout(Request $request)
{
    // 1. LOGOUT USER
    Auth::logout();

    // 2. INVALIDATE SESSION
    $request->session()->invalidate();

    // 3. REGENERATE CSRF TOKEN
    $request->session()->regenerateToken();

    // 4. REDIRECT KE LOGIN
    return redirect('/login')
           ->with('success', 'You have been logged out successfully.');
}
```

**Penjelasan Detail:**
1. **Auth::logout()**: Hapus authentication
2. **invalidate()**: Hapus semua data session
3. **regenerateToken()**: Buat CSRF token baru (keamanan)
4. **Redirect**: Ke halaman login

---

## 👤 Model

### File: `app/Models/User.php`

Model User merepresentasikan tabel users di database.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. MASS ASSIGNMENT PROTECTION
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // Ditambahkan untuk role-based access
    ];

    // 2. HIDDEN ATTRIBUTES (tidak ditampilkan di JSON response)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 3. ATTRIBUTE CASTING
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',  // Auto hash password
        ];
    }

    // 4. HELPER METHODS
    
    /**
     * Check if user is admin
     * Usage: $user->isAdmin()
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     * Usage: $user->isUser()
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
```

**Penjelasan:**

1. **$fillable**: Field yang boleh diisi mass assignment
2. **$hidden**: Field yang disembunyikan saat convert ke JSON
3. **casts()**: Automatic type casting
   - `password => hashed`: Auto hash saat set password
4. **Helper Methods**: Method untuk cek role user

**Contoh Penggunaan:**
```php
// Cek role
if (Auth::user()->isAdmin()) {
    // Admin code
}

// Create user
User::create([
    'name' => 'John',
    'email' => 'john@example.com',
    'password' => 'password123',  // Auto di-hash
    'role' => 'user'
]);
```

---

## 👁️ View

### 1. Login Page: `resources/views/auth/login.blade.php`

**Cara Dipanggil:**
```php
// Dari AuthController.php
return view('auth.login');  // Laravel otomatis cari di resources/views/auth/login.blade.php
```

**Struktur View:**
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Mayra D'Light</title>
    <style>
        /* CSS untuk styling */
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo & Branding -->
        <div class="logo">
            <div class="logo-icon">Mayra D'Light</div>
            <div class="tagline">Roti hangat, resep keluarga.</div>
        </div>

        <!-- Welcome Text -->
        <h2 class="welcome-text">Welcome Back!</h2>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
            @csrf  <!-- CSRF Token untuk keamanan -->
            
            <!-- Email Input -->
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
                <span class="password-toggle" onclick="togglePassword()">
                    <svg>...</svg>  <!-- Icon mata -->
                </span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Sign in</button>
        </form>

        <!-- Additional Links -->
        <div class="register-link">
            Don't have an account? 
            <a href="{{ route('register') }}">Register here</a>
        </div>
    </div>

    <script>
        // JavaScript untuk validasi dan toggle password
    </script>
</body>
</html>
```

**Penjelasan Blade Directives:**

1. **`@csrf`**: 
   - Menghasilkan hidden input dengan CSRF token
   - Wajib untuk semua form POST (keamanan)

2. **`{{ route('login.post') }}`**:
   - Menghasilkan URL dari route name 'login.post'
   - Laravel generate URL otomatis

3. **`{{ old('email') }}`**:
   - Mengisi kembali input dengan value sebelumnya jika ada error

4. **`@if(session('success'))`**:
   - Cek apakah ada session 'success'
   - Tampilkan alert jika ada

5. **`@if($errors->any())`**:
   - Cek apakah ada validation errors
   - Loop dan tampilkan semua error

---

### 2. Register Page: `resources/views/auth/register.blade.php`

**Cara Dipanggil:**
```php
// Dari AuthController.php
return view('auth.register');  // Cari di resources/views/auth/register.blade.php
```

**Struktur View:**
```blade
<form action="{{ route('register.post') }}" method="POST">
    @csrf
    
    <!-- Name Input -->
    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </div>

    <!-- Email Input -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <!-- Password Input -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <div class="password-requirements">Must be at least 8 characters long</div>
    </div>

    <!-- Password Confirmation -->
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button type="submit" class="btn-submit">Create Account</button>
</form>
```

**Catatan Penting:**
- Input `password_confirmation` harus exact name ini agar `confirmed` validation rule bekerja

---

## 🛣️ Routes

### File: `routes/web.php`

Routes mendefinisikan URL dan controller yang menanganinya.

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontPageController;

// ========================================
// AUTHENTICATION ROUTES (Public)
// ========================================

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])
     ->name('login');
     
Route::post('/login', [AuthController::class, 'login'])
     ->name('login.post');

// Register Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])
     ->name('register');
     
Route::post('/register', [AuthController::class, 'register'])
     ->name('register.post');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout');

// ========================================
// PUBLIC ROUTES
// ========================================

Route::get('/', [FrontPageController::class, 'index'])
     ->name('home');
     
Route::get('/breads/{bread}', [FrontPageController::class, 'show'])
     ->name('breads.show');
     
Route::get('/about', [FrontPageController::class, 'about'])
     ->name('about');

// ========================================
// ADMIN ROUTES (Protected)
// ========================================

Route::middleware(['admin'])->group(function () {
    
    Route::get('/admin', [AdminController::class, 'index'])
         ->name('admin.dashboard');
    
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])
         ->name('admin.categories.store');
    
    Route::post('/admin/breads', [AdminController::class, 'storeBread'])
         ->name('admin.breads.store');
    
    Route::delete('/admin/breads/{bread}', [AdminController::class, 'destroyBread'])
         ->name('admin.breads.destroy');
});
```

**Penjelasan Route:**

1. **HTTP Methods**:
   - `GET`: Untuk menampilkan halaman
   - `POST`: Untuk submit form
   - `DELETE`: Untuk hapus data

2. **Route Naming**:
   ```php
   ->name('login')
   ```
   - Memberi nama route
   - Bisa dipanggil dengan `route('login')` di view

3. **Route Parameters**:
   ```php
   '/breads/{bread}'
   ```
   - `{bread}` = parameter dinamis
   - Laravel otomatis inject model Bread

4. **Middleware Group**:
   ```php
   Route::middleware(['admin'])->group(function () {
       // Routes di sini dilindungi admin middleware
   });
   ```

---

## 🛡️ Middleware

### 1. AdminMiddleware

**File:** `app/Http/Middleware/AdminMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. CEK APAKAH USER SUDAH LOGIN
        if (!Auth::check()) {
            return redirect('/login')
                   ->with('error', 'Please login to access this page.');
        }

        // 2. CEK APAKAH USER ADALAH ADMIN
        if (Auth::user()->role !== 'admin') {
            return redirect('/')
                   ->with('error', 'You do not have permission to access this page.');
        }

        // 3. JIKA LOLOS SEMUA CEK, LANJUTKAN REQUEST
        return $next($request);
    }
}
```

**Penjelasan:**
1. **Auth::check()**: Cek apakah user authenticated
2. **Auth::user()->role**: Ambil role user yang login
3. **$next($request)**: Lanjutkan ke controller jika lolos
4. **redirect()**: Kembalikan user dengan error jika gagal

**Flow Diagram:**
```
Request → AdminMiddleware
          ↓
    ┌─────┴─────┐
    │ Logged in?│
    └─────┬─────┘
          ├─ No → Redirect to /login
          │
          ↓ Yes
    ┌─────┴─────┐
    │  Is Admin?│
    └─────┬─────┘
          ├─ No → Redirect to /
          │
          ↓ Yes
       Controller
```

---

### 2. UserMiddleware

**File:** `app/Http/Middleware/UserMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // CEK APAKAH USER SUDAH LOGIN
        if (!Auth::check()) {
            return redirect('/login')
                   ->with('error', 'Please login to access this page.');
        }

        // LANJUTKAN REQUEST (tidak cek role)
        return $next($request);
    }
}
```

**Perbedaan dengan AdminMiddleware:**
- UserMiddleware: Hanya cek login, tidak cek role
- AdminMiddleware: Cek login + cek role admin

---

### 3. Registrasi Middleware

**File:** `bootstrap/app.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware): void {
        // REGISTER MIDDLEWARE DENGAN ALIAS
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
        ]);
    })
    ->create();
```

**Penjelasan:**
- Mendaftarkan middleware dengan alias
- Alias 'admin' bisa digunakan di routes: `->middleware(['admin'])`
- Alias 'user' bisa digunakan di routes: `->middleware(['user'])`

---

## 🗄️ Database & Migration

### Migration: Create Users Table

**File:** `database/migrations/0001_01_01_000000_create_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                           // Primary key (bigint auto increment)
            $table->string('name');                 // Nama user (varchar 255)
            $table->string('email')->unique();      // Email unique (varchar 255)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');             // Password hashed (varchar 255)
            $table->rememberToken();                // Remember me token
            $table->timestamps();                   // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

---

### Migration: Add Role Column

**File:** `database/migrations/2025_11_07_052642_update_role.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role setelah email
            $table->enum('role', ['admin', 'user'])
                  ->default('user')
                  ->after('email');
        });
    }

    public function down(): void
    {
        // Rollback: hapus kolom role
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
```

**Penjelasan:**
- **enum('role', ['admin', 'user'])**: Kolom hanya bisa isi 'admin' atau 'user'
- **->default('user')**: Default value = 'user'
- **->after('email')**: Posisi kolom setelah email

**Struktur Tabel Users:**
```
+-------------------+-----------------+------+-----+---------+
| Column            | Type            | Null | Key | Default |
+-------------------+-----------------+------+-----+---------+
| id                | bigint unsigned | NO   | PRI | NULL    |
| name              | varchar(255)    | NO   |     | NULL    |
| email             | varchar(255)    | NO   | UNI | NULL    |
| role              | enum            | NO   |     | user    |
| email_verified_at | timestamp       | YES  |     | NULL    |
| password          | varchar(255)    | NO   |     | NULL    |
| remember_token    | varchar(100)    | YES  |     | NULL    |
| created_at        | timestamp       | YES  |     | NULL    |
| updated_at        | timestamp       | YES  |     | NULL    |
+-------------------+-----------------+------+-----+---------+
```

---

## 🌱 Seeder

### AdminUserSeeder

**File:** `database/seeders/AdminUserSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREATE ADMIN USER
        if (!User::where('email', 'admin@mayra.com')->exists()) {
            User::create([
                'name' => 'Admin Mayra',
                'email' => 'admin@mayra.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        // 2. CREATE TEST USER
        if (!User::where('email', 'user@mayra.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'user@mayra.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]);
        }
    }
}
```

**Penjelasan:**
1. **Cek Eksistensi**: `->exists()` cek apakah user sudah ada
2. **Hash::make()**: Hash password sebelum simpan
3. **Role Assignment**: Set role secara manual

**Jalankan Seeder:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

---

### DatabaseSeeder

**File:** `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil AdminUserSeeder
        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}
```

**Jalankan:**
```bash
php artisan db:seed
```

---

## ⚙️ Cara Kerja

### 1. Alur Login

```
┌──────────────────────────────────────────────────────────────────┐
│ 1. USER MENGAKSES /login                                         │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 2. Route: GET /login                                             │
│    → AuthController::showLoginForm()                             │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 3. Controller cek: Auth::check()                                 │
│    ├─ Sudah login? → Redirect ke /                               │
│    └─ Belum login? → return view('auth.login')                   │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 4. TAMPIL: resources/views/auth/login.blade.php                 │
│    User isi email & password, klik "Sign in"                     │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 5. FORM SUBMIT: POST /login                                      │
│    Data dikirim ke AuthController::login()                       │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 6. Controller VALIDASI:                                          │
│    - Email required & format email valid?                        │
│    - Password required?                                          │
│    ├─ Tidak valid? → Back dengan error                           │
│    └─ Valid? → Lanjut                                            │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 7. Controller CEK KREDENSIAL: Auth::attempt()                    │
│    - Cari user by email di database                              │
│    - Hash password input & bandingkan dengan DB                  │
│    ├─ Tidak cocok? → Back dengan error                           │
│    └─ Cocok? → Lanjut                                            │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 8. LOGIN BERHASIL                                                │
│    - Buat session untuk user                                     │
│    - $request->session()->regenerate()                           │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 9. CEK ROLE USER                                                 │
│    ├─ Role = 'admin' → Redirect ke /admin                        │
│    └─ Role = 'user' → Redirect ke /                              │
└──────────────────────────────────────────────────────────────────┘
```

**Kode Flow:**
```php
// 1. User akses GET /login
Route::get('/login', [AuthController::class, 'showLoginForm']);

// 2. Controller return view
public function showLoginForm() {
    return view('auth.login');  // Tampilkan login.blade.php
}

// 3. User submit form POST /login
Route::post('/login', [AuthController::class, 'login']);

// 4. Controller proses login
public function login(Request $request) {
    // Validasi
    $request->validate([...]);
    
    // Attempt login
    if (Auth::attempt($credentials)) {
        // Regenerate session
        $request->session()->regenerate();
        
        // Redirect based on role
        if (Auth::user()->role === 'admin') {
            return redirect('/admin');
        }
        return redirect('/');
    }
    
    // Gagal login
    return back()->withErrors([...]);
}
```

---

### 2. Alur Register

```
┌──────────────────────────────────────────────────────────────────┐
│ 1. USER MENGAKSES /register                                      │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 2. Route: GET /register                                          │
│    → AuthController::showRegisterForm()                          │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 3. TAMPIL: resources/views/auth/register.blade.php              │
│    User isi: name, email, password, password_confirmation        │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 4. FORM SUBMIT: POST /register                                   │
│    Data dikirim ke AuthController::register()                    │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 5. Controller VALIDASI:                                          │
│    - Name required & max 255?                                    │
│    - Email valid & unique?                                       │
│    - Password min 8 & confirmed?                                 │
│    ├─ Tidak valid? → Back dengan error                           │
│    └─ Valid? → Lanjut                                            │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 6. CREATE USER BARU                                              │
│    - Hash password dengan Hash::make()                           │
│    - Set role = 'user'                                           │
│    - Simpan ke database                                          │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 7. AUTO LOGIN                                                    │
│    - Auth::login($user)                                          │
│    - Buat session untuk user baru                                │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ 8. REDIRECT KE HOMEPAGE                                          │
│    dengan success message                                        │
└──────────────────────────────────────────────────────────────────┘
```

---

### 3. Alur Middleware Protection

```
┌──────────────────────────────────────────────────────────────────┐
│ USER MENGAKSES /admin                                            │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ Route: middleware(['admin'])                                     │
│ → AdminMiddleware::handle()                                      │
└───────────────────────────┬──────────────────────────────────────┘
                            ↓
                   ┌────────┴────────┐
                   │  Auth::check()  │
                   └────────┬────────┘
                            ├─ No → Redirect /login
                            │
                            ↓ Yes
                   ┌────────┴────────┐
                   │ role === admin? │
                   └────────┬────────┘
                            ├─ No → Redirect / (forbidden)
                            │
                            ↓ Yes
┌──────────────────────────────────────────────────────────────────┐
│ LANJUT KE CONTROLLER                                             │
│ AdminController::index()                                         │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🧪 Testing

### Manual Testing

#### 1. Test Login Admin

```bash
# Start server
php artisan serve

# Browser: http://localhost:8000/login
# Credentials:
Email: admin@mayra.com
Password: admin123

# Expected:
✓ Redirect ke /admin
✓ Muncul "Welcome back, Admin!"
✓ Bisa akses admin dashboard
```

#### 2. Test Login User

```bash
# Browser: http://localhost:8000/login
# Credentials:
Email: user@mayra.com
Password: user123

# Expected:
✓ Redirect ke /
✓ Muncul "Welcome back!"
✓ TIDAK bisa akses /admin (redirect dengan error)
```

#### 3. Test Register

```bash
# Browser: http://localhost:8000/register
# Fill form:
Name: John Doe
Email: john@example.com
Password: password123
Confirm Password: password123

# Expected:
✓ User baru tersimpan di database
✓ Auto login
✓ Redirect ke homepage
✓ Role = 'user'
```

#### 4. Test Middleware

```bash
# Test 1: Akses /admin tanpa login
# Browser: http://localhost:8000/admin (logout dulu)
# Expected: Redirect ke /login dengan error

# Test 2: Akses /admin sebagai user
# Login sebagai user@mayra.com
# Akses: http://localhost:8000/admin
# Expected: Redirect ke / dengan error "no permission"

# Test 3: Akses /admin sebagai admin
# Login sebagai admin@mayra.com
# Akses: http://localhost:8000/admin
# Expected: Berhasil akses dashboard
```

---

### Command Testing

```bash
# 1. Cek database users
php artisan tinker
>>> User::all();  // Lihat semua user
>>> User::where('role', 'admin')->get();  // Lihat admin

# 2. Test authentication
>>> Auth::attempt(['email' => 'admin@mayra.com', 'password' => 'admin123']);
>>> Auth::check();  // Should return true
>>> Auth::user()->role;  // Should return 'admin'

# 3. Reset database & seed
php artisan migrate:fresh --seed
```

---

## Ringkasan Cara Kerja File

### View Rendering

```php
// Dari Controller
return view('auth.login');

// Laravel mencari file:
resources/views/auth/login.blade.php
```

### Route ke Controller

```php
// Di routes/web.php
Route::get('/login', [AuthController::class, 'showLoginForm']);

// Memanggil method:
app/Http/Controllers/AuthController.php::showLoginForm()
```

### Controller ke Model

```php
// Di Controller
$user = User::create([...]);

// Menggunakan model:
app/Models/User.php
```

### Middleware Protection

```php
// Di routes/web.php
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', ...);
});

// Memanggil middleware:
app/Http/Middleware/AdminMiddleware.php
```

---

## Key Points

1. **MVC Pattern**:
   - **Model** (User.php): Data & business logic
   - **View** (*.blade.php): UI/tampilan
   - **Controller** (AuthController.php): Koordinasi antara Model & View

2. **Authentication Flow**:
   - Login → Validasi → Auth::attempt() → Session → Redirect
   - Register → Validasi → Create User → Auto Login → Redirect

3. **Authorization (Middleware)**:
   - AdminMiddleware: Cek login + role admin
   - UserMiddleware: Hanya cek login
   - Digunakan di routes untuk protect akses

4. **Security**:
   - Password di-hash dengan Hash::make()
   - CSRF protection dengan @csrf
   - Session regeneration setelah login
   - Validation untuk semua input

5. **File Paths**:
   - Views: `resources/views/`
   - Controllers: `app/Http/Controllers/`
   - Models: `app/Models/`
   - Middleware: `app/Http/Middleware/`
   - Routes: `routes/web.php`

---

## Referensi

- [Laravel Authentication](https://laravel.com/docs/11.x/authentication)
- [Laravel Middleware](https://laravel.com/docs/11.x/middleware)
- [Blade Templates](https://laravel.com/docs/11.x/blade)
- [Laravel Routing](https://laravel.com/docs/11.x/routing)

---

**Dibuat pada:** November 7, 2025  
**Laravel Version:** 11.x  
**Autor:** Sistem Autentikasi Mayra D'Light
