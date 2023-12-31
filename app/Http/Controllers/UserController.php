<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('peran', '!=', 'admin')->orderBy('peran', 'asc')->orderBy('nama', 'asc')->get();
        return view('user.index', compact('users'));
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'peran' => 'required|in:admin,kasir,pelanggan',
        ], [
            'required' => ':attribute tidak boleh kosong!',
            'email.email' => 'email yang Anda masukkan tidak valid!',
            'email.unique' => 'email yang anda masukkan sudah terdaftar!',
            'in' => ':attribute harus memiliki nilai :values',
        ]);

        $validate['password'] = Hash::make($request->password);
        $register = User::create($validate);

        if ($register) {
            return redirect(route('admin.users.index'))->with('success', 'Berhasil menambahkan user');
        }
        return back()->with('error', 'Ada kesalahan sistem, mohon coba beberapa saat lagi!')->withInput($request->except('password'));
    }

    public function edit(User $user): View
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validate = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'alamat' => 'nullable|string',
            'email' => 'required|email',
            'peran' => 'required|in:admin,kasir,pelanggan',
            'password' => 'nullable|string|exclude'
        ], [
            'required' => ':attribute tidak boleh kosong!',
            'email.email' => 'email yang Anda masukkan tidak valid!',
            'in' => ':attribute harus memiliki nilai :values',
        ]);
        if (trim($request->password != '')) {
            $validate['password'] = Hash::check($request->password, $user->passowrd) ? $user->password : Hash::make($request->password);
        }

        if ($user->update($validate)) {
            if ($user->peran == 'admin') {
                return redirect(route('admin.users.index'))->with('success', 'Berhasil mengubah data user');
            } else if ($user->peran == 'pelanggan') {
                $request->session()->regenerate();
                return redirect(route('pelanggan.profil'))->with('success', 'Berhasil menyimpan perubahan');
            }
        }
        return back()->with('error', 'Gagal mengubah data user. Terjadi kesalahan, coba lagi beberapa saat')->withInput($request->except('password'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->delete()) {
            return redirect(route('admin.users.index'))->with('success', 'Berhasil menghapus data user');
        }
        return back()->with('error', 'Gagal menghapus data user. Terjadi kesalahan, coba lagi beberapa saat');
    }
}
