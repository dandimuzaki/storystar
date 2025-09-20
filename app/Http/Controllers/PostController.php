<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {     
        $query = Post::with('category','user','media');

    if ($request->has('category')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('name', $request->category); 
        });
    }

        $posts = $query
            ->withCount('claps')
            ->where('published_at','<=',now())
            ->latest()
            ->simplePaginate(5);

        return view('post.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Cache::remember('categories', 3600, function () {
            return Category::all();
        });
        
        return view('post.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(PostCreateRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        $data['published_at'] = \Carbon\Carbon::parse($data['published_at']);

        $post = Post::create($data);

        $post->addMediaFromRequest('image')
            ->toMediaCollection('posts');

        return redirect()->route('mypost', $post->user->username);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        $post = Post::withCount('claps')->findOrFail($post->id);

        return view('post.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        };
        
        $categories = Cache::remember('categories', 3600, function () {
            return Category::all();
        });
        
        return view('post.edit', [
            'categories' => $categories,
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        };

        $data = $request->validated();

        $data['published_at'] = \Carbon\Carbon::parse($data['published_at']);

        $post->update($data);

        if ($data['image'] ?? false) {
            $post->addMediaFromRequest('image')
            ->toMediaCollection('posts');
        }
        
        return redirect()->route('mypost', $post->user->username);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('mypost', $post->user->username);
    }
}
