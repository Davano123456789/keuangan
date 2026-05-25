<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index()
    {
        $users = User::with('wallets')->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => 'success',
            'data' => $user->load('wallets')
        ]);
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang dapat melakukan aksi ini.'
            ], 403);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,user'],
            'wallet_ids' => ['nullable', 'array'],
            'wallet_ids.*' => ['exists:wallets,id'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->has('wallet_ids')) {
            $user->wallets()->sync($request->wallet_ids);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Akun pegawai berhasil ditambahkan',
            'data' => $user->load('wallets')
        ], 201);
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang dapat melakukan aksi ini.'
            ], 403);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,user'],
            'wallet_ids' => ['nullable', 'array'],
            'wallet_ids.*' => ['exists:wallets,id'],
        ]);

        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Sync wallet access (empty array if admin or no wallets selected)
        $user->wallets()->sync($request->wallet_ids ?? []);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pegawai berhasil diperbarui',
            'data' => $user->load('wallets')
        ]);
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang dapat melakukan aksi ini.'
            ], 403);
        }

        // Don't allow user to delete themselves
        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak bisa menghapus akun Anda sendiri!'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Akun pegawai berhasil dihapus'
        ]);
    }
}

