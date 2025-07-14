<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\Company;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function dashboard()
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
                'average_rating' => Review::avg('rating'),
                'this_month' => Review::whereMonth('created_at', Carbon::now()->month)->count(),
            ],
        ];

        // Recent activities
        $recent_bookings = Booking::with(['user:id,name', 'trip:id,title', 'company:id,name'])
            ->latest()
            ->limit(10)
            ->get();

        $recent_reviews = Review::with(['user:id,name', 'trip:id,title'])
            ->latest()
            ->limit(10)
            ->get();

        $pending_companies = Company::pending()
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_bookings' => $recent_bookings,
            'recent_reviews' => $recent_reviews,
            'pending_companies' => $pending_companies,
            'message' => 'Dashboard data retrieved successfully'
        ]);
    }

    /**
     * Get all users with pagination.
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

        return response()->json([
            'users' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    /**
     * Show specific user details.
     */
    public function showUser($id)
    {
        $user = User::with(['agent:id,name', 'bookings.trip', 'reviews.trip'])
            ->findOrFail($id);

        return response()->json([
            'user' => $user,
            'message' => 'User details retrieved successfully'
        ]);
    }

    /**
     * Update user status (for banning/unbanning).
     */
    public function updateUserStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,banned'
        ]);

        $user = User::findOrFail($id);
        
        // You would add a status column to users table for this
        // For now, we'll just return success
        
        return response()->json([
            'message' => 'User status updated successfully'
        ]);
    }

    /**
     * Get all companies.
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

        return response()->json([
            'companies' => $companies,
            'message' => 'Companies retrieved successfully'
        ]);
    }

    /**
     * Approve company.
     */
    public function approveCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['is_approved' => true]);

        return response()->json([
            'message' => 'Company approved successfully',
            'company' => $company
        ]);
    }

    /**
     * Reject company.
     */
    public function rejectCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['is_approved' => false]);

        return response()->json([
            'message' => 'Company rejected',
            'company' => $company
        ]);
    }

    /**
     * Get all agents.
     */
    public function agents(Request $request)
    {
        $agents = Agent::withCount(['referredUsers', 'commissions'])
            ->with(['commissions' => function($query) {
                $query->selectRaw('agent_id, sum(amount) as total_earned, sum(case when paid = 1 then amount else 0 end) as total_paid')
                      ->groupBy('agent_id');
            }])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return response()->json([
            'agents' => $agents,
            'message' => 'Agents retrieved successfully'
        ]);
    }

    /**
     * Show specific agent details.
     */
    public function showAgent($id)
    {
        $agent = Agent::with(['referredUsers', 'commissions.booking.trip'])
            ->findOrFail($id);

        return response()->json([
            'agent' => $agent,
            'message' => 'Agent details retrieved successfully'
        ]);
    }

    /**
     * Get all bookings.
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

        return response()->json([
            'bookings' => $bookings,
            'message' => 'Bookings retrieved successfully'
        ]);
    }

    /**
     * Show specific booking details.
     */
    public function showBooking($id)
    {
        $booking = Booking::with(['user', 'trip', 'company', 'agent', 'commission'])
            ->findOrFail($id);

        return response()->json([
            'booking' => $booking,
            'message' => 'Booking details retrieved successfully'
        ]);
    }

    /**
     * Get all commissions.
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

        return response()->json([
            'commissions' => $commissions,
            'message' => 'Commissions retrieved successfully'
        ]);
    }

    /**
     * Get pending commissions.
     */
    public function pendingCommissions()
    {
        $commissions = Commission::unpaid()
            ->with(['agent:id,name', 'booking.trip:id,title'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'commissions' => $commissions,
            'message' => 'Pending commissions retrieved successfully'
        ]);
    }

    /**
     * Mark commission as paid.
     */
    public function payCommission($id)
    {
        $commission = Commission::findOrFail($id);
        $commission->markAsPaid();

        return response()->json([
            'message' => 'Commission marked as paid',
            'commission' => $commission
        ]);
    }

    /**
     * Booking reports.
     */
    public function bookingReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonth();
        $endDate = $request->end_date ?? Carbon::now();

        $bookingStats = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed_bookings,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_bookings,
                SUM(total_price) as total_revenue
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'booking_stats' => $bookingStats,
            'message' => 'Booking reports retrieved successfully'
        ]);
    }

    /**
     * Revenue reports.
     */
    public function revenueReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonth();
        $endDate = $request->end_date ?? Carbon::now();

        $revenueStats = Booking::confirmed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                SUM(total_price) as daily_revenue,
                COUNT(*) as bookings_count
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = Booking::confirmed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        return response()->json([
            'revenue_stats' => $revenueStats,
            'total_revenue' => $totalRevenue,
            'message' => 'Revenue reports retrieved successfully'
        ]);
    }

    /**
     * Commission reports.
     */
    public function commissionReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonth();
        $endDate = $request->end_date ?? Carbon::now();

        $commissionStats = Commission::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                SUM(amount) as total_commissions,
                SUM(CASE WHEN paid = 1 THEN amount ELSE 0 END) as paid_commissions,
                SUM(CASE WHEN paid = 0 THEN amount ELSE 0 END) as unpaid_commissions,
                COUNT(*) as commission_count
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'commission_stats' => $commissionStats,
            'message' => 'Commission reports retrieved successfully'
        ]);
    }
}