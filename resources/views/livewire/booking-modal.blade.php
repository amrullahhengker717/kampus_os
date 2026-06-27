<div>
    <x-modal wire:model="isOpen" name="booking-modal" show="isOpen" focusable>
        <form wire:submit.prevent="bookRoom" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Book Classroom / Facility') }}
            </h2>

            <!-- Title / Purpose -->
            <div class="mt-4">
                <x-input-label for="title" :value="__('Booking Purpose / Title')" />
                <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Room Selection -->
            <div class="mt-4">
                <x-input-label for="roomId" :value="__('Select Room')" />
                <select wire:model="roomId" id="roomId" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">-- Choose a Facility --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->building->name }} - {{ $room->name }} (Cap: {{ $room->capacity }})</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('roomId')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <!-- Start Time -->
                <div>
                    <x-input-label for="startTime" :value="__('Start Time')" />
                    <x-text-input wire:model="startTime" id="startTime" class="block mt-1 w-full" type="datetime-local" required />
                    <x-input-error :messages="$errors->get('startTime')" class="mt-2" />
                </div>

                <!-- End Time -->
                <div>
                    <x-input-label for="endTime" :value="__('End Time')" />
                    <x-text-input wire:model="endTime" id="endTime" class="block mt-1 w-full" type="datetime-local" required />
                    <x-input-error :messages="$errors->get('endTime')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeModal">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Submit Request') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</div>
