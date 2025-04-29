<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Fleet;
use Illuminate\Support\Facades\Auth; 
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
class BookingController extends Controller
{
    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fp_id' => 'required|integer',
            'fleet_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'total_price' => 'required|numeric',
            'payment_status' => 'required|string',
        ]);
        do {
            $randomNumber = 'RP' . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        } while (Booking::where('booking_no', $randomNumber)->exists());

        $booking = Booking::create([
            'fp_id' => $validated['fp_id'],
            'fleet_id' => $validated['fleet_id'],
            'customer_id' => $validated['customer_id'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'total_price' => $validated['total_price'],
            'payment_status' => $validated['payment_status'],
            'status' => 'pending', 
            'booking_no' => $randomNumber,
        ]);
        $customer = \App\Models\User::find($validated['customer_id']);

        if ($customer) {
            Mail::to($customer->email)->send(new BookingConfirmation($booking));
        }

        return response()->json(['success' => true, 'message' => 'Booking confirmed']);
    }
    public function customer_index()
    {
        $auth_id = Auth::user()->id;
        
        $bookings = Booking::with('fleet')
        ->where('customer_id', $auth_id)
        ->where('is_cancelled' , null)
        ->get();

        return view('customer-bookings.index', compact('bookings'));
    }
    public function cancel($id)
    {
        $auth_id = Auth::user()->id;
        $booking = Booking::findOrFail($id);
        if (Auth::id() !== $booking->customer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->is_cancelled = 1;
        $booking->status = 'cancelled';
        $booking->save();

        return response()->json(['message' => 'Booking cancelled successfully.']);
    }
    

    public function invoice($id)
    {
        $booking = Booking::with('fleet')->findOrFail($id);

        $customer_id = $booking->customer_id;
        $customer = \App\Models\User::find($customer_id);

        $fp_id = $booking->fp_id;
        $fp = \App\Models\User::find($fp_id);

        $fleet_id = $booking->fleet_id;
        $fleet = Fleet::find($fleet_id);

        $pdf = Pdf::loadView('invoice.invoice', compact('booking','customer','fp','fleet'));
        return $pdf->stream("invoice-{$booking->booking_no}.pdf");
    }
    

}
