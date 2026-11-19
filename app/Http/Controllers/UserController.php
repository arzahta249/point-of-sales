<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('nama')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'username'      => ['required', 'string', 'max:100', 'unique:users,username', 'alpha_dash'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
            'role'          => ['required', 'in:admin,kasir,pemilik'],
        ], [
            'nama.required'          => 'Nama wajib diisi.',
            'username.required'      => 'Username wajib diisi.',
            'username.unique'        => 'Username sudah digunakan.',
            'username.alpha_dash'    => 'Username hanya boleh huruf, angka, strip, dan underscore.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'role.required'          => 'Role wajib dipilih.',
            'role.in'                => 'Role tidak valid.',
        ]);

        User::create([
            'nama'          => $request->nama,
            'username'      => $request->username,
            'password_hash' => Hash::make($request->password),
            'role'          => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id, 'alpha_dash'],
            'role'     => ['required', 'in:admin,kasir,pemilik'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:6', 'confirmed'];
        }

        $request->validate($rules, [
            'nama.required'       => 'Nama wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan underscore.',
            'role.required'       => 'Role wajib dipilih.',
            'role.in'             => 'Role tidak valid.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        $data = $request->only('nama', 'username', 'role');

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}

