@extends('admin.layout')

@section('title', 'Companies Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Companies Management</h1>
                <p class="text-gray-600">Manage tour companies, approvals, and their trip offerings</p>
            </div>
            <div class="flex space-x-3">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Total:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $companies->total() }} companies
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search Companies</label>
                    <div class="mt-1 relative">
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or email..."
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Approval Status</label>
                    <select name="status" 
                            id="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Companies</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved Only</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.companies') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Companies List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($companies as $company)
                <li>
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-building text-blue-600 text-lg"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-medium text-gray-900 truncate">
                                            {{ $company->name }}
                                        </h3>
                                        @if($company->is_approved)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope mr-1"></i>
                                            {{ $company->email }}
                                        </div>
                                        @if($company->phone)
                                            <div class="flex items-center">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $company->phone }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marked-alt mr-1"></i>
                                            <span>{{ $company->trips_count ?? 0 }} trips</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            <span>{{ $company->bookings_count ?? 0 }} bookings</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>Joined {{ $company->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @if($company->description)
                                        <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                            {{ Str::limit($company->description, 120) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(!$company->is_approved)
                                    <form action="{{ route('admin.companies.approve', $company->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-150 ease-in-out"
                                                onclick="return confirm('Are you sure you want to approve this company?')">
                                            <i class="fas fa-check mr-1"></i>
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.companies.reject', $company->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-150 ease-in-out"
                                                onclick="return confirm('Are you sure you want to reject this company?')">
                                            <i class="fas fa-times mr-1"></i>
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white">
                                        <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                        Approved
                                    </span>
                                @endif
                                
                                <!-- View Details Button -->
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onclick="showCompanyDetails({{ $company->id }})">
                                    <i class="fas fa-eye mr-1"></i>
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-12 text-center">
                    <div class="max-w-sm mx-auto">
                        <i class="fas fa-building text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No companies found</h3>
                        <p class="text-gray-500">
                            @if(request('search') || request('status'))
                                No companies match your current filters. Try adjusting your search criteria.
                            @else
                                No companies have registered yet.
                            @endif
                        </p>
                        @if(request('search') || request('status'))
                            <div class="mt-4">
                                <a href="{{ route('admin.companies') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Clear filters
                                </a>
                            </div>
                        @endif
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($companies->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $companies->links() }}
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $companies->where('is_approved', true)->count() }}</div>
            <div class="text-sm text-gray-500">Approved</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $companies->where('is_approved', false)->count() }}</div>
            <div class="text-sm text-gray-500">Pending</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-green-600">{{ $companies->sum('trips_count') }}</div>
            <div class="text-sm text-gray-500">Total Trips</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $companies->sum('bookings_count') }}</div>
            <div class="text-sm text-gray-500">Total Bookings</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showCompanyDetails(companyId) {
    // This would typically open a modal or redirect to a details page
    alert('Company details view would open here. Company ID: ' + companyId);
    // In a real implementation, you might do:
    // window.location.href = '/admin/companies/' + companyId;
    // or open a modal with AJAX loaded content
}
</script>
@endpush
@endsection