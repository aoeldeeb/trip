<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\Company;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
                'active' => User::whereHas('bookings', function($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                })->count(),
            ],
            'companies' => [
                'total' => Company::count(),
                'approved' => Company::approved()->count(),
                'pending' => Company::pending()->count(),
            ],
            'agents' => [
                'total' => Agent::count(),
                'active' => Agent::whereHas('referredUsers')->count(),
                'total_commissions' => Commission::sum('amount'),
                'unpaid_commissions' => Commission::unpaid()->sum('amount'),
            ],
            'trips' => [
                'total' => Trip::count(),
                'active' => Trip::active()->count(),
                'upcoming' => Trip::upcoming()->count(),
            ],
            'bookings' => [
                'total' => Booking::count(),
                'confirmed' => Booking::confirmed()->count(),
                'pending' => Booking::pending()->count(),
                'cancelled' => Booking::cancelled()->count(),
                'today' => Booking::whereDate('created_at', Carbon::today())->count(),
                'this_month' => Booking::whereMonth('created_at', Carbon::now()->month)->count(),
                'total_revenue' => Booking::confirmed()->sum('total_price'),
            ],
            'reviews' => [
                'total' => Review::count(),
                'average_rating' => round(Review::avg('rating'), 2),
                'this_month' => Review::whereMonth('created_at', Carbon::now()->month)->count(),
            ],
        ];

        // Recent activities
        $recent_bookings = Booking::with(['user:id,name', 'trip:id,title', 'company:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_reviews = Review::with(['user:id,name', 'trip:id,title'])
            ->latest()
            ->limit(5)
            ->get();

        $pending_companies = Company::pending()
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings', 'recent_reviews', 'pending_companies'));
    }

    /**
     * Show users management page.
     */
    public function users(Request $request)
    {
        $users = User::with('agent:id,name')
            ->withCount(['bookings', 'reviews'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Show companies management page.
     */
    public function companies(Request $request)
    {
        $companies = Company::withCount(['trips', 'bookings'])
            ->when($request->status, function($query, $status) {
                if ($status === 'approved') {
                    $query->approved();
                } elseif ($status === 'pending') {
                    $query->pending();
                }
            })
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('admin.companies', compact('companies'));
    }

    /**
     * Approve company.
     */
    public function approveCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Company approved successfully!');
    }

    /**
     * Reject company.
     */
    public function rejectCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'Company rejected!');
    }

    /**
     * Show agents management page.
     */
    public function agents(Request $request)
    {
        $agents = Agent::withCount(['referredUsers', 'commissions'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('admin.agents', compact('agents'));
    }

    /**
     * Show bookings management page.
     */
    public function bookings(Request $request)
    {
        $bookings = Booking::with(['user:id,name', 'trip:id,title', 'company:id,name', 'agent:id,name'])
            ->when($request->status, function($query, $status) {
                $query->byStatus($status);
            })
            ->when($request->date_from, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(20);

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Show commissions management page.
     */
    public function commissions(Request $request)
    {
        $commissions = Commission::with(['agent:id,name', 'booking.trip:id,title', 'booking.user:id,name'])
            ->when($request->status, function($query, $status) {
                if ($status === 'paid') {
                    $query->paid();
                } elseif ($status === 'unpaid') {
                    $query->unpaid();
                }
            })
            ->latest()
            ->paginate(20);

        return view('admin.commissions', compact('commissions'));
    }

    /**
     * Mark commission as paid.
     */
    public function payCommission($id)
    {
        $commission = Commission::findOrFail($id);
        $commission->markAsPaid();

        return redirect()->back()->with('success', 'Commission marked as paid!');
    }

    /**
     * Show reports page.
     */
    public function reports()
    {
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(total_price) as revenue')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topTrips = Trip::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        $topAgents = Agent::withCount('referredUsers')
            ->with(['commissions' => function($query) {
                $query->selectRaw('agent_id, sum(amount) as total_earned')
                      ->groupBy('agent_id');
            }])
            ->orderBy('referred_users_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports', compact('monthlyBookings', 'topTrips', 'topAgents'));
    }
}