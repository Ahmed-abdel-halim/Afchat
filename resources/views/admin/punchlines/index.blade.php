@extends('layouts.admin')

@section('title', 'إدارة الردود')

@section('content')
<div class="space-y-6" x-data="{ 
    showDetails: false, 
    selectedPunchline: null,
    openDetails(punchline) {
        this.selectedPunchline = punchline;
        this.showDetails = true;
    },
    deleteComment(id) {
        const form = document.getElementById('delete-comment-form');
        form.action = `/admin/comments/${id}`;
        
        if (window.confirmDelete('delete-comment-form', 'هل أنت متأكد من حذف هذا التعليق؟')) {
            form.submit();
        }
    }
}">
    <!-- Hidden Delete Form for Comments -->
    <form id="delete-comment-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="البحث في الردود..." 
                   class="w-full bg-white dark:bg-darkCard border border-gray-100 dark:border-white/5 rounded-2xl pr-12 pl-4 py-3 focus:ring-2 focus:ring-sky-500/20 outline-none transition-all">
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-glass rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">الرد</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">القفشة المرتبطة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">بواسطة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">الضحكات</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-left">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @if($punchlines->count() > 0)
                        @foreach($punchlines as $punchline)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="max-w-xs overflow-hidden text-ellipsis whitespace-nowrap font-bold text-gray-800 dark:text-gray-200">
                                    {{ $punchline->text }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-bold">
                                {{ \Illuminate\Support\Str::limit($punchline->setup->text ?? 'غير مرغوب', 30) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $author = $punchline->user ?? null;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center text-[10px] font-black uppercase">
                                        @if($author && $author->avatar)
                                            <img src="{{ $author->avatar }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            {{ substr($author->name ?? 'U', 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $author->name ?? 'مستخدم غير معروف' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-green-500/10 text-green-500 rounded-full text-xs font-bold">
                                    {{ $punchline->laughs_count ?? 0 }} ضحكة
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Details Button -->
                                    <button @click="openDetails({{ $punchline->toJson() }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-500/10 text-sky-500 hover:bg-sky-500 hover:text-white transition-all shadow-sm" title="عرض التفاصيل">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>

                                    <form id="delete-punchline-{{ $punchline->id }}" action="{{ route('admin.punchlines.delete', $punchline->id) }}" method="POST" onsubmit="return window.confirmDelete('delete-punchline-{{ $punchline->id }}', 'هل أنت متأكد من حذف هذا الرد؟ سيتم حذف جميع التعليقات المرتبطة به.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/10 text-red-500 hover:bg-red-50 hover:text-white transition-all">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا يوجد ردود حالياً.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($punchlines->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5">
            {{ $punchlines->links() }}
        </div>
        @endif
    </div>

    <!-- Details Modal -->
    <div x-show="showDetails" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        
        <div @click.away="showDetails = false" 
             class="bg-white dark:bg-darkCard w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-white/5 flex flex-col max-h-[85vh]">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-50 dark:border-white/5 flex items-center justify-between bg-gray-50/50 dark:bg-white/2">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-sky-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-sky-500/20">
                        <i class="fa-solid fa-comments text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black">تفاصيل الرد</h3>
                        <p class="text-xs font-bold text-gray-400">استطلاع الرد والتعليقات المصاحبة</p>
                    </div>
                </div>
                <button @click="showDetails = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar">
                <!-- Original Punchline -->
                <div class="bg-sky-50/50 dark:bg-sky-500/5 p-6 rounded-[2rem] border border-sky-100/50 dark:border-sky-500/10 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-quote-right text-8xl text-sky-600"></i>
                    </div>
                    <div class="relative z-10 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full shrink-0 border-2 border-white dark:border-darkBg shadow-sm overflow-hidden bg-gray-200">
                             <template x-if="selectedPunchline && selectedPunchline.user && selectedPunchline.user.avatar">
                                <img :src="selectedPunchline.user.avatar" class="w-full h-full object-cover">
                             </template>
                             <template x-if="!(selectedPunchline && selectedPunchline.user && selectedPunchline.user.avatar)">
                                <div class="w-full h-full flex items-center justify-center font-bold text-xs" x-text="selectedPunchline ? selectedPunchline.user.name.substring(0,1) : 'U'"></div>
                             </template>
                        </div>
                        <div>
                            <p class="text-xs font-black text-sky-600 mb-1" x-text="selectedPunchline ? selectedPunchline.user.name : ''"></p>
                            <p class="text font-bold text-gray-800 dark:text-gray-100 leading-relaxed" x-text="selectedPunchline ? selectedPunchline.text : ''"></p>
                        </div>
                    </div>
                </div>

                <!-- Comments List -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-sm font-black flex items-center gap-2 text-gray-800 dark:text-gray-100">
                            <i class="fa-solid fa-comment-dots text-sky-500"></i>
                            التعليقات
                            <span class="text-[10px] bg-sky-500/10 dark:bg-sky-500/20 px-2 py-0.5 rounded-full text-sky-600 dark:text-sky-400" x-text="selectedPunchline ? selectedPunchline.comments.length : 0"></span>
                        </h4>
                    </div>

                    <div class="space-y-3">
                        <template x-if="selectedPunchline && selectedPunchline.comments.length > 0">
                            <template x-for="comment in selectedPunchline.comments" :key="comment.id">
                                <div class="flex gap-3 items-start p-4 bg-gray-50/20 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl hover:border-sky-500/30 transition-all group">
                                    <div class="w-8 h-8 rounded-full shrink-0 border border-gray-100 dark:border-white/10 overflow-hidden bg-gray-100 dark:bg-white/10">
                                        <template x-if="comment.user && comment.user.avatar">
                                            <img :src="comment.user.avatar" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!(comment.user && comment.user.avatar)">
                                            <div class="w-full h-full flex items-center justify-center font-bold text-[10px] dark:text-gray-400" x-text="comment.user ? comment.user.name.substring(0,1) : 'U'"></div>
                                        </template>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-black text-gray-400 group-hover:text-sky-500" x-text="comment.user ? comment.user.name : 'مستخدم'"></span>
                                            
                                            <!-- Delete Comment Button -->
                                            <button @click="deleteComment(comment.id)" class="w-6 h-6 flex items-center justify-center rounded-md bg-red-500/5 text-red-400 hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100" title="حذف التعليق">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-300" x-text="comment.body"></p>
                                    </div>
                                </div>
                            </template>
                        </template>

                        <template x-if="!selectedPunchline || selectedPunchline.comments.length === 0">
                            <div class="text-center py-8 opacity-40">
                                <i class="fa-solid fa-ghost text-4xl mb-3 block"></i>
                                <p class="text-xs font-bold">لا توجد تعليقات على هذا الرد بعد.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 bg-gray-50/50 dark:bg-white/2 border-t border-gray-50 dark:border-white/5">
                <button @click="showDetails = false" class="w-full py-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 rounded-2xl font-black text-sm transition-all text-gray-600 dark:text-gray-300">
                    إغلاق النافذة
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
