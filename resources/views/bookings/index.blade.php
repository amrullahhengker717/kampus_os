<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Classroom Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="glass-panel p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Booking Calendar</h3>
                    @can('create bookings')
                    <x-primary-button>
                        {{ __('New Booking') }}
                    </x-primary-button>
                    @endcan
                </div>

                <div id="calendar" class="min-h-[600px] text-gray-800 dark:text-gray-200"></div>
            </div>

        </div>
    </div>

    <!-- Initialize FullCalendar -->
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new window.Calendar(calendarEl, {
                plugins: [ window.dayGridPlugin, window.interactionPlugin ],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                editable: true,
                selectable: true,
                select: function(info) {
                    alert('Selected ' + info.startStr + ' to ' + info.endStr + '\nYou can hook this up to open a booking modal!');
                },
                events: [
                    @foreach($bookings as $booking)
                    {
                        title: '{{ $booking->room->name }} - {{ $booking->user->name }}',
                        start: '{{ $booking->start_time }}',
                        end: '{{ $booking->end_time }}',
                        color: '{{ $booking->status === 'approved' ? '#10b981' : '#f59e0b' }}'
                    },
                    @endforeach
                ]
            });
            
            calendar.render();
        });
    </script>
</x-app-layout>
