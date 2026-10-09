<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     */
    public function index(): View
    {
        $users = User::withCount('transaksiPenjualans')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna baru.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Menyimpan data pengguna baru ke basis data.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pengguna' => ['required', 'string', 'max:50', 'unique:users,nama_pengguna'],
            'password' => ['required', 'string', 'min:6'],
            'peran' => ['required', 'in:admin,kasir,pemilik'],
        ], [
            'nama_pengguna.required' => 'Nama pengguna wajib diisi.',
            'nama_pengguna.unique' => 'Nama pengguna sudah digunakan, silakan pilih yang lain.',
            'nama_pengguna.max' => 'Nama pengguna maksimal 50 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
            'peran.required' => 'Peran pengguna wajib dipilih.',
            'peran.in' => 'Peran yang dipilih tidak valid.',
        ]);

        User::create([
            'nama_pengguna' => $validated['nama_pengguna'],
            'password' => $validated['password'],
            'peran' => $validated['peran'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru "'.$validated['nama_pengguna'].'" berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data pengguna.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di basis data.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pengguna' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nama_pengguna')->ignore($user->id),
            ],
            'peran' => ['required', 'in:admin,kasir,pemilik'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nama_pengguna.required' => 'Nama pengguna wajib diisi.',
            'nama_pengguna.unique' => 'Nama pengguna sudah terdaftar.',
            'nama_pengguna.max' => 'Nama pengguna maksimal 50 karakter.',
            'peran.required' => 'Peran pengguna wajib dipilih.',
            'peran.in' => 'Peran yang dipilih tidak valid.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 6 karakter.',
        ]);

        $updateData = [
            'nama_pengguna' => $validated['nama_pengguna'],
            'peran' => $validated['peran'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna "'.$user->nama_pengguna.'" berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dengan pengecekan integritas data.
     */
    public function destroy(User $user): RedirectResponse
    {
        // 1. Larangan menghapus akun sendiri yang sedang aktif
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        // 2. Larangan menghapus pengguna yang memiliki riwayat transaksi
        if ($user->transaksiPenjualans()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna "'.$user->nama_pengguna.'" tidak dapat dihapus karena memiliki riwayat data transaksi penjualan.');
        }

        try {
            $nama = $user->nama_pengguna;
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna "'.$nama.'" berhasil dihapus dari sistem.');
        } catch (QueryException $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menghapus pengguna karena data masih terikat dengan catatan sistem lainnya.');
        }
    }
}
