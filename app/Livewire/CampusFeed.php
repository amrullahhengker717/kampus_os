<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class CampusFeed extends Component
{
    use WithFileUploads;

    public $content = '';
    public $type = 'general';
    public $attachments = [];
    public $commentBodies = []; // Store comment bodies indexed by post ID
    public $activeComments = []; // Store which posts have comments expanded

    public function render()
    {
        $posts = \App\Models\Post::with([
                'user', 
                'attachments', 
                'likes', 
                'comments.user'
            ])
            ->withCount('likes', 'comments')
            ->latest()
            ->paginate(15);
            
        return view('livewire.campus-feed', ['posts' => $posts]);
    }

    public function createPost()
    {
        $this->validate([
            'content' => 'required|string|max:1000',
            'type' => 'required|in:general,news,event,marketplace',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $post = auth()->user()->posts()->create([
            'content' => $this->content,
            'type' => $this->type,
        ]);

        if (!empty($this->attachments)) {
            foreach ($this->attachments as $file) {
                $path = $file->store('attachments', 'public');
                $post->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        $this->reset(['content', 'type', 'attachments']);
        session()->flash('message', 'Post created successfully!');
    }

    public function toggleLike($postId)
    {
        $userId = auth()->id();
        $like = \App\Models\Like::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
        } else {
            \App\Models\Like::create([
                'post_id' => $postId,
                'user_id' => $userId,
            ]);
        }
    }

    public function toggleComments($postId)
    {
        if (in_array($postId, $this->activeComments)) {
            $this->activeComments = array_diff($this->activeComments, [$postId]);
        } else {
            $this->activeComments[] = $postId;
        }
    }

    public function addComment($postId)
    {
        if (!isset($this->commentBodies[$postId]) || trim($this->commentBodies[$postId]) === '') {
            return;
        }

        \App\Models\Comment::create([
            'post_id' => $postId,
            'user_id' => auth()->id(),
            'body' => $this->commentBodies[$postId],
        ]);

        $this->commentBodies[$postId] = '';
    }

    public function deletePost($postId)
    {
        $post = \App\Models\Post::findOrFail($postId);
        if ($post->user_id === auth()->id()) {
            $post->delete();
        }
    }
}
