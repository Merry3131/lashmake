@extends('admin.layouts.admin_menu')

@section('title', 'График работы')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-serif text-gray-950">Управление графиком работы</h1>
                    <p class="text-xs text-gray-500 mt-1">Нажмите на ячейку дня для изменения расписания мастера</p>
                </div>

                {{-- Переключение недель --}}
                <div class="flex gap-1 bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
                    <a href="{{ route('admin.schedule.index', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50 rounded-lg">← Прошлая</a>
                    <a href="{{ route('admin.schedule.index', ['date' => now()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-xs font-semibold text-pink-600 bg-pink-50 rounded-lg">Сегодня</a>
                    <a href="{{ route('admin.schedule.index', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50 rounded-lg">Следующая →</a>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100">
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[180px]">Мастер</th>
                            @foreach($weekDays as $day)
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                    <span class="block text-gray-400 font-normal text-[10px]">{{ $day->isoFormat('dd') }}</span>
                                    <span class="text-sm font-serif text-gray-900">{{ $day->format('d.m') }}</span>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($specialists as $specialist)
                            <tr>
                                <td class="p-4">
                                    <div class="font-medium text-gray-900">{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}</div>
                                    <div class="text-[11px] text-gray-400 font-light">{{ $specialist->level->name ?? 'Мастер' }}</div>
                                </td>

                                @foreach($weekDays as $day)
                                    @php
                                        $dateStr = $day->format('Y-m-d');
                                        $daySchedule = $schedules->get($specialist->id)?->get($dateStr)?->first();
                                    @endphp
                                    <td class="p-2 text-center">
                                        @if($daySchedule)
                                            <a href="{{ route('admin.schedule.edit', $daySchedule->id) }}"
                                               class="block p-3 rounded-xl transition-all border {{ $daySchedule->is_day_off ? 'bg-gray-50 border-gray-200/60 text-gray-400' : 'bg-pink-50/40 border-pink-100 text-pink-700 hover:scale-[1.02] hover:shadow-sm' }}">
                                                @if($daySchedule->is_day_off)
                                                    <span class="text-xs font-light italic">Выходной</span>
                                                @else
                                                    <span class="block text-xs font-bold">{{ Carbon\Carbon::parse($daySchedule->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($daySchedule->end_time)->format('H:i') }}</span>
                                                    @if($daySchedule->break_start)
                                                        <span class="block text-[10px] text-gray-400 font-light mt-0.5">Перерыв: {{ Carbon\Carbon::parse($daySchedule->break_start)->format('H:i') }}</span>
                                                    @endif
                                                @endif
                                            </a>
                                        @else
                                            <a href="{{ route('admin.schedule.create', ['specialist_id' => $specialist->id, 'date' => $day->format('Y-m-d')]) }}"
                                               class="block p-3 text-xs text-gray-400 italic font-light bg-gray-50/50 hover:bg-pink-50 hover:text-pink-600 rounded-xl border border-dashed border-gray-200 hover:border-pink-200 transition-all duration-200">
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
    </div>
@endsection
