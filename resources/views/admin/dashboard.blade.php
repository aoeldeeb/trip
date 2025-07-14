@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-gray-600">Welcome to Rahlety Admin Panel - Your travel platform command center</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Users Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['users']['total']) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-sm text-green-600">+{{ $stats['users']['this_month'] }} this month</span>
                    <span class="text-xs text-gray-500"> • {{ $stats['users']['active'] }} active</span>
                </div>
            </div>
        </div>

        <!-- Companies Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-building text-green-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Companies</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['companies']['approved']) }}/{{ number_format($stats['companies']['total']) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    @if($stats['companies']['pending'] > 0)
                        <span class="text-sm text-orange-600">{{ $stats['companies']['pending'] }} pending approval</span>
                    @else
                        <span class="text-sm text-green-600">All companies approved</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bookings Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-purple-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Bookings</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['bookings']['total']) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-sm text-blue-600">{{ $stats['bookings']['today'] }} today</span>
                    <span class="text-xs text-gray-500"> • {{ $stats['bookings']['this_month'] }} this month</span>
                </div>
            </div>
        </div>

        <!-- Revenue Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['bookings']['total_revenue'], 2) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-sm text-green-600">{{ $stats['bookings']['confirmed'] }} confirmed bookings</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Agents Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-tie text-indigo-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Agents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['agents']['total']) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-sm text-green-600">${{ number_format($stats['agents']['total_commissions'], 2) }} total commissions</span>
                </div>
            </div>
        </div>

        <!-- Trips Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-map-marked-alt text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Trips</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['trips']['active']) }}/{{ number_format($stats['trips']['total']) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-sm text-blue-600">{{ $stats['trips']['upcoming'] }} upcoming</span>
                </div>
            </div>
        </div>

        <!-- Reviews Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Reviews</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['reviews']['total']) }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-sm text-yellow-600">⭐ {{ $stats['reviews']['average_rating'] ?: 'N/A' }} avg rating</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Bookings</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_bookings as $booking)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->trip->title ?? 'Unknown Trip' }}</p>
                                <p class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($booking->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-4">
                        <p class="text-gray-500 text-center">No recent bookings</p>
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-3 bg-gray-50">
                <a href="{{ route('admin.bookings') }}" class="text-sm text-blue-600 hover:text-blue-800">View all bookings →</a>
            </div>
        </div>

        <!-- Pending Company Approvals -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pending Company Approvals</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pending_companies as $company)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $company->name }}</p>
                                <p class="text-sm text-gray-500">{{ $company->email }}</p>
                                <p class="text-xs text-gray-400">Registered {{ $company->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.companies.approve', $company->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.companies.reject', $company->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-4">
                        <p class="text-gray-500 text-center">No pending approvals</p>
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-3 bg-gray-50">
                <a href="{{ route('admin.companies') }}" class="text-sm text-blue-600 hover:text-blue-800">View all companies →</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-users text-2xl text-blue-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Manage Users</span>
                </a>
                <a href="{{ route('admin.companies') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-building text-2xl text-green-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Manage Companies</span>
                </a>
                <a href="{{ route('admin.commissions') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-dollar-sign text-2xl text-yellow-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Pay Commissions</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chart-bar text-2xl text-purple-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">View Reports</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection