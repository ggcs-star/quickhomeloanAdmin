<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-100 overflow-y-auto">

    <div class="flex min-h-screen">

        <!-- ================= MOBILE HEADER ================= -->
        <div
            class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-6 h-6 text-indigo-600"></i>
                <span class="font-bold text-gray-800">QuickHomeLoan</span>
            </div>

            <button onclick="toggleSidebar()">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- ================= MOBILE OVERLAY ================= -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden">
        </div>

        <!-- ================= SIDEBAR ================= -->
        <aside id="sidebar" class="w-64 bg-white border-r flex-col fixed inset-y-0 left-0 z-50
                  transform -translate-x-full md:translate-x-0
                  transition-transform duration-300
                  md:flex">

            <!-- LOGO -->
            <div class="px-6 py-5 flex items-center gap-2 flex-shrink-0">
                <i data-lucide="layers" class="w-6 h-6 text-indigo-600"></i>
                <h2 class="text-xl font-bold text-gray-800">QuickHomeLoan</h2>
            </div>

            <!-- MENU -->
            <nav class="mt-6 space-y-1 px-3 flex-1 overflow-y-auto">
                @php
                    $menu = [
                        ['Dashboard', 'dashboard', 'home'],
                        [
                            'Education Center',
                            'icon' => 'home',
                            'children' => [
                                ['Modules', 'modules.index'],
                                ['Contents', 'contents.index'],
                            ]
                        ],
                        ['Calculators','calculators.index','Calculator'],
                        ['Reels', 'reels.index', 'video'],
                        ['Leads', 'leads', 'users'],

                        ['Projects', 'projects', 'building'],
                        ['Lenders', 'lenders', 'landmark'],
                        ['Partners (DSA)', 'partners.index', 'handshake'],
                        ['Documents', 'documents', 'file-text'],
                        ['Tasks', 'tasks', 'check-square'],
                        ['Campaigns', 'campaigns', 'megaphone'],
                        ['Interest Rates', 'rates.index', 'percent'],
                        ['Analytics', 'analytics', 'bar-chart-2'],
                        ['Settings', 'settings', 'settings'],
                    ];
                @endphp

                @foreach($menu as $m)

                    {{-- NORMAL LINK --}}
                    @if(!isset($m['children']))
                                <a href="{{ route($m[1]) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                           {{ request()->routeIs($m[1])
                            ? 'bg-indigo-50 text-indigo-700 font-semibold'
                            : 'text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-600' }}">

                                    <i data-lucide="{{ $m[2] }}" class="w-5 h-5"></i>
                                    <span>{{ $m[0] }}</span>
                                </a>

                                {{-- DROPDOWN --}}
                    @else
                        <div x-data="{ open: false }">

                            <!-- Parent -->
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-gray-800 hover:bg-indigo-50">

                                <div class="flex items-center gap-3">
                                    <i data-lucide="{{ $m['icon'] }}" class="w-5 h-5"></i>
                                    <span>{{ $m[0] }}</span>
                                </div>

                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>

                            <!-- Children -->
                            <div x-show="open" class="ml-8 mt-1 space-y-1">
                                @foreach($m['children'] as $child)
                                    <a href="{{ route($child[1]) }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-indigo-50">
                                        {{ $child[0] }}
                                    </a>
                                @endforeach
                            </div>

                        </div>
                    @endif

                @endforeach
            </nav>

            <!-- USER -->
            <div class="px-5 py-4 flex items-center gap-3 border-t flex-shrink-0">
                <div
                    class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-semibold text-indigo-700">
                    AU
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Admin User</p>
                    <p class="text-xs text-gray-500">Admin</p>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT ================= -->
        <main class="flex-1 ml-0 md:ml-64 overflow-y-auto pt-16 md:pt-0">
            <div class="p-4 sm:p-6 min-h-screen">
                @yield('content')
            </div>
        </main>

    </div>

    <!-- ================= JS ================= -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        lucide.createIcons();
    </script>

</body>

</html>