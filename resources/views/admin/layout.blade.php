<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Rahlety</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 overflow-y-auto bg-blue-900">
                <div class="flex items-center flex-shrink-0 px-4">
                    <h1 class="text-xl font-bold text-white">🌊 Rahlety Admin</h1>
                </div>
                <div class="mt-8 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt mr-3 h-6 w-6"></i>
                            Dashboard
                        </a>

                        <!-- Users -->
                        <a href="{{ route('admin.users') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-users mr-3 h-6 w-6"></i>
                            Users
                        </a>

                        <!-- Companies -->
                        <a href="{{ route('admin.companies') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.companies') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-building mr-3 h-6 w-6"></i>
                            Companies
                        </a>

                        <!-- Agents -->
                        <a href="{{ route('admin.agents') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.agents') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-user-tie mr-3 h-6 w-6"></i>
                            Agents
                        </a>

                        <!-- Bookings -->
                        <a href="{{ route('admin.bookings') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bookings') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-calendar-check mr-3 h-6 w-6"></i>
                            Bookings
                        </a>

                        <!-- Commissions -->
                        <a href="{{ route('admin.commissions') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.commissions') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-dollar-sign mr-3 h-6 w-6"></i>
                            Commissions
                        </a>

                        <!-- Reports -->
                        <a href="{{ route('admin.reports') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports') ? 'bg-blue-800 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fas fa-chart-bar mr-3 h-6 w-6"></i>
                            Reports
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-blue-800 p-4">
                    <div class="flex items-center">
                        <div class="text-blue-200 text-sm">
                            <p class="font-medium">Admin User</p>
                            <p class="text-xs">admin@rahlety.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75"
                 @click="sidebarOpen = false"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-blue-900">
                <!-- Mobile sidebar content (same as desktop) -->
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
                <button @click="sidebarOpen = true" class="h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>
            </div>
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>