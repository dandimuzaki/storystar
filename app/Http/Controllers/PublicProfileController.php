<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(Request $request, User $user)
    {
        $posts = $user->posts()->latest()->paginate(5);
        return view('public_profile.show', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function index(Request $request)
    {
        $users = User::when(
            $request->search,
            fn($q, $search) => $q->search($search) // <-- must match `scopeSearch`
        )->paginate(10);
    
        return view('result.users', compact('users'));
    }
}
