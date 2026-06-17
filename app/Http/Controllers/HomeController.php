<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Fleet;
use App\Models\UserValidation;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->hasRole('FP')) {
            $totalBookings = Booking::where('is_cancelled',null)
            ->where('fp_id',Auth::user()->id)
            ->count();

            $totalInvoices = Invoice::where('fp_id',Auth::user()->id)->count();

            $ToBePaidInvoices = Invoice::where('payment_status','pending')
            ->where('fp_id',Auth::user()->id)
            ->count();

            $totalFleets = Fleet::where('status','active')
            ->where('user_id',Auth::user()->id)
            ->count();
           
            // to be paid invoices sum of amount
            $pendingInvoices = Invoice::where('payment_status', 'pending')
            ->where('fp_id',Auth::user()->id)->get();
            $pendingBookingIds = $pendingInvoices
            ->pluck('booking_id')
            ->unique();
            $totalPendingAmount = Booking::whereIn('id', $pendingBookingIds)
            ->sum('total_price');
            
            
            // Paid invoices sum of amount
            $paidInvoices = Invoice::where('payment_status', 'paid')
            ->where('fp_id',Auth::user()->id)
            ->get();
            $paidBookingIds = $paidInvoices
            ->pluck('booking_id')
            ->unique();
            $totalPaidAmount = Booking::whereIn('id', $paidBookingIds)
            ->sum('total_price');


            $revenueByMonth = $this->buildMonthlyRevenue(
                ['fp_id' => Auth::user()->id],
                'fp_amount'
            );

            return view('home', compact('totalFleets','ToBePaidInvoices','totalBookings', 'totalInvoices','totalPendingAmount','totalPaidAmount','revenueByMonth'));
        }
        return view('home');
    }

    public function adminDashboard()
    {
        $totalUsers = User::count() - 1;
        $totalBookings = Booking::where('is_cancelled', null)->count();
        $totalInvoices = Invoice::count();
        $ToBePaidInvoices = Invoice::where('payment_status', 'pending')->count();
        $totalFleets = Fleet::where('status', 'active')->count();
        $totalCustomers = User::role('User')->count();
        $totalFleetProviders = User::role('FP')->count();

        $pendingInvoices = Invoice::where('payment_status', 'pending')->get();
        $pendingBookingIds = $pendingInvoices->pluck('booking_id')->unique();
        $totalPendingAmount = Booking::whereIn('id', $pendingBookingIds)->sum('total_price');

        $paidInvoices = Invoice::where('payment_status', 'paid')->get();
        $paidBookingIds = $paidInvoices->pluck('booking_id')->unique();
        $totalPaidAmount = Booking::whereIn('id', $paidBookingIds)->sum('total_price');

        $verification_requests = UserValidation::where('status', 'pending')->count();
        $revenueByMonth = $this->buildMonthlyRevenue([], 'fee_amount');

        return view('home', compact(
            'totalUsers',
            'totalCustomers',
            'totalFleetProviders',
            'totalFleets',
            'ToBePaidInvoices',
            'totalBookings',
            'totalInvoices',
            'totalPendingAmount',
            'totalPaidAmount',
            'verification_requests',
            'revenueByMonth'
        ));
    }

    private function buildMonthlyRevenue(array $conditions, string $amountColumn): array
    {
        $query = Booking::whereNull('is_cancelled');

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        $monthlyRevenue = $query->get()
            ->groupBy(fn ($booking) => Carbon::parse($booking->created_at)->month)
            ->map(fn ($bookings) => (int) $bookings->sum($amountColumn))
            ->all();

        $revenueByMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueByMonth[] = $monthlyRevenue[$i] ?? 0;
        }

        return $revenueByMonth;
    }
}
