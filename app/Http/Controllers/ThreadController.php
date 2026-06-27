<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user', 'category')->withCount('replies')->latest()->paginate(15);
        $categories = \App\Models\Category::all();
        return view('forum.index', compact('threads', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'attachments.*' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $thread = Thread::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $thread->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('forum.show', $thread)->with('message', 'Thread berhasil dibuat!');
    }

    public function show(Thread $thread)
    {
        $thread->increment('views');
        $thread->load('user', 'category', 'attachments');
        // Load replies nested up to 3 levels to avoid infinite eager loading issues, 
        // or just load all replies and group them in the view. Let's load all and let the view handle it recursively.
        $replies = $thread->replies()->with('user', 'attachments', 'replies')->latest()->get();
        
        return view('forum.show', compact('thread', 'replies'));
    }

    public function storeReply(Request $request, Thread $thread)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:replies,id',
            'attachments.*' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $reply = $thread->replies()->create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $reply->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('forum.show', $thread)->with('message', 'Balasan terkirim!');
    }
    public function edit(Thread $thread)
    {
        if ($thread->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit thread ini.');
        }
        $categories = \App\Models\Category::all();
        return view('forum.edit', compact('thread', 'categories'));
    }

    public function update(Request $request, Thread $thread)
    {
        if ($thread->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit thread ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $thread->update($validated);

        return redirect()->route('forum.show', $thread)->with('message', 'Thread berhasil diperbarui!');
    }

    public function destroy(Thread $thread)
    {
        if ($thread->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus thread ini.');
        }

        $thread->delete();

        return redirect()->route('forum.index')->with('message', 'Thread berhasil dihapus.');
    }

    public function destroyReply(\App\Models\Reply $reply)
    {
        if ($reply->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus komentar ini.');
        }

        $reply->delete();

        return back()->with('message', 'Komentar berhasil dihapus.');
    }
}
