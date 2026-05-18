<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function redirectBasedOnRole()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('teknisi')) {
            return redirect()->route('teknisi.dashboard');
        } elseif ($user->hasRole('pelanggan')) {
            return redirect()->route('pelanggan.dashboard');
        }

        return redirect('/');
    }
}