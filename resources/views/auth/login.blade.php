<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | QuickHomeLoan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">

            <!-- LEFT PANEL -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-10 lg:p-14 hidden lg:flex flex-col">
                <div class="flex items-center gap-3 mb-10">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center">
                        <i class="fas fa-home text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">QuickHomeLoan</h1>
                        <p class="text-sm text-gray-600">Admin Control Panel</p>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Secure Admin Control Panel
                </h2>

                <p class="text-gray-700 leading-relaxed mb-10">
                    Manage leads, loan disbursals, partners, campaigns and real-time analytics
                    from one secure dashboard.
                </p>

                <div class="mt-auto text-sm text-gray-600 border-t pt-6">
                    © 2025 QuickHomeLoan · Internal Access Only
                </div>
            </div>

            <!-- RIGHT PANEL (FORM) -->
            <div class="p-10 lg:p-14">
                <div class="max-w-md mx-auto">

                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Admin Login
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Sign in to continue to the dashboard
                    </p>

                    {{-- Laravel Error --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- REAL LARAVEL LOGIN FORM -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                EMAIL ADDRESS
                            </label>
                            <div class="relative">
                                <input type="email" name="email" required class="w-full px-4 py-3 pl-11 rounded-lg border border-gray-300 bg-gray-50
                                       focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       focus:outline-none transition" placeholder="admin@quickhomelon.in">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="far fa-envelope"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                PASSWORD
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required class="w-full px-4 py-3 pl-11 pr-11 rounded-lg border border-gray-300 bg-gray-50
               focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               focus:outline-none transition" placeholder="••••••••">

                                <!-- Lock icon (left) -->
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </div>

                                <!-- Eye icon (right) -->
                                <div onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer">
                                    <i id="eyeIcon" class="fas fa-eye"></i>
                                </div>
                            </div>


                        </div>

                        <!-- Submit -->
                        <button type="submit" class="w-full py-3.5 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600
                               text-white font-semibold hover:from-blue-700 hover:to-indigo-700
                               focus:ring-4 focus:ring-blue-200 transition shadow-md hover:shadow-lg">
                            Sign In to Dashboard →
                        </button>
                    </form>

                    <div class="mt-10 text-sm text-gray-500 text-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Authorized personnel only
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        function togglePassword() {
            const password = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>