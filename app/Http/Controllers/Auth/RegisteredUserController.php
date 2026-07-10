<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use App\Http\Requests\Auth\RegisterCustomerRequest;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi customer.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Menyimpan data customer baru ke database.
     *
     * @throws ValidationException
     */
    public function store(RegisterCustomerRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Pendaftaran berhasil, arahkan ke login dengan pesan sukses (tidak otomatis login)
        return redirect(route('login'))->with('status', 'Akun berhasil dibuat. Silakan login menggunakan akun yang telah didaftarkan.');
    }
}
