<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Rahlety</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <h1 class="text-4xl font-bold text-blue-600">🌊 Rahlety</h1>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Admin Dashboard</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Sign in to access the admin panel
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Admin Email
                    </label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="admin@rahlety.com">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Enter admin password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                        </span>
                        Sign in to Dashboard
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Demo Access</span>
                    </div>
                </div>

                <div class="mt-6 bg-gray-50 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Quick Access (Demo)</h4>
                    <p class="text-xs text-gray-600 mb-3">For demo purposes, any email/password combination will work.</p>
                    <div class="text-xs text-gray-500">
                        <p><strong>Email:</strong> admin@rahlety.com</p>
                        <p><strong>Password:</strong> admin123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 text-center">
        <p class="text-xs text-gray-500">
            © 2025 Rahlety Travel Platform. All rights reserved.
        </p>
        <p class="text-xs text-gray-400 mt-1">
            Admin Dashboard - Secure Access Only
        </p>
    </div>
</body>
</html>