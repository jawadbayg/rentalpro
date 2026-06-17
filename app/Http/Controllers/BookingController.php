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
use App\Models\PaymentHistory;
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

        if ($this->hasFleetBookingConflict(
            $validated['fleet_id'],
            $validated['from_date'],
            $validated['to_date']
        )) {
            return response()->json([
                'success' => false,
                'message' => 'This vehicle is already booked for part of the selected dates. Please choose different dates.',
            ], 422);
        }

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
            'due_date' => $booking->to_date,
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
            ->where('customer_id',$auth_id)
            ->where('payment_status','pending')->get();
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

        $invoice = Invoice::where('booking_id',$id)->first();
        if($invoice){
            $invoice->delete();
        }

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
        $vehicleId = $request->input('id');

        if (! $fromDate && ! $toDate) {
            return response()->json([
                'available' => true,
                'message' => 'Please select dates.',
            ]);
        }

        if ($fromDate && $toDate) {
            if ($toDate < $fromDate) {
                return response()->json([
                    'available' => false,
                    'message' => 'To date must be on or after from date.',
                ]);
            }

            $hasConflict = $this->hasFleetBookingConflict($vehicleId, $fromDate, $toDate);

            return response()->json([
                'available' => ! $hasConflict,
                'message' => $hasConflict
                    ? 'This vehicle is already booked for part of the selected dates. Please choose different dates.'
                    : 'Vehicle is available for these dates.',
            ]);
        }

        $date = $fromDate ?: $toDate;
        $hasConflict = $this->hasFleetBookingConflict($vehicleId, $date, $date);

        return response()->json([
            'available' => ! $hasConflict,
            'message' => $hasConflict
                ? 'Vehicle is not available on this date.'
                : 'Vehicle is available on this date.',
        ]);
    }

    private function hasFleetBookingConflict(int $fleetId, string $fromDate, string $toDate, ?int $excludeBookingId = null): bool
    {
        $query = Booking::whereNull('is_cancelled')
            ->where('fleet_id', $fleetId)
            ->whereDate('from_date', '<=', $toDate)
            ->whereDate('to_date', '>=', $fromDate);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }
    public function paymentSuccessChanges($booking_id, array $paymentDetails = [])
    {
        $booking = Booking::where('id', $booking_id)->first();

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json(['message' => 'Booking is already paid.'], 422);
        }

        $booking->payment_status = 'paid';
        $booking->save();

        $invoice = Invoice::where('booking_id', $booking_id)->first();
        if ($invoice) {
            $invoice->payment_status = 'paid';
            $invoice->save();
        }

        $this->paymentHistoryStore($booking, $invoice, $paymentDetails);

        return response()->json(['message' => 'Payment status updated to paid.']);
    }

    public function showCheckout($booking_id)
    {
        $booking = Booking::with(['fleet', 'fp'])
            ->where('id', $booking_id)
            ->where('customer_id', Auth::id())
            ->whereNull('is_cancelled')
            ->where('payment_status', '!=', 'paid')
            ->firstOrFail();

        return view('payments.checkout', compact('booking'));
    }

    public function processPayment(Request $request, $booking_id)
    {
        $booking = Booking::where('id', $booking_id)
            ->where('customer_id', Auth::id())
            ->whereNull('is_cancelled')
            ->where('payment_status', '!=', 'paid')
            ->firstOrFail();

        $validated = $request->validate([
            'card_holder_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'card_number' => ['required', 'regex:/^[\d\s]{13,23}$/'],
            'card_expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvv' => ['required', 'digits_between:3,4'],
        ], [
            'card_holder_name.regex' => 'Name on card should only contain letters and spaces.',
            'card_number.regex' => 'Please enter a valid card number.',
            'card_expiry.regex' => 'Expiry must be in MM/YY format.',
            'card_cvv.digits_between' => 'CVV must be 3 or 4 digits.',
        ]);

        $cardNumber = preg_replace('/\s+/', '', $validated['card_number']);
        $cardLastFour = substr($cardNumber, -4);

        $paymentDetails = [
            'payer_name' => $validated['card_holder_name'],
            'payment_method' => 'card',
            'reference_no' => '**** **** **** '.$cardLastFour,
        ];

        $invoice = Invoice::where('booking_id', $booking->id)->first();

        $booking->payment_status = 'paid';
        $booking->save();

        if ($invoice) {
            $invoice->payment_status = 'paid';
            $invoice->save();
        }

        $this->paymentHistoryStore($booking, $invoice, $paymentDetails);

        return redirect()
            ->route('checkout.success', $booking->id)
            ->with('success', 'Payment completed successfully.');
    }

    public function paymentSuccessPage($booking_id)
    {
        $booking = Booking::with('fleet')
            ->where('id', $booking_id)
            ->where('customer_id', Auth::id())
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $payment = PaymentHistory::where('booking_id', $booking->id)->latest()->first();

        return view('payments.success', compact('booking', 'payment'));
    }

    public function paymentHistoryStore($booking, $invoice, array $paymentDetails = [])
    {
        if ($booking && $invoice) {
            PaymentHistory::create([
                'booking_id' => $booking->id,
                'invoice_id' => $invoice->id,
                'customer_id' => $booking->customer_id,
                'fp_id' => $booking->fp_id,
                'total_price' => $booking->total_price,
                'payer_name' => $paymentDetails['payer_name'] ?? null,
                'payment_method' => $paymentDetails['payment_method'] ?? null,
                'reference_no' => $paymentDetails['reference_no'] ?? null,
            ]);
        }
    }
        
    public function paymentHistoryIndex(){
        if (Auth::user()->hasRole('Admin')) {
            $payments = PaymentHistory::with(['booking', 'customer', 'fleetProvider'])->latest()->get();
        } elseif (Auth::user()->hasRole('FP')) {
            $payments = PaymentHistory::with(['booking', 'customer', 'fleetProvider'])
                ->where('fp_id', Auth::user()->id)
                ->latest()
                ->get();
        } else {
            $payments = collect();
        }

        return view('payments.index', compact('payments'));
    }
    }
