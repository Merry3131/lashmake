@extends('admin.layouts.admin_menu')

@section('title', 'График работы')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider text-[#1e1f22]">График работы</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Нажмите на ячейку дня для изменения расписания мастера</p>
            </div>

            <div class="flex gap-1 bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
                <a href="{{ route('admin.schedule.index', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">← Прошлая</a>
                <a href="{{ route('admin.schedule.index', ['date' => now()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-sm font-semibold text-pink-600 bg-pink-50 rounded-lg transition-colors">Сегодня</a>
                <a href="{{ route('admin.schedule.index', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Следующая →</a>
            </div>
        </div>

        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-normal tracking-wide shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-sm">✓</span>
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

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] bg-[#f8f8fa]">
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium min-w-[180px]">Мастер</th>
                        @foreach($weekDays as $day)
                            <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium text-center">
                                <span class="block text-sm text-[#9ca0b0] font-normal">{{ $day->isoFormat('dd') }}</span>
                                <span class="text-sm font-normal text-[#1e1f22]">{{ $day->format('d.m') }}</span>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @foreach($specialists as $specialist)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-normal text-[#1e1f22]">{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}</div>
                                <div class="text-sm text-[#9ca0b0] font-light">{{ $specialist->level->display_name ?? 'Мастер' }}</div>
                            </td>

                            @foreach($weekDays as $day)
                                @php
                                    $dateStr = $day->format('Y-m-d');
                                    $daySchedule = $schedules->get($specialist->id)?->get($dateStr)?->first();
                                @endphp
                                <td class="px-2 py-2 text-center">
                                    @if($daySchedule)
                                        <a href="{{ route('admin.schedule.edit', $daySchedule->id) }}"
                                           class="block p-3 rounded-xl transition-all border {{ $daySchedule->is_day_off ? 'bg-slate-50 border-slate-200 text-slate-400' : 'bg-pink-50/40 border-pink-100 text-pink-700 hover:scale-[1.02] hover:shadow-sm' }}">
                                            @if($daySchedule->is_day_off)
                                                <span class="text-sm font-light italic">Выходной</span>
                                            @else
                                                <span class="block text-sm font-normal">{{ Carbon\Carbon::parse($daySchedule->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($daySchedule->end_time)->format('H:i') }}</span>
                                                @if($daySchedule->break_start)
                                                    <span class="block text-sm text-[#9ca0b0] font-light mt-0.5">Перерыв: {{ Carbon\Carbon::parse($daySchedule->break_start)->format('H:i') }}</span>
                                                @endif
                                            @endif
                                        </a>
                                    @else
                                        <a href="{{ route('admin.schedule.create', ['specialist_id' => $specialist->id, 'date' => $day->format('Y-m-d')]) }}"
                                           class="block p-3 text-sm text-slate-400 italic font-light bg-slate-50/50 hover:bg-pink-50 hover:text-pink-600 rounded-xl border border-dashed border-slate-200 hover:border-pink-200 transition-all duration-200">
                                            + Не задан
                                        </a>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
