<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Fleet;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth; 
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpParser\Node\Expr\FuncCall;
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
        
        $feeAmount = round($validated['total_price'] * 0.20);

        $fpAmount = round($validated['total_price'] * 0.80);

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
            'fee_amount' => $feeAmount,
            'fp_amount' => $fpAmount,
        ]);
        $this->generateInvoice($booking);

        return response()->json(['success' => true, 'message' => 'Booking confirmed']);
    }

    public function generateInvoice($booking)
    {
        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'booking_no' => $booking->booking_no,
            'fp_id' => $booking->fp_id,
            'fleet_id' => $booking->fleet_id,
            'customer_id' => $booking->customer_id,
            'payment_status' => $booking->payment_status,
            'due_date' => now()->addDays(7)->toDateString(),
        ]);
        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);
        $customer = User::find($booking->customer_id);
        if ($customer) {
            Mail::to($customer->email)->send(new BookingConfirmation($booking, $pdf->output()));
        }
    }

    public function invoiceIndex(){

        $auth_id = Auth::user()->id;
        
        if(Auth::user()->hasRole('FP')){
            $invoices = Invoice::with(['booking', 'customer', 'fp', 'fleet'])
            ->where('fp_id',$auth_id)->get();
        }
        elseif(Auth::user()->hasRole('User')){
            $invoices = Invoice::with(['booking', 'customer', 'fp', 'fleet'])
            ->where('customer_id',$auth_id)->get();
        }
        else{
            $invoices = Invoice::with(['booking', 'customer', 'fp', 'fleet'])->get(); //Admin
        }

        return view('invoices.index',compact('invoices'));
    }

    public function invoiceDownload($id)
    {
        $invoice = Invoice::with(['booking', 'customer', 'fp', 'fleet'])->findOrFail($id);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->booking_no . '.pdf');
    }

    public function customer_index()
    {
        $fleet = '';
        $customer = '';
        $invoice_to_be_paid = false;
        $auth_id = Auth::user()->id;
        if (Auth::user()->hasRole('Admin')) {
            $bookings = Booking::with('fleet')->get();
        }
        elseif (Auth::user()->hasRole('FP')) {
            $bookings = Booking::with('fleet')
            ->where('fp_id',$auth_id)
            ->get();
        } else {
            $auth_id = Auth::id();
            $bookings = Booking::with('fleet')
            ->where('customer_id', $auth_id)
            ->whereNull('is_cancelled')
            ->get();
            foreach ($bookings as $booking) {
                $invoices = Invoice::where('booking_id', $booking->id)
                ->where('customer_id',$auth_id)->get();
                foreach ($invoices as $invoice) {
                    if ($invoice->payment_status == 'pending') {
                        $invoice_to_be_paid = true;
                }
            }
        }   
    }
        foreach ($bookings as $booking) {
            $customer = User::find($booking->customer_id);
            $fp = User::find($booking->fp_id);
            $fleet = Fleet::find($booking->fleet_id);
        }

        return view('customer-bookings.index', compact('bookings','fleet','customer','invoice_to_be_paid'));
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

    public function checkDate(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($fromDate) {
            $bookingExists = Booking::whereNull('is_cancelled')
                ->whereDate('from_date', '<=', $fromDate)
                ->whereDate('to_date', '>=', $fromDate)
                ->exists();

            if ($bookingExists) {
                return response()->json([
                    'available' => false,
                    'message' => 'Vehicle is not available on this date.'
                ]);
            } else {
                return response()->json([
                    'available' => true,
                    'message' => 'Vehicle is available on this date.'
                ]);
            }
        }

        if ($toDate) {
            $bookingExists = Booking::whereNull('is_cancelled')
                ->whereDate('from_date', '<=', $toDate)
                ->whereDate('to_date', '>=', $toDate)
                ->first();

            // if ($bookingExists) {
            //     if ($bookingExists->from_date > $fromDate && $bookingExists->from_date <= $toDate) {
            //         return response()->json([
            //             'available' => false,
            //             'message' => 'Vehicle is only available up to ' . $bookingExists->from_date,
            //         ]);
            //     }   
            // }
            
            if ($bookingExists) {
                return response()->json([
                    'available' => false,
                    'message' => 'Vehicle is not available on this date.'
                ]);
            } else {
                return response()->json([
                    'available' => true,
                    'message' => 'Vehicle is available on this date.'
                ]);
            }
        }

        return response()->json([
            'available' => true,
            'message' => 'Vehicle is available on this date.'
        ]);
    }

        
    }
