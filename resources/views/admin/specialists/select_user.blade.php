@extends('admin.layouts.admin_menu')

@section('title', 'Выбор пользователя')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.specialists.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">← Назад к мастерам</a>
        <h1 class="text-xl font-bold text-slate-800 mt-2">Шаг 1: Выберите пользователя системы</h1>
        <p class="text-sm text-slate-400">Найдите клиента или сотрудника, которого хотите назначить мастером салона.</p>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form action="{{ route('admin.specialists.create') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Введите Фамилию, Имя или номер телефона..."
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all" />
            <button type="submit" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl text-sm transition-all">
                Найти
            </button>
            @if(request('search'))
                <a href="{{ route('admin.specialists.create') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm flex items-center">
                    Сбросить
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase bg-slate-50/50">
                <th class="px-6 py-3">ФИО</th>
                <th class="px-6 py-3">Телефон</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3 text-right">Действие</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $user->last_name }} {{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.specialists.build', $user->id) }}"
                           class="inline-block px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-pink-100">
                            Сделать мастером →
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        Пользователи не найдены или все найденные уже являются мастерами.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
