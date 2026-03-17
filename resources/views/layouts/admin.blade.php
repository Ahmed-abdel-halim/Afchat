<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- Immediate Theme Application - Must be at the top -->
    <script>
        (function() {
            // Dark Mode
            const storedDark = localStorage.getItem('darkMode');
            const isDark = storedDark !== null ? storedDark === 'true' : true; 
            if (isDark) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');

            // Sidebar State - Prevent Layout Shift
            const storedSidebar = localStorage.getItem('sidebarOpen');
            const isMobile = window.innerWidth < 1024;
            const sidebarOpen = storedSidebar !== null ? storedSidebar === 'true' : !isMobile;
            
            // Add a class to handle initial width without transition
            if (!isMobile) {
                document.documentElement.classList.add(sidebarOpen ? 'sidebar-opened' : 'sidebar-closed');
            }
        })();
    </script>
    <style>
        /* CSS to run before Tailwind loads */
        html.dark { background: #0d1117 !important; color: white; }
        html { background: #f0f5fa; transition: none !important; }
        body { visibility: hidden; } /* Hide body until theme is ready */
        html.dark body, html body { visibility: visible; }
    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'لوحة التحكم' }} - Smart School</title>
    
    <!-- Scripts & Styles Load -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        darkBg: '#0d1117',
                        darkCard: '#161b22',
                    },
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer components {
            .card-glass {
                @apply bg-white/80 dark:bg-[#161b22]/80 backdrop-blur-md border border-gray-100 dark:border-white/5 shadow-sm;
            }
            .scale-up {
                animation: scaleUp 0.3s ease-out forwards;
            }
            .no-transition {
                transition: none !important;
            }
        }

        @keyframes scaleUp {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { @apply bg-transparent; }
        ::-webkit-scrollbar-thumb { @apply bg-gray-300/50 dark:bg-white/10 rounded-full hover:bg-gray-400 dark:hover:bg-white/20; }

        /* Sidebar transition - only active after load */
        .sidebar-transition {
            transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Essential widths before any JS loads */
        @media (min-width: 1024px) {
            html.sidebar-opened aside { width: 16rem !important; }
            html.sidebar-closed aside { width: 6rem !important; }
            html.sidebar-opened .main-content-wrapper { margin-right: 0; }
        }
    </style>
</head>
<body class="bg-[#f0f5fa] dark:bg-darkBg text-gray-900 dark:text-gray-100 font-cairo transition-colors duration-300" 
      x-data="{ 
        darkMode: document.documentElement.classList.contains('dark'), 
        sidebarOpen: localStorage.getItem('sidebarOpen') !== null ? localStorage.getItem('sidebarOpen') === 'true' : window.innerWidth >= 1024,
        isMobile: window.innerWidth < 1024,
        sidebarInitialized: false,
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            }
        },
        toggleSidebar() {
            this.sidebarInitialized = true; // Enable transitions only on click
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
            document.documentElement.classList.toggle('sidebar-opened', this.sidebarOpen);
            document.documentElement.classList.toggle('sidebar-closed', !this.sidebarOpen);
        },
        init() {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (this.isMobile) this.sidebarOpen = false;
                // Don't auto-open on desktop resize to respect user choice
            });
        }
      }">

    <div class="flex h-screen overflow-hidden relative bg-[#f0f5fa] dark:bg-darkBg">
        
        <!-- Sidebar Backdrop (Mobile only) -->
        <div x-show="sidebarOpen && isMobile" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-md z-[60] lg:hidden"
             x-cloak></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 right-0 z-[70] h-full bg-white dark:bg-darkCard border-l border-gray-100 dark:border-white/5 lg:relative lg:z-20 lg:translate-x-0 overflow-hidden"
               :class="{
                   'w-64 translate-x-0': sidebarOpen,
                   'w-0 translate-x-full lg:w-24 lg:translate-x-0': !sidebarOpen,
                   'sidebar-transition': sidebarInitialized
               }"
               x-cloak>
            
            <div class="flex flex-col h-full overflow-hidden" :class="!sidebarOpen && !isMobile ? 'items-center' : ''">
                <!-- Logo -->
                <div class="px-6 py-8 flex items-center justify-between min-w-[240px]" :class="!sidebarOpen && !isMobile ? 'justify-center min-w-0' : ''">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-amber-500 dark:bg-sky-500 rounded-2xl flex items-center justify-center shadow-lg transform rotate-3">
                            <span class="text-white text-2xl font-black leading-none">أ</span>
                        </div>
                        <span x-show="sidebarOpen || isMobile" x-transition.opacity class="mr-3 font-bold text-xl tracking-tight whitespace-nowrap">قـفـشات</span>
                    </div>
                    <!-- Close button on mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto custom-scrollbar">
                    <a href="{{ route('admin.index') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.index') ? 'bg-amber-500/10 dark:bg-sky-500/10 text-amber-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-chart-pie text-lg {{ request()->routeIs('admin.index') ? 'text-amber-500 dark:text-sky-500' : 'text-gray-400 group-hover:text-amber-500 dark:group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">نظرة عامة</span>
                    </a>

                    <a href="{{ route('admin.setups') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.setups') ? 'bg-amber-500/10 dark:bg-sky-500/10 text-amber-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-quote-right text-lg {{ request()->routeIs('admin.setups') ? 'text-amber-500 dark:text-sky-500' : 'text-gray-400 group-hover:text-amber-500 dark:group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">القفشات</span>
                    </a>

                    <a href="{{ route('admin.punchlines') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.punchlines') ? 'bg-amber-500/10 dark:bg-sky-500/10 text-amber-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-comments text-lg {{ request()->routeIs('admin.punchlines') ? 'text-amber-500 dark:text-sky-500' : 'text-gray-400 group-hover:text-amber-500 dark:group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">الردود</span>
                    </a>

                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.users') ? 'bg-amber-500/10 dark:bg-sky-500/10 text-amber-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-users text-lg {{ request()->routeIs('admin.users') ? 'text-amber-500 dark:text-sky-500' : 'text-gray-400 group-hover:text-amber-500 dark:group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">المستخدمين</span>
                    </a>
                </nav>

                <!-- Footer (Logout) -->
                <div class="p-4 border-t border-gray-100 dark:border-white/5">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 rounded-2xl bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all font-bold group">
                            <div class="w-8 flex justify-center shrink-0">
                                <i class="fa-solid fa-right-from-bracket group-hover:scale-110 transition-transform"></i>
                            </div>
                            <span x-show="sidebarOpen || isMobile" class="mr-3 whitespace-nowrap">تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden relative">
            
            <!-- Header -->
            <header class="h-20 flex items-center justify-between px-4 md:px-8 bg-white/80 dark:bg-darkCard/80 backdrop-blur-md border-b border-gray-100 dark:border-white/5 z-40">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" 
                            class="p-3 mr-0 md:-mr-2 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-amber-500/10 dark:hover:bg-sky-500/10 hover:text-amber-600 dark:hover:text-sky-400 transition-all">
                        <i class="fa-solid fa-bars-staggered text-xl" :class="sidebarOpen && !isMobile ? 'rotate-90' : ''"></i>
                    </button>
                    <h1 class="mr-4 md:mr-6 text-lg md:text-xl font-black truncate max-w-[150px] md:max-w-none">@yield('title', 'لوحة التحكم')</h1>
                </div>

                <div class="flex items-center space-x-3 space-x-reverse">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                        <i :class="darkMode ? 'fa-solid fa-sun text-amber-500' : 'fa-solid fa-moon text-indigo-500'"></i>
                    </button>

                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 space-x-reverse p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                            <div class="w-9 h-9 bg-gradient-to-tr from-amber-500 to-orange-400 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                                {{ substr(auth()->user()->name ?? 'Admin', 0, 1) }}
                            </div>
                            <span class="hidden md:block font-bold text-sm">{{ auth()->user()->name ?? 'المدير' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute left-0 mt-2 w-48 bg-white dark:bg-darkCard rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 p-2"
                             x-cloak>
                             <div class="px-4 py-2 border-b border-gray-50 dark:border-white/5 mb-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">المستخدم</p>
                                <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? 'المدير' }}</p>
                             </div>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all font-bold text-sm">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>تسجيل الخروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                @if(session('success'))
                <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 p-4 rounded-2xl flex items-center gap-3 font-bold scale-up">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 p-4 rounded-2xl scale-up">
                    <div class="flex items-center gap-3 font-bold mb-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>حدث خطأ ما:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm opacity-80">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
