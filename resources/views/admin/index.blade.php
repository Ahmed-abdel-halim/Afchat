@extends('layouts.admin')

@section('title', 'نظرة عامة')

@section('content')
<div class="space-y-8">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Setups Stat -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 group transition-all duration-300">
            <div class="flex items-start justify-between mb-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">إجمالي القفشات</p>
                <button class="text-gray-200 hover:text-gray-400">
                    <i class="fa-solid fa-ellipsis text-xs"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-500 shrink-0">
                    <i class="fa-solid fa-quote-right text-lg"></i>
                </div>

                <h3 class="text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100">{{ $stats['setups_count'] }}</h3>
                
                <div class="px-2 py-1 bg-green-500/10 text-green-500 rounded-lg text-[10px] font-black flex items-center gap-1 shrink-0">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i>
                    <span>12%+</span>
                </div>
            </div>
            
            <div class="text-left mt-4">
                <p class="text-[9px] font-bold text-gray-300">منذ الشهر الماضي</p>
            </div>
        </div>

        <!-- Replies Stat -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 group transition-all duration-300">
            <div class="flex items-start justify-between mb-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">إجمالي الردود</p>
                <button class="text-gray-200 hover:text-gray-400">
                    <i class="fa-solid fa-ellipsis text-xs"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-50 dark:bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-500 shrink-0">
                    <i class="fa-solid fa-comments text-lg"></i>
                </div>

                <h3 class="text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100">{{ $stats['punchlines_count'] }}</h3>
                
                <div class="px-2 py-1 bg-green-500/10 text-green-500 rounded-lg text-[10px] font-black flex items-center gap-1 shrink-0">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i>
                    <span>8%+</span>
                </div>
            </div>
            
            <div class="text-left mt-4">
                <p class="text-[9px] font-bold text-gray-300">منذ الشهر الماضي</p>
            </div>
        </div>

        <!-- Users Stat -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 group transition-all duration-300">
            <div class="flex items-start justify-between mb-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">المستخدمين</p>
                <button class="text-gray-200 hover:text-gray-400">
                    <i class="fa-solid fa-ellipsis text-xs"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500 shrink-0">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>

                <h3 class="text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100">{{ $stats['users_count'] }}</h3>
                
                <div class="px-2 py-1 bg-green-500/10 text-green-500 rounded-lg text-[10px] font-black flex items-center gap-1 shrink-0">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i>
                    <span>15%+</span>
                </div>
            </div>
            
            <div class="text-left mt-4">
                <p class="text-[9px] font-bold text-gray-300">منذ الشهر الماضي</p>
            </div>
        </div>

        <!-- Comments Stat -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 group transition-all duration-300">
            <div class="flex items-start justify-between mb-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">التعليقات</p>
                <button class="text-gray-200 hover:text-gray-400">
                    <i class="fa-solid fa-ellipsis text-xs"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-pink-50 dark:bg-pink-500/10 rounded-2xl flex items-center justify-center text-pink-500 shrink-0">
                    <i class="fa-solid fa-comment-dots text-lg"></i>
                </div>

                <h3 class="text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100">{{ $stats['comments_count'] }}</h3>
                
                <div class="px-2 py-1 bg-blue-500/10 text-blue-500 rounded-lg text-[10px] font-black flex items-center gap-1 shrink-0">
                    <i class="fa-solid fa-clock text-[8px]"></i>
                    <span>مباشر</span>
                </div>
            </div>
            
            <div class="text-left mt-4">
                <p class="text-[9px] font-bold text-gray-300">تحديث تلقائي</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Users and Comments Stats -->
        <div class="bg-white dark:bg-darkCard rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <h4 class="text-xl font-black">المستخدمين والتعليقات</h4>
                    <i class="fa-solid fa-users-viewfinder text-gray-300"></i>
                </div>
                <button class="text-gray-200 hover:text-gray-400">
                    <i class="fa-solid fa-ellipsis text-xs"></i>
                </button>
            </div>

            <div class="flex items-center gap-8 mb-8 text-sm">
                <div class="text-center">
                    <p class="text-gray-400 font-bold text-[10px] mb-1">المستخدمين</p>
                    <p class="text-gray-900 dark:text-white font-black text-2xl">{{ $stats['users_count'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-400 font-bold text-[10px] mb-1">إجمالي التفاعل</p>
                    <p class="text-green-500 font-black text-2xl">{{ $stats['users_count'] + $stats['comments_count'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-400 font-bold text-[10px] mb-1">التعليقات</p>
                    <p class="text-red-500 font-black text-2xl">{{ $stats['comments_count'] }}</p>
                </div>
            </div>

            <div class="h-[250px] relative">
                <canvas id="usersChart"></canvas>
            </div>
            
            <div class="mt-6 flex justify-center items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-teal-400"></span>
                    <span class="text-xs font-bold text-gray-400">التعليقات</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <span class="text-xs font-bold text-gray-400">المستخدمين</span>
                </div>
            </div>
        </div>

        <!-- Setups and Punchlines Stats -->
        <div class="bg-white dark:bg-darkCard rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-black">إحصائيات القفشات والردود</h4>
                <button class="text-gray-200 hover:text-gray-400">
                    <i class="fa-solid fa-ellipsis text-xs"></i>
                </button>
            </div>

            <div class="flex items-center gap-8 mb-8 text-sm">
                <div class="text-center">
                    <p class="text-gray-400 font-bold text-[10px] mb-1">إجمالي الردود</p>
                    <p class="text-gray-900 dark:text-white font-black text-2xl">{{ $stats['punchlines_count'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-400 font-bold text-[10px] mb-1">إجمالي النشاط</p>
                    <p class="text-green-500 font-black text-2xl">{{ $stats['setups_count'] + $stats['punchlines_count'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-400 font-bold text-[10px] mb-1">إجمالي القفشات</p>
                    <p class="text-red-500 font-black text-2xl">{{ $stats['setups_count'] }}</p>
                </div>
            </div>

            <div class="h-[250px] relative">
                <canvas id="offersChart"></canvas>
            </div>

            <div class="mt-6 flex justify-center items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                    <span class="text-xs font-bold text-gray-400">القفشات</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-teal-400"></span>
                    <span class="text-xs font-bold text-gray-400">الردود</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Latest Setups Table -->
        <div class="bg-white dark:bg-darkCard rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden flex flex-col">
            <div class="p-8 flex items-center justify-between border-b border-gray-100 dark:border-white/5">
                <div>
                    <h4 class="text-lg font-black">أحدث القفشات</h4>
                    <p class="text-gray-400 text-[10px] font-bold mt-1">آخر قفشات تم إضافتها</p>
                </div>
                <a href="{{ route('admin.setups') }}" class="text-[10px] font-black text-amber-500 hover:underline">عرض الكل</a>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($latest_setups as $setup)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/2 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 font-bold shrink-0 text-xs">
                                        {{ mb_substr($setup->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="mr-3 overflow-hidden">
                                        <p class="text-sm font-black text-gray-800 dark:text-gray-200 truncate">{{ $setup->text }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold truncate">بواسطة: {{ $setup->user->name ?? 'مجهول' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-left shrink-0">
                                <span class="px-2 py-1 bg-teal-500/10 text-teal-600 rounded-lg text-[9px] font-black">
                                    {{ $setup->punchlines_count }} رد
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-8 py-12 text-center text-gray-400 font-bold">لا يوجد قفشات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Replies List -->
        <div class="bg-white dark:bg-darkCard rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden flex flex-col">
            <div class="p-8 flex items-center justify-between border-b border-gray-100 dark:border-white/5">
                <div>
                    <h4 class="text-lg font-black">أحدث الردود</h4>
                    <p class="text-gray-400 text-[10px] font-bold mt-1">آخر ردود المحاربين</p>
                </div>
                <a href="{{ route('admin.punchlines') }}" class="text-[10px] font-black text-purple-500 hover:underline">عرض الكل</a>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($latest_punchlines as $punchline)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/2 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-600 font-bold shrink-0 text-xs">
                                        {{ mb_substr($punchline->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="mr-3 overflow-hidden">
                                        <p class="text-sm font-black text-gray-800 dark:text-gray-200 truncate">{{ $punchline->text }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold truncate">بواسطة: {{ $punchline->user->name ?? 'مجهول' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-left shrink-0">
                                <p class="text-[10px] font-black text-gray-300">{{ $punchline->created_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-8 py-12 text-center text-gray-400 font-bold">لا يوجد ردود</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Users List -->
        <div class="bg-white dark:bg-darkCard rounded-[2rem] shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden flex flex-col">
            <div class="p-8 flex items-center justify-between border-b border-gray-100 dark:border-white/5">
                <div>
                    <h4 class="text-lg font-black">مستخدمين جدد</h4>
                    <p class="text-gray-400 text-[10px] font-bold mt-1">أحدث المنضمين للمنصة</p>
                </div>
                <a href="{{ route('admin.users') }}" class="text-[10px] font-black text-blue-500 hover:underline">عرض الكل</a>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($latest_users as $user)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/2 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="w-9 h-9 rounded-xl object-cover shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 font-bold shrink-0 text-xs">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="mr-3">
                                        <p class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-left shrink-0">
                                <p class="text-[10px] font-black text-gray-300">{{ $user->created_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-8 py-12 text-center text-gray-400 font-bold">لا يوجد مستخدمين</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    // Offers Chart (Setups & Punchlines)
    const offersCtx = document.getElementById('offersChart').getContext('2d');
    new Chart(offersCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'القفشات',
                    data: {!! json_encode($setups_history) !!},
                    backgroundColor: '#a855f7',
                    borderRadius: 4,
                },
                {
                    label: 'الردود',
                    data: {!! json_encode($punchlines_history) !!},
                    backgroundColor: '#2dd4bf',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { family: 'Cairo' }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: textColor, font: { family: 'Cairo' } } }
            }
        }
    });

    // Users Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'المستخدمين',
                    data: {!! json_encode($users_history) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                },
                {
                    label: 'التعليقات',
                    data: {!! json_encode($comments_history) !!},
                    borderColor: '#2dd4bf',
                    backgroundColor: 'rgba(45, 212, 191, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#2dd4bf',
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { family: 'Cairo' }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: textColor, font: { family: 'Cairo' } } }
            }
        }
    });
</script>
@endpush
@endsection
