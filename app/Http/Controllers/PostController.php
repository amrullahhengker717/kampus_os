<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(15);
        return view('feed.index', compact('posts'));
    }

    public function store(StorePostRequest $request)
    {
        $post = $request->user()->posts()->create($request->validated());

        return back()->with('success', 'Post created successfully!');
    }
}
