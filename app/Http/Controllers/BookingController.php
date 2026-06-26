<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $rooms = Room::with('building')->get();
        $bookings = Booking::with(['user', 'room.building'])->get();
        return view('bookings.index', compact('rooms', 'bookings'));
    }
}
