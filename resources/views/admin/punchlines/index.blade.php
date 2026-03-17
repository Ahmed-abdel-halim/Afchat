@extends('layouts.admin')

@section('title', 'إدارة الردود')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="البحث في الردود..." 
                   class="w-full bg-white dark:bg-darkCard border border-gray-100 dark:border-white/5 rounded-2xl pr-12 pl-4 py-3 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
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
                                    $author = $punchline->user ?? $punchline->setup->user ?? null;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center text-[10px] font-black uppercase">
                                        {{ substr($author->name ?? 'U', 0, 1) }}
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
                                    <form action="{{ route('admin.punchlines.delete', $punchline->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all">
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
</div>
@endsection
