@extends('layouts.admin')

@section('title', 'الملف الشخصي')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="relative mb-12">
        <div class="h-48 bg-gradient-to-r from-sky-500 to-indigo-600 rounded-[2.5rem] shadow-xl overflow-hidden">
            <div class="absolute top-0 right-0 p-12 opacity-10">
                <i class="fa-solid fa-user-gear text-[10rem] text-white"></i>
            </div>
        </div>
        
        <div class="absolute bottom-2 right-12 flex items-end gap-6 px-4">
            <div class="w-32 h-32 bg-white dark:bg-darkCard rounded-[2rem] p-2 shadow-2xl relative group">
                <div class="w-full h-full bg-gradient-to-tr from-sky-400 to-sky-500 rounded-[1.8rem] flex items-center justify-center text-white text-5xl font-black shadow-inner">
                    {{ substr($user->name, 0, 1) }}
                </div>
            </div>
            
            <div class="mb-4">
                <h2 class="text-3xl font-black text-white drop-shadow-md mb-1">{{ $user->name }}</h2>
                <p class="text-sky-100 font-bold opacity-80 italic">مسؤول النظام</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-16">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="card-glass rounded-3xl p-6">
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">معلومات الحساب</h4>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 leading-none mb-1 uppercase">البريد الإلكتروني</p>
                            <p class="text-sm font-bold truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 leading-none mb-1 uppercase">تاريخ الانضمام</p>
                            <p class="text-sm font-bold">{{ $user->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-600/5 dark:bg-indigo-500/10 border border-indigo-500/20 rounded-3xl p-6">
                <div class="flex items-center gap-3 mb-3 text-indigo-600 dark:text-indigo-400 font-black">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>نصيحة أمان</span>
                </div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 leading-relaxed italic">
                    تأكد من استخدام كلمة مرور قوية تحتوي على رموز وأرقام وحروف كبيرة لضمان أمان لوحة التحكم.
                </p>
            </div>
        </div>

        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card-glass rounded-[2rem] p-8 shadow-sm">
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">الاسم الكامل</label>
                            <div class="relative">
                                <i class="fa-solid fa-user absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl pr-12 pl-4 py-4 outline-none focus:ring-2 focus:ring-sky-500/20 transition-all font-bold">
                            </div>
                            @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">البريد الإلكتروني</label>
                            <div class="relative">
                                <i class="fa-solid fa-at absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl pr-12 pl-4 py-4 outline-none focus:ring-2 focus:ring-sky-500/20 transition-all font-bold">
                            </div>
                            @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fa-solid fa-lock text-gray-300"></i>
                            <h4 class="text-sm font-black uppercase tracking-widest">تغيير كلمة المرور</h4>
                            <span class="text-[10px] text-gray-400 font-bold">(اتركه فارغاً إذا كنت لا تريد التغيير)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">كلمة المرور الجديدة</label>
                                <input type="password" name="password" 
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-4 py-4 outline-none focus:ring-2 focus:ring-sky-500/20 transition-all font-bold">
                                @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-4 py-4 outline-none focus:ring-2 focus:ring-sky-500/20 transition-all font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-4 bg-sky-500 text-white rounded-2xl font-black text-sm shadow-xl shadow-sky-500/20 hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
