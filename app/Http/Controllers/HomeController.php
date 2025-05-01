<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Fleet;
use App\Models\UserValidation;
use Illuminate\Support\Facades\DB;
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
            $totalUsers = User::count()-1;
            $totalBookings = Booking::where('is_cancelled',null)->count();
            $totalInvoices = Invoice::count();
            $ToBePaidInvoices = Invoice::where('payment_status','pending')->count();
            $totalFleets = Fleet::where('status','active')->count();
            $totalCustomers = User::role('User')->count();
            $totalFleetProviders = User::role('FP')->count();
            // to be paid invoices sum of amount
            $pendingInvoices = Invoice::where('payment_status', 'pending')->get();
            $pendingBookingIds = $pendingInvoices->pluck('booking_id')->unique();
            $totalPendingAmount = Booking::whereIn('id', $pendingBookingIds)->sum('total_price');
            // Paid invoices sum of amount
            $paidInvoices = Invoice::where('payment_status', 'paid')->get();
            $paidBookingIds = $paidInvoices->pluck('booking_id')->unique();
            $totalPaidAmount = Booking::whereIn('id', $paidBookingIds)->sum('total_price');
            //user verification requests count
            $verification_requests = UserValidation::where('status','pending')->count();
            $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, SUM(fee_amount) as total')
            ->whereNull('is_cancelled')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

            $revenueByMonth = [];
            for ($i = 1; $i <= 12; $i++) {
                $revenueByMonth[] = isset($monthlyRevenue[$i]) ? (int) $monthlyRevenue[$i] : 0;
            }
            return view('home', compact('totalUsers', 'totalCustomers','totalFleetProviders','totalFleets','ToBePaidInvoices','totalBookings', 'totalInvoices','totalPendingAmount','totalPaidAmount','verification_requests','revenueByMonth'));
        }

        elseif (Auth::user()->hasRole('FP')) {
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


            $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, SUM(fp_amount) as total')
            ->where('fp_id',Auth::user()->id)
            ->whereNull('is_cancelled')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

            $revenueByMonth = [];
            for ($i = 1; $i <= 12; $i++) {
                $revenueByMonth[] = isset($monthlyRevenue[$i]) ? (int) $monthlyRevenue[$i] : 0;
            }

            return view('home', compact('totalFleets','ToBePaidInvoices','totalBookings', 'totalInvoices','totalPendingAmount','totalPaidAmount','revenueByMonth'));
        }
        return view('home');
    }
}
