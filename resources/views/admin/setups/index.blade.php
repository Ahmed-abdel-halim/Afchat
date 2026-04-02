@extends('layouts.admin')

@section('title', 'إدارة القفشات')

@section('content')
<div class="space-y-6" x-data="{ 
    showAddModal: false, 
    showEditModal: false,
    editingSetup: { id: '', text: '' },
    openEditModal(setup) {
        this.editingSetup = { id: setup.id, text: setup.text };
        this.showEditModal = true;
    }
}">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="البحث في القفشات..." 
                   class="w-full bg-white dark:bg-darkCard border border-gray-100 dark:border-white/5 rounded-2xl pr-12 pl-4 py-3 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
        </div>
        <button @click="showAddModal = true" class="px-6 py-3 bg-sky-500 text-white rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-sky-500/20 hover:scale-[1.02] transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة قفشة جديدة</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="card-glass rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">نص القفشة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">بواسطة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-left">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @if($setups->count() > 0)
                        @foreach($setups as $setup)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                    {{ $setup->text }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center text-[10px] font-black uppercase">
                                        {{ substr($setup->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $setup->user->name ?? 'مستخدم غير معروف' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400 font-bold">{{ $setup->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal({ id: '{{ $setup->id }}', text: '{{ addslashes($setup->text) }}' })" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form id="delete-setup-{{ $setup->id }}" action="{{ route('admin.setups.delete', $setup->id) }}" method="POST" onsubmit="return window.confirmDelete('delete-setup-{{ $setup->id }}', 'هل أنت متأكد من حذف هذه القفشة؟ سيتم حذف جميع الردود والتعليقات المرتبطة بها.')">
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا يوجد قفشات हालياً.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($setups->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5">
            {{ $setups->links() }}
        </div>
        @endif
    </div>

    <!-- Add Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm"
             x-cloak>
            
            <div class="flex min-h-full items-center justify-center p-4">
                <div @click.away="showAddModal = false" 
                     class="bg-white dark:bg-darkCard border border-gray-200 dark:border-white/10 w-full max-w-lg rounded-3xl p-6 shadow-2xl scale-up">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">إضافة قفشة جديدة</h3>
                        <button @click="showAddModal = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.setups.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">محتوى القفشة</label>
                            <textarea name="text" required rows="4" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-sky-500/20 transition-all" placeholder="اكتب بداية القفشة هنا..."></textarea>
                        </div>
                        
                        <div class="pt-4 flex gap-3">
                            <button type="submit" class="flex-1 py-4 bg-sky-500 text-white rounded-2xl font-bold shadow-lg shadow-sky-500/20 hover:opacity-90 transition-all">حفظ</button>
                            <button type="button" @click="showAddModal = false" class="flex-1 py-4 bg-gray-50 dark:bg-white/5 rounded-2xl font-bold hover:bg-gray-100 dark:hover:bg-white/10 transition-all">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Edit Modal -->
    <template x-teleport="body">
        <div x-show="showEditModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm"
             x-cloak>
            
            <div class="flex min-h-full items-center justify-center p-4">
                <div @click.away="showEditModal = false" 
                     class="bg-white dark:bg-darkCard border border-gray-200 dark:border-white/10 w-full max-w-lg rounded-3xl p-6 shadow-2xl scale-up">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">تعديل القفشة</h3>
                        <button @click="showEditModal = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/setups') }}/' + editingSetup.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">محتوى القفشة</label>
                            <textarea name="text" x-model="editingSetup.text" required rows="4" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500/20 transition-all" placeholder="اكتب بداية القفشة هنا..."></textarea>
                        </div>
                        
                        <div class="pt-4 flex gap-3">
                            <button type="submit" class="flex-1 py-4 bg-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/20 hover:opacity-90 transition-all">تحديث</button>
                            <button type="button" @click="showEditModal = false" class="flex-1 py-4 bg-gray-50 dark:bg-white/5 rounded-2xl font-bold hover:bg-gray-100 dark:hover:bg-white/10 transition-all">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
