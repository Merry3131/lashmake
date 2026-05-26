@extends('admin.layouts.admin_menu')

@section('title', 'Специалисты | Lashmake Admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-slate-800">Специалисты (Мастера)</h1>
        <a href="{{ route('admin.specialists.create') }}" class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl text-sm transition-all shadow-sm">
            + Добавить мастера
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase bg-slate-50/50">
                <th class="px-6 py-3">ФИО Специалиста</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Квалификация</th>
                <th class="px-6 py-3">Опыт</th>
                <th class="px-6 py-3">Описание</th>
                <th class="px-6 py-3 text-right">Действия</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($specialists as $specialist)
                <tr class="hover:bg-slate-50/50 transition-colors">

                    <td class="px-6 py-4 font-semibold text-slate-900">
                        @if($specialist->user)
                            {{ $specialist->user->last_name }} {{ $specialist->user->first_name }}
                        @else
                            <span class="text-rose-500">Пользователь удален</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-slate-500">
                        {{ $specialist->user->email ?? '—' }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-medium">
                            {{ $specialist->level->display_name ?? 'Не указан' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $specialist->experience ?? '—' }}</td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $specialist->bio ?? '—' }}</td>

                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.specialists.edit', $specialist->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Изменить
                        </a>
                        <form action="{{ route('admin.specialists.destroy', $specialist->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
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
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        Мастера салона еще не добавлены.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
