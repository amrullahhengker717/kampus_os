<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user', 'category')->latest()->paginate(15);
        return view('forum.index', compact('threads'));
    }
}
