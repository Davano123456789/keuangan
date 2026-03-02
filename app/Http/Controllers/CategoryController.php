<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::first(); 
        $userId = $user ? $user->id : null;

        $categories = Category::where('user_id', $userId)->get();
        return view('categories.index', compact('categories'));
    }
}
