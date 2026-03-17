@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="space-y-6" x-data="{ 
    showDetails: false, 
    selectedUser: {},
    openDetails(user) {
        this.selectedUser = user;
        this.showDetails = true;
    }
}">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="البحث عن مستخدم..." 
                   class="w-full bg-white dark:bg-darkCard border border-gray-100 dark:border-white/5 rounded-2xl pr-12 pl-4 py-3 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-glass rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">تاريخ الانضمام</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-left">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @if($users->count() > 0)
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="w-10 h-10 rounded-xl object-cover border-2 border-amber-500/20">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-orange-400 rounded-xl flex items-center justify-center text-white font-black">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm">{{ $user->name }}</span>
                                        <span class="text-xs text-gray-400">#{{ $user->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-bold">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-green-500/10 text-green-600 dark:text-green-400 rounded-full text-[10px] font-black uppercase">نشط</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openDetails({{ $user }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-500/10 text-gray-500 hover:bg-gray-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/10 text-red-500 hover:bg-red-50 hover:text-white transition-all">
                                            <i class="fa-solid fa-ban text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا يوجد مستخدمين حالياً.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- User Details Modal -->
    <div x-show="showDetails" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        
        <div class="bg-white dark:bg-[#161b22] w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden relative border border-white/10" @click.away="showDetails = false">
            <div class="h-32 bg-gradient-to-tr from-amber-500 to-orange-400 relative">
                <button @click="showDetails = false" class="absolute top-6 left-6 w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-2xl flex items-center justify-center text-white transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="px-8 pb-8 -mt-12 relative">
                <div class="flex flex-col items-center text-center">
                    <template x-if="selectedUser.avatar">
                        <img :src="selectedUser.avatar" class="w-24 h-24 rounded-3xl object-cover border-4 border-white dark:border-[#161b22] bg-white shadow-xl mb-4">
                    </template>
                    <template x-if="!selectedUser.avatar">
                        <div class="w-24 h-24 bg-gradient-to-tr from-amber-500 to-orange-400 rounded-3xl flex items-center justify-center text-white text-4xl font-black border-4 border-white dark:border-[#161b22] shadow-xl mb-4">
                            <span x-text="selectedUser.name ? selectedUser.name.substring(0, 1) : ''"></span>
                        </div>
                    </template>

                    <h3 class="text-2xl font-black mb-1" x-text="selectedUser.name"></h3>
                    <p class="text-gray-400 font-bold text-sm mb-6" x-text="selectedUser.email"></p>

                    <div class="grid grid-cols-2 gap-4 w-full mb-8">
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-3xl border border-gray-100 dark:border-white/5">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">تاريخ الانضمام</p>
                            <p class="font-bold text-sm" x-text="selectedUser.created_at ? new Date(selectedUser.created_at).toLocaleDateString('ar-EG') : ''"></p>
                        </div>
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-3xl border border-gray-100 dark:border-white/5">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">الحالة</p>
                            <span class="text-xs font-black text-green-500 uppercase">نشط</span>
                        </div>
                    </div>

                    <div class="w-full space-y-3">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                            <span class="text-xs font-bold text-gray-500">طريقة التسجيل</span>
                            <span class="text-xs font-black text-amber-500" x-text="selectedUser.provider ? selectedUser.provider : 'بريد إلكتروني'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
