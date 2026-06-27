<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Forum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Diskusi Terbaru</h3>
                <x-primary-button x-data @click="$dispatch('open-modal', 'create-thread-modal')">
                    Buat Thread Baru
                </x-primary-button>
            </div>

            <!-- Categories Filter / Legend -->
            <div class="flex space-x-2 mb-6 overflow-x-auto pb-2">
                @foreach($categories as $category)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $category->color }}-100 text-{{ $category->color }}-800 border border-{{ $category->color }}-200 shadow-sm whitespace-nowrap">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>

            @if(session('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('message') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($threads as $thread)
                    <a href="{{ route('forum.show', $thread) }}" class="block glass-panel p-5 hover:-translate-y-1 hover:shadow-lg transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-{{ $thread->category->color }}-100 text-{{ $thread->category->color }}-800">
                                        {{ $thread->category->name }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Oleh {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $thread->title }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $thread->body }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col items-end justify-center ml-4 space-y-2">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    {{ $thread->replies_count }}
                                </div>
                                <div class="flex items-center text-gray-400 text-xs">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ $thread->views }}
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="glass-panel p-8 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Belum ada thread diskusi. Jadilah yang pertama!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $threads->links() }}
            </div>

        </div>
    </div>

    <!-- Create Thread Modal -->
    <x-modal name="create-thread-modal" focusable>
        <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Buat Thread Baru
            </h2>

            <!-- Kategori -->
            <div class="mb-4">
                <x-input-label for="category_id" :value="__('Kategori')" />
                <select id="category_id" name="category_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                    <option value="" disabled selected>Pilih Kategori...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <!-- Judul -->
            <div class="mb-4">
                <x-input-label for="title" :value="__('Judul')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" required placeholder="Apa yang ingin Anda diskusikan?" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Isi -->
            <div class="mb-4">
                <x-input-label for="body" :value="__('Isi Diskusi')" />
                <textarea id="body" name="body" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required placeholder="Tuliskan pemikiran Anda secara detail..."></textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <!-- Lampiran (Attachments) -->
            <div class="mb-4">
                <x-input-label for="thread-attachments" :value="__('Lampirkan File (Opsional)')" />
                <input id="thread-attachments" type="file" name="attachments[]" multiple class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 mt-1" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                <p class="text-xs text-gray-500 mt-1">Maksimal 10MB per file. Anda bisa memilih beberapa file sekaligus.</p>
                <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Posting Thread') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
