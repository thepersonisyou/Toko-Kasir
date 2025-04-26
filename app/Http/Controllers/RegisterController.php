<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:5|max:255|'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        // Simpan data ke database
        User::create($validatedData);

        return redirect()->route('login')
                    ->with('success', 'Akun Berhasil Didaftarkan.');
    }
}
