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
        $users = User::latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Akun pegawai berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(User $user)
    {
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
