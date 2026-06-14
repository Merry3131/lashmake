@extends('admin.layouts.admin_menu')

@section('title', 'Список записей салона')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ВЕРХНЯЯ ЧАСТЬ: ЗАГОЛОВОК И КНОПКА ДОБАВЛЕНИЯ (РАСТЯНУТО) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Записи салона</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Просмотр, редактирование и отмена записей клиентов студии</p>
            </div>
            <a href="{{ route('admin.appointments.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                + Новая запись
            </a>
        </div>

        {{-- УВЕДОМЛЕНИЕ ОБ УСПЕШНОМ ДЕЙСТВИИ --}}
        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-normal tracking-wide shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-[10px] font-bold">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <label for="hide-alert-checkbox" class="ms-4 p-1.5 inline-flex items-center justify-center rounded-xl text-emerald-500 hover:bg-emerald-100 hover:text-emerald-900 transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </label>
                </div>
            </div>
        @endif

        {{-- ПОЛНОШИРИННАЯ ТАБЛИЦА С ЗАПИСЯМИ --}}
        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] bg-[#f8f8fa]">
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium w-16 text-center">ID</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Клиент</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Услуга</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Мастер</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Дата и время</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Статус</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium text-right pr-8">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200">
                            {{-- ID --}}
                            <td class="px-6 py-4.5 text-xs text-[#7c7e8c] font-light text-center bg-[#f8f8fa]/30">
                                {{ $appointment->id }}
                            </td>

                            {{-- Клиент --}}
                            <td class="px-6 py-4.5 text-sm font-normal text-[#1e1f22] whitespace-nowrap">
                                <div class="font-medium">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</div>
                                <div class="text-xs text-[#7c7e8c] font-light mt-0.5">{{ $appointment->user->phone }}</div>
                            </td>

                            {{-- Услуга --}}
                            <td class="px-6 py-4.5 text-sm font-normal text-[#1e1f22]">
                                {{ $appointment->service->name }}
                            </td>

                            {{-- Мастер --}}
                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light whitespace-nowrap">
                                {{ $appointment->specialist->user->first_name }} {{ $appointment->specialist->user->last_name }}
                            </td>

                            {{-- Дата и время --}}
                            <td class="px-6 py-4.5 text-sm font-normal text-[#1e1f22] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($appointment->appointment_at)->format('d.m.Y H:i') }}
                            </td>

                            {{-- Статус --}}
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @switch($appointment->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-normal tracking-wide uppercase rounded-lg bg-amber-50 text-amber-700 border border-amber-100">
                                            Ожидает
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-normal tracking-wide uppercase rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Подтверждена
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-normal tracking-wide uppercase rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                                            Отменена
                                        </span>
                                        @break
                                @endswitch
                            </td>

                            {{-- Действия --}}
                            <td class="px-6 py-4.5 text-right space-x-1.5 pr-8 whitespace-nowrap">
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="inline-flex items-center justify-center px-3 py-2 border border-[#f1f1f5] bg-white hover:bg-[#f8f8fa] text-[#1e1f22] text-xs font-light rounded-xl transition-all duration-200 shadow-sm">
                                    Изменить
                                </a>

                                @if($appointment->status !== 'cancelled')
                                    <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите отменить эту запись?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-2 border border-rose-100 bg-rose-50/50 hover:bg-rose-50 text-rose-600 text-xs font-light rounded-xl transition-all duration-200 cursor-pointer shadow-sm">
                                            Отменить
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-[#7c7e8c]">
                                <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">Записей на процедуры пока нет.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
