@extends('layouts.admin')

@section('title', 'نظرة عامة')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Setups Stat -->
        <div class="card-glass p-6 rounded-3xl group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">إجمالي القفشات</p>
                    <h3 class="text-3xl font-black">{{ $stats['setups_count'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-500/10 dark:bg-sky-500/10 rounded-2xl flex items-center justify-center text-amber-500 dark:text-sky-500">
                    <i class="fa-solid fa-quote-right text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up ml-1"></i>
                <span>12%+ هذا الشهر</span>
            </div>
        </div>

        <!-- Replies Stat -->
        <div class="card-glass p-6 rounded-3xl group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">إجمالي الردود</p>
                    <h3 class="text-3xl font-black">{{ $stats['punchlines_count'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-500">
                    <i class="fa-solid fa-comments text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up ml-1"></i>
                <span>8%+ هذا الشهر</span>
            </div>
        </div>

        <!-- Users Stat -->
        <div class="card-glass p-6 rounded-3xl group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">المستخدمين</p>
                    <h3 class="text-3xl font-black">{{ $stats['users_count'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-blue-500">
                <i class="fa-solid fa-user-plus ml-1"></i>
                <span>جديد اليوم: 5</span>
            </div>
        </div>

        <!-- Comments Stat -->
        <div class="card-glass p-6 rounded-3xl group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">التعليقات</p>
                    <h3 class="text-3xl font-black">{{ $stats['comments_count'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-pink-500/10 rounded-2xl flex items-center justify-center text-pink-500">
                    <i class="fa-solid fa-comment-dots text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-gray-400">
                <i class="fa-solid fa-clock ml-1"></i>
                <span>تحديث منذ ثواني</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Charts Placeholder -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 card-glass rounded-3xl p-8 h-96 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-chart-line text-3xl text-gray-300"></i>
            </div>
            <h4 class="text-xl font-bold mb-2">إحصائيات النشاط</h4>
            <p class="text-gray-500 max-w-sm">هنا سيتم عرض رسم بياني يوضح معدل إضافة القفشات وتفاعل المستخدمين خلال الأسبوع الماضي.</p>
        </div>

        <div class="card-glass rounded-3xl p-8">
            <h4 class="text-xl font-bold mb-6">إجراءات سريعة</h4>
            <div class="space-y-4">
                <button class="w-full flex items-center justify-between p-4 rounded-2xl bg-amber-500 dark:bg-sky-500 text-white font-bold shadow-lg shadow-amber-500/20 dark:shadow-sky-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span>إضافة سيتاب جديد</span>
                    <i class="fa-solid fa-plus-circle"></i>
                </button>
                <button class="w-full flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5 font-bold hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                    <span>مراجعة التعليقات</span>
                    <i class="fa-solid fa-shield-halved"></i>
                </button>
                <button class="w-full flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5 font-bold hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                    <span>إرسال تنبيه للمستخدمين</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
