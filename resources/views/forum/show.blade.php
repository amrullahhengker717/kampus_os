<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('forum.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $thread->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Thread Original Post -->
            <div class="glass-panel p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl">
                                {{ substr($thread->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $thread->user->name }}</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $thread->created_at->format('d M Y, H:i') }}</span>
                                <span>•</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-{{ $thread->category->color }}-100 text-{{ $thread->category->color }}-800">
                                    {{ $thread->category->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if(Auth::id() === $thread->user_id)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('forum.edit', $thread) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Edit Thread</a>
                                    <form method="POST" action="{{ route('forum.destroy', $thread) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus thread ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Hapus Thread</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 whitespace-pre-wrap">
                    {{ $thread->body }}
                </div>
                
                <!-- Thread Attachments -->
                @if($thread->attachments && $thread->attachments->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 space-y-2">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Lampiran</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($thread->attachments as $attachment)
                                @if(str_starts_with($attachment->mime_type, 'image/'))
                                    <img src="{{ Storage::url($attachment->file_path) }}" alt="{{ $attachment->file_name }}" class="max-h-64 rounded-lg border border-gray-200 dark:border-gray-700 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">
                                @elseif(str_starts_with($attachment->mime_type, 'video/'))
                                    <video controls class="w-full max-h-64 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type }}">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                @elseif(str_starts_with($attachment->mime_type, 'audio/'))
                                    <audio controls class="w-full">
                                        <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type }}">
                                        Browser Anda tidak mendukung tag audio.
                                    </audio>
                                @else
                                    <a href="{{ Storage::url($attachment->file_path) }}" download class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <div class="flex-1 truncate">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $attachment->file_name }}</div>
                                            <div class="text-xs text-gray-500">{{ number_format($attachment->size / 1024, 1) }} KB</div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            {{ $thread->views }} Dilihat
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            {{ $thread->replies_count ?? $replies->count() }} Balasan
                        </div>
                    </div>
                    
                    <button x-data @click="$dispatch('open-reply-modal', { parentId: null, author: '{{ $thread->user->name }}' })" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm shadow-sm">
                        Balas Diskusi Ini
                    </button>
                </div>
            </div>

            <!-- Replies List (Recursive) -->
            @if($replies->count() > 0)
                <div class="space-y-2 mt-8">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Semua Balasan</h4>
                    
                    @foreach($replies as $reply)
                        <x-forum-reply :reply="$reply" :depth="0" />
                    @endforeach
                </div>
            @else
                <div class="glass-panel p-8 text-center text-gray-500 dark:text-gray-400 mt-8">
                    Belum ada balasan. Jadilah yang pertama berkomentar!
                </div>
            @endif
        </div>
    </div>

    <!-- Universal Reply Modal -->
    <x-modal name="reply-modal" focusable>
        <div x-data="{ parentId: null, author: '' }"
             x-on:open-reply-modal.window="parentId = $event.detail.parentId; author = $event.detail.author; $dispatch('open-modal', 'reply-modal')">
             
            <form method="POST" action="{{ route('forum.reply.store', $thread) }}" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="parent_id" :value="parentId">

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4" x-text="parentId ? 'Membalas ' + author : 'Balas Diskusi'">
                </h2>

                <div class="mb-4">
                    <x-input-label for="reply-body" :value="__('Komentar Anda')" />
                    <textarea id="reply-body" name="body" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required placeholder="Tuliskan pemikiran Anda..."></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="reply-attachments" :value="__('Lampirkan File (Opsional)')" />
                    <input id="reply-attachments" type="file" name="attachments[]" multiple class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 mt-1" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 10MB per file. Anda bisa memilih beberapa file sekaligus.</p>
                    <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                </div>
                
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3">
                        {{ __('Kirim Balasan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
