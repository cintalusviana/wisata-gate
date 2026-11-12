<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OfficerController extends Controller
{
    /**
     * Menampilkan halaman Data Petugas.
     */
    public function index()
    {
        $officers = User::whereIn('role', ['admin', 'petugas'])
            ->latest()
            ->get();

        return view('officers.index', compact('officers'));
    }

    /**
     * Menyimpan petugas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'petugas']),
            ],
            'status' => [
                'required',
                Rule::in(['aktif', 'nonaktif']),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama petugas wajib diisi.',
            'name.max' => 'Nama petugas maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah terdaftar.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('officers.index')
            ->with('success', 'Data petugas berhasil ditambahkan.');
    }

    /**
     * Memperbarui data petugas.
     */
    public function update(Request $request, $officer)
    {
        $user = User::whereIn('role', ['admin', 'petugas'])
            ->findOrFail($officer);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'petugas']),
            ],
            'status' => [
                'required',
                Rule::in(['aktif', 'nonaktif']),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama petugas wajib diisi.',
            'name.max' => 'Nama petugas maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        // Password hanya diperbarui jika diisi.
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('officers.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Menghapus data petugas.
     *
     * Akun yang memiliki riwayat kendaraan atau aktivitas
     * tidak boleh dihapus agar data historis tetap tersimpan.
     */
    public function destroy($officer)
    {
        $result = DB::transaction(function () use ($officer) {

            $user = User::whereIn('role', ['admin', 'petugas'])
                ->lockForUpdate()
                ->findOrFail($officer);

            // Mencegah penghapusan akun yang sedang digunakan.
            if ((int) $user->id === (int) auth()->id()) {
                return [
                    'success' => false,
                    'message' => 'Akun yang sedang digunakan tidak dapat dihapus.',
                ];
            }

            // Periksa apakah akun memiliki riwayat kendaraan.
            $hasGateLogs = $user->gateLogs()->exists();

            // Periksa apakah akun memiliki riwayat aktivitas.
            $hasActivityLogs = $user->activityLogs()->exists();

            if ($hasGateLogs || $hasActivityLogs) {
                return [
                    'success' => false,
                    'message' => 'Akun tidak dapat dihapus karena masih memiliki riwayat kendaraan atau aktivitas. Silakan nonaktifkan akun melalui fitur Edit.',
                ];
            }

            // Hapus hanya jika tidak memiliki riwayat.
            $user->delete();

            return [
                'success' => true,
                'message' => 'Data petugas berhasil dihapus.',
            ];
        });

        if (!$result['success']) {
            return redirect()
                ->route('officers.index')
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('officers.index')
            ->with('success', $result['message']);
    }
}
