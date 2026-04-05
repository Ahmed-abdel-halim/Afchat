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
            let sidebarOpen = storedSidebar !== null ? storedSidebar === 'true' : !isMobile;
            
            // Add a class to handle initial width without transition
            document.documentElement.classList.add(sidebarOpen ? 'sidebar-opened' : 'sidebar-closed');
        })();
    </script>
    <style>
        /* Immediate Sidebar Content Control - Prevent Jitter */
        html.sidebar-closed aside span { display: none !important; }
        html.sidebar-closed aside .min-w-\[240px\] { min-width: 0 !important; width: 6rem !important; }
        html.sidebar-closed aside nav a { justify-content: center !important; }
        
        /* Immediate Sidebar Widths - Prevent Flickering on load */
        @media (min-width: 1024px) {
            html:not(.sidebar-ready).sidebar-opened aside { width: 16rem !important; }
            html:not(.sidebar-ready).sidebar-closed aside { width: 6rem !important; }
        }
        @media (max-width: 1023px) {
            html:not(.sidebar-ready).sidebar-closed aside { width: 0 !important; }
            html:not(.sidebar-ready).sidebar-opened aside { width: 16rem !important; }
        }

        /* Essential widths before any JS loads */
        @media (min-width: 1024px) {
            html.sidebar-opened aside { width: 16rem; }
            html.sidebar-closed aside { width: 6rem; }
        }

        /* CSS to run before Tailwind loads */
        html.dark { background: #0d1117 !important; color: white; }
        html { background: #f0f5fa; }
        body { opacity: 0; transition: opacity 0.2s ease-in; } 
        html.sidebar-ready body { opacity: 1; }
    </style>
    <script>
        // Mark as ready after a tiny delay to allow for smooth logic transitions
        window.addEventListener('DOMContentLoaded', () => {
             document.documentElement.classList.add('sidebar-ready');
        });
    </script>

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

        /* Global Pagination Styles */
        nav[role="navigation"] span[aria-current="page"] > span {
            @apply bg-sky-500 text-white border-sky-500 !important;
        }
        nav[role="navigation"] a:hover {
            @apply bg-sky-500/10 text-sky-500 !important;
        }

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
                        <div class="w-10 h-10 bg-sky-500 rounded-2xl flex items-center justify-center shadow-lg transform rotate-3">
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
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.index') ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-chart-pie text-lg {{ request()->routeIs('admin.index') ? 'text-sky-500' : 'text-gray-400 group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">نظرة عامة</span>
                    </a>

                    <a href="{{ route('admin.setups') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.setups') ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-quote-right text-lg {{ request()->routeIs('admin.setups') ? 'text-sky-500' : 'text-gray-400 group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">القفشات</span>
                    </a>

                    <a href="{{ route('admin.punchlines') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.punchlines') ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-comments text-lg {{ request()->routeIs('admin.punchlines') ? 'text-sky-500' : 'text-gray-400 group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">الردود</span>
                    </a>

                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.users') ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-users text-lg {{ request()->routeIs('admin.users') ? 'text-sky-500' : 'text-gray-400 group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">المستخدمين</span>
                    </a>

                    <a href="{{ route('admin.profile') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all group whitespace-nowrap {{ request()->routeIs('admin.profile') ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400' : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        <div class="w-8 flex justify-center shrink-0">
                            <i class="fa-solid fa-user-gear text-lg {{ request()->routeIs('admin.profile') ? 'text-sky-500' : 'text-gray-400 group-hover:text-sky-500' }}"></i>
                        </div>
                        <span x-show="sidebarOpen || isMobile" class="mr-3 font-semibold">الملف الشخصي</span>
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
                            class="p-3 mr-0 md:-mr-2 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 transition-all">
                        <i class="fa-solid fa-bars-staggered text-xl" :class="sidebarOpen && !isMobile ? 'rotate-90' : ''"></i>
                    </button>
                    <h1 class="mr-4 md:mr-6 text-lg md:text-xl font-black truncate max-w-[150px] md:max-w-none">@yield('title', 'لوحة التحكم')</h1>
                </div>

                <div class="flex items-center space-x-3 space-x-reverse">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                        <i :class="darkMode ? 'fa-solid fa-sun text-sky-500' : 'fa-solid fa-moon text-indigo-500'"></i>
                    </button>

                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 space-x-reverse p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                            <div class="w-9 h-9 bg-gradient-to-tr from-sky-500 to-sky-400 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
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
                             
                             <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all font-bold text-sm mb-1">
                                <i class="fa-solid fa-user-circle text-sky-500"></i>
                                <span>الملف الشخصي</span>
                             </a>

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
            <div class="flex-1 overflow-y-auto p-4 md:p-8 relative">
                
                <!-- Dynamic Toast Notifications -->
                <div x-data="{ 
                        showSuccess: {{ session('success') ? 'true' : 'false' }},
                        showError: {{ ($errors->any() || session('error')) ? 'true' : 'false' }},
                        init() {
                            if(this.showSuccess) setTimeout(() => this.showSuccess = false, 5000);
                            if(this.showError) setTimeout(() => this.showError = false, 8000);
                        }
                    }"
                    class="fixed top-24 left-6 z-[100] flex flex-col gap-3 max-w-md w-full sm:w-auto"
                    x-cloak>
                    
                    <!-- Success Toast -->
                    @if(session('success'))
                    <div x-show="showSuccess" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full opacity-0"
                         x-transition:enter-end="translate-x-0 opacity-100"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="translate-x-0 opacity-100"
                         x-transition:leave-end="-translate-x-full opacity-0"
                         class="bg-green-600 text-white shadow-2xl p-4 rounded-2xl flex items-center gap-4 group scale-up border border-white/20">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white shrink-0">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-black text-white/70 uppercase tracking-widest mb-1">نجاح العملية</p>
                            <p class="font-bold text-sm leading-tight text-white">{{ session('success') }}</p>
                        </div>
                        <button @click="showSuccess = false" class="text-white/50 hover:text-white transition-colors p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @endif

                    <!-- Error Toast (Themed Green as requested) -->
                    @if($errors->any() || session('error'))
                    <div x-show="showError" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full opacity-0"
                         x-transition:enter-end="translate-x-0 opacity-100"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="translate-x-0 opacity-100"
                         x-transition:leave-end="-translate-x-full opacity-0"
                         class="bg-green-600 text-white shadow-2xl p-4 rounded-2xl flex items-center gap-4 scale-up border border-white/20">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white shrink-0">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-black text-white/70 uppercase tracking-widest mb-1">نجاح العملية</p>
                            <div class="text-sm font-bold leading-tight text-white">
                                @if(session('error'))
                                    <p>{!! session('error') !!}</p>
                                @endif
                                @if($errors->any())
                                    <ul class="list-none">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <button @click="showError = false" class="text-white/50 hover:text-white transition-colors p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Premium Global Confirmation Modal -->
    <div x-data="{ 
            open: false, 
            title: '', 
            message: '', 
            confirmText: 'تأكيد',
            cancelText: 'إلغاء',
            callback: null,
            confirmAction() {
                if (this.callback) this.callback();
                this.open = false;
            }
        }"
        @confirm.window="
            title = $event.detail.title || 'هل أنت متأكد؟';
            message = $event.detail.message || 'لا يمكن التراجع عن هذا الإجراء.';
            confirmText = $event.detail.confirmText || 'تأكيد';
            cancelText = $event.detail.cancelText || 'إلغاء';
            callback = $event.detail.callback;
            open = true;
        "
        class="fixed inset-0 z-[200] overflow-y-auto"
        x-show="open"
        x-cloak>
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
             x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-darkCard p-8 text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 dark:border-white/5"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex flex-col items-center">
                    <!-- Icon Container -->
                    <div class="mx-auto flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-3xl bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 mb-6 scale-up">
                        <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-2xl font-black leading-6 text-gray-900 dark:text-gray-100 mb-4" x-text="title"></h3>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400" x-text="message"></p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="button" 
                            @click="confirmAction()"
                            class="inline-flex w-full justify-center rounded-2xl bg-red-600 px-6 py-4 text-sm font-black text-white shadow-lg shadow-red-600/30 hover:bg-red-700 transition-all sm:w-auto min-w-[120px]">
                        <span x-text="confirmText"></span>
                    </button>
                    <button type="button" 
                            @click="open = false"
                            class="inline-flex w-full justify-center rounded-2xl bg-gray-100 dark:bg-white/5 px-6 py-4 text-sm font-black text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-all sm:w-auto min-w-[120px]">
                        <span x-text="cancelText"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')

    <!-- Modal Helper Script -->
    <script>
        window.confirmDelete = function(formId, message = 'هل أنت متأكد من حذف هذا العنصر؟') {
            window.dispatchEvent(new CustomEvent('confirm', {
                detail: {
                    title: 'تأكيد الحذف',
                    message: message,
                    confirmText: 'حذف الآن',
                    cancelText: 'تراجع',
                    callback: () => {
                        document.getElementById(formId).submit();
                    }
                }
            }));
            return false;
        };
    </script>
</body>
</html>
