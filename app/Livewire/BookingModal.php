<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingModal extends Component
{
    public $isOpen = false;
    public $roomId;
    public $title;
    public $startTime;
    public $endTime;

    protected $listeners = ['openBookingModal'];

    protected $rules = [
        'roomId' => 'required|exists:rooms,id',
        'title' => 'required|string|max:255',
        'startTime' => 'required|date',
        'endTime' => 'required|date|after:startTime',
    ];

    public function openBookingModal($start, $end)
    {
        $this->reset(['roomId', 'title']);
        $this->startTime = Carbon::parse($start)->format('Y-m-d\TH:i');
        $this->endTime = Carbon::parse($end)->format('Y-m-d\TH:i');
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function bookRoom()
    {
        $this->validate();

        // Check for conflicts
        $conflict = Booking::where('room_id', $this->roomId)
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                      ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                      ->orWhere(function ($q) {
                          $q->where('start_time', '<=', $this->startTime)
                            ->where('end_time', '>=', $this->endTime);
                      });
            })->exists();

        if ($conflict) {
            $this->addError('roomId', 'This room is already booked for the selected time.');
            return;
        }

        Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $this->roomId,
            'title' => $this->title,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'status' => 'pending' // Admin needs to approve
        ]);

        $this->isOpen = false;
        
        // Notify parent to refresh calendar
        $this->dispatch('bookingCreated');
    }

    public function render()
    {
        $rooms = Room::with('building')->get();
        return view('livewire.booking-modal', compact('rooms'));
    }
}
