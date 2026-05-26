@extends('admin.layouts.admin_menu')

@section('title', 'Категории услуг')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-slate-800">Категории услуг</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl text-sm transition-all shadow-sm">
            + Добавить категорию
        </a>
    </div>

    @if(session('success'))
        <div class="relative mb-6">
            <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
            <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-semibold shadow-sm peer-checked:hidden transition-all">
                <div class="flex items-center gap-3">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <label for="hide-alert-checkbox" class="ms-4 p-1.5 inline-flex items-center justify-center rounded-xl text-emerald-500 hover:bg-emerald-100 hover:text-emerald-900 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </label>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase bg-slate-50/50">
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Отображаемое название</th>
                <th class="px-6 py-3">Ярлык (Slug)</th>
                <th class="px-6 py-3">Описание</th>
                <th class="px-6 py-3 text-right">Действия</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($categories as $category)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">#{{ $category->id }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $category->display_name }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $category->slug }}</td>
                    <td class="px-6 py-4 max-w-xs truncate text-slate-500">{{ $category->description ?? '—' }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Изменить
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-lg transition-colors">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        Категорий пока нет. Создайте первую!
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
