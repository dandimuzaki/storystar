<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->query('search');

        $posts = Post::query()
            ->where('title', 'like', "%{$search}%")
            ->where('published_at', '<=', now())
            ->latest()
            ->paginate(5, ['*'], 'result.posts');
        
        $users = User::query()
            ->where('username', 'like', "%{$search}%")
            ->latest()
            ->paginate(5, ["*"], "result.users");

        return view('result.index', [
            'search'=> $search,
            'posts' => $posts,
            'users' => $users
        ]);
    }
}
