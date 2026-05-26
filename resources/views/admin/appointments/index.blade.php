@extends('admin.layouts.admin_menu')

@section('title', 'Список записей салона')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-slate-800">Записи салона</h1>
        <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl text-sm transition-all shadow-sm">
            + Новая запись
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </label>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase bg-slate-50/50">
                <th class="px-6 py-3">Дата и Время</th>
                <th class="px-6 py-3">Клиент</th>
                <th class="px-6 py-3">Мастер (Специалист)</th>
                <th class="px-6 py-3">Услуга</th>
                <th class="px-6 py-3">Статус</th>
                <th class="px-6 py-3 text-right">Действие</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($appointments as $appointment)
                <tr class="hover:bg-slate-50/50 transition-colors">

                    <td class="px-6 py-4 font-bold text-slate-900">
                        {{ \Carbon\Carbon::parse($appointment->appointment_at)->format('d.m.Y H:i') }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800">
                            {{ $appointment->user->last_name ?? '' }} {{ $appointment->user->first_name ?? 'Неизвестный' }}
                        </div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            {{ $appointment->user->phone ?? 'Телефон не указан' }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-slate-700 font-medium">
                        @if($appointment->specialist && $appointment->specialist->user)
                            {{ $appointment->specialist->user->last_name }} {{ $appointment->specialist->user->first_name }}
                        @else
                            <span class="text-slate-400">Не назначен</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate">
                        {{ $appointment->service->name ?? 'Услуга удалена' }}
                    </td>

                    <td class="px-6 py-4">
                        @switch($appointment->status)
                            @case('confirmed')
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">
                                    Подтверждена
                                </span>
                                @break
                            @case('pending')
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-semibold">
                                    Ожидает
                                </span>
                                @break
                            @case('cancelled')
                                <span class="px-2 py-1 bg-rose-50 text-rose-700 rounded-lg text-xs font-semibold">
                                    Отменена
                                </span>
                                @break
                            @case('completed')
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">
                                    Выполнена
                                </span>
                                @break
                            @default
                                <span class="px-2 py-1 bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold">
                                    {{ $appointment->status }}
                                </span>
                        @endswitch
                    </td>

                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Изменить
                        </a>

                        @if($appointment->status !== 'cancelled')
                            <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите отменить эту запись?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-lg transition-colors">
                                    Отменить
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        Записей на процедуры пока нет.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
