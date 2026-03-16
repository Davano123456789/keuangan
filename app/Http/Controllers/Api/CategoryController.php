<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:IN,OUT',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'type' => $request->type
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:IN,OUT',
        ]);

        $category->update([
            'name' => $request->name,
            'type' => $request->type
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        // Optional: Check if category is used in transactions
        $isUsed = \App\Models\Transaction::where('category_id', $category->id)->exists();
        if ($isUsed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak bisa menghapus kategori! Kategori ini masih digunakan dalam transaksi.'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
