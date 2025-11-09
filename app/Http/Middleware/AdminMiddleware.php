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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan bahwa pengguna:
     * 1. Sudah login (punya session aktif).
     * 2. Memiliki role `admin`.
     *
     * Jika pengguna belum login, mereka akan diarahkan ke halaman login.
     * Jika pengguna sudah login tapi bukan admin, mereka akan diarahkan ke halaman utama.
    * middleware ini bakal di gunakan di:
    * @see \App\bootstrap\app.php - Didaftarkan sebagai middleware dengan alias 'admin'
    * @see routes/web.php - Diterapkan pada route yang memerlukan akses admin
    * 
    */
    public function handle(Request $request, Closure $next): Response
    {

        //di cek kalo user udah login belum(punya session)
        if (!Auth::check()) {
            //kalo belum arahin user ke page login 
            return redirect('/login')->with('error', 'Please login to access this page.');
        }

        // kalo udah di cek lagi user itu admin bukan 
        if (Auth::user()->role !== 'admin') {
            //kalo bukan jangan di kasih masuk arahin dia ke lending page aja
            return redirect('/')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
