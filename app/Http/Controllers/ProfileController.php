<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN PROFILE
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE + FOTO
    |--------------------------------------------------------------------------
    */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // update data basic
        $user->fill($request->validated());

        // reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {

            $user->email_verified_at = null;
        }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO PROFILE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            // hapus foto lama
            if (
                $user->foto &&
                Storage::disk('public')->exists($user->foto)
            ) {

                Storage::disk('public')->delete($user->foto);
            }

            // upload foto baru
            $fotoPath = $request->file('foto')->store(
                'foto-profile',
                'public'
            );

            $user->foto = $fotoPath;
        }

        $user->save();

        return Redirect::back()->with(
            'success',
            'Profil berhasil diperbarui'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN UBAH PASSWORD
    |--------------------------------------------------------------------------
    */

    public function editPassword()
    {
        return view('profile.password');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        // cek password lama
        if (
            !Hash::check(
                $request->current_password,
                Auth::user()->password
            )
        ) {

            return back()->with(
                'error',
                'Password lama salah'
            );
        }

        // update password baru
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with(
            'success',
            'Password berhasil diubah'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE ACCOUNT
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // hapus foto jika ada
        if (
            $user->foto &&
            Storage::disk('public')->exists($user->foto)
        ) {

            Storage::disk('public')->delete($user->foto);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}