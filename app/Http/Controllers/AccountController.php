<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Customer\UpdateAccountRequest;

/**
 * Controller untuk mengelola profil akun customer.
 */
class AccountController extends Controller
{
    /**
     * Menampilkan halaman edit profil customer.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('account');
    }

    /**
     * Menyimpan perubahan profil nama dan kata sandi customer.
     *
     * @param UpdateAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAccountRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->name;

        // Memperbarui kata sandi jika diinputkan
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('success', '✅ Profil berhasil diperbarui');
    }
}