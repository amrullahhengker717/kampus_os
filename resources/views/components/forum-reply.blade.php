@props(['reply', 'depth' => 0, 'parentAuthor' => null])

<div class="relative {{ $depth > 0 ? 'ml-6 sm:ml-12 mt-4' : 'mt-4' }}">
    <!-- Indentation Line for nested replies -->
    @if($depth > 0)
        <div class="absolute -left-6 top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
        <div class="absolute -left-6 top-8 w-6 h-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
    @endif

    <div class="glass-panel p-5" id="reply-{{ $reply->id }}">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-sm">
                    {{ substr($reply->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $reply->user->name }}</h4>
                        @if($parentAuthor)
                            <span class="text-xs text-gray-400 flex items-center">
                                <svg class="w-3 h-3 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                membalas <span class="font-semibold text-indigo-500 ml-1">{{ '@'.$parentAuthor }}</span>
                            </span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <button x-data @click="$dispatch('open-reply-modal', { parentId: {{ $reply->id }}, author: '{{ $reply->user->name }}' })" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Balas
                </button>
                
                @if(Auth::id() === $reply->user_id)
                    <form method="POST" action="{{ route('forum.reply.destroy', $reply) }}" onsubmit="return confirm('Hapus komentar ini?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap ml-11">
            {{ $reply->body }}
        </div>

        @if($reply->attachments && $reply->attachments->count() > 0)
            <div class="ml-11 mt-3 space-y-2">
                @foreach($reply->attachments as $attachment)
                    @if(str_starts_with($attachment->mime_type, 'image/'))
                        <div class="inline-block relative group">
                            <img src="{{ Storage::url($attachment->file_path) }}" alt="{{ $attachment->file_name }}" class="max-h-48 rounded-lg border border-gray-200 dark:border-gray-700 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">
                        </div>
                    @elseif(str_starts_with($attachment->mime_type, 'video/'))
                        <video controls class="max-w-full max-h-64 rounded-lg border border-gray-200 dark:border-gray-700">
                            <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type }}">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    @elseif(str_starts_with($attachment->mime_type, 'audio/'))
                        <audio controls class="w-full max-w-md">
                            <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type }}">
                            Browser Anda tidak mendukung tag audio.
                        </audio>
                    @else
                        <a href="{{ Storage::url($attachment->file_path) }}" download class="inline-flex items-center space-x-2 p-2 rounded bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $attachment->file_name }}</span>
                            <span class="text-xs text-gray-400">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recursive Call for Nested Replies -->
    @if($reply->replies && $reply->replies->count() > 0)
        <div class="nested-replies-container">
            @foreach($reply->replies as $nestedReply)
                <!-- Prevent infinite recursion by capping visual depth (optional) but allowing recursive data -->
                <x-forum-reply :reply="$nestedReply" :depth="$depth + 1" :parent-author="$reply->user->name" />
            @endforeach
        </div>
    @endif
</div>
