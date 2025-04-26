<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        return view('index');
    }

    public function login(Request $request)
    {
        // Mencoba login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Jika login berhasil, redirect ke dashboard
            return redirect('/dashboard')->with('success', 'Selamat datang!');
        } else {
            // Jika login gagal, redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['login' => 'Email dan password tidak sesuai!'])->withInput();
        }
    }

    public function logout ()
    {
        Auth::logout();
        return redirect('/login');
    }
}
