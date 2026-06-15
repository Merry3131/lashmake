@extends('admin.layouts.admin_menu')

@section('title', 'Редактировать расписание')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider text-[#1e1f22]">Редактировать расписание</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Настройка рабочего дня мастера</p>
            </div>
            <a href="{{ route('admin.schedule.index', ['date' => $schedule->work_date->format('Y-m-d')]) }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm tracking-wider font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                ← Назад к графику
            </a>
        </div>

        @if ($errors->any())
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-red-50 border border-red-100 text-red-800 rounded-2xl text-sm font-normal tracking-wide shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-500 text-white text-sm">!</span>
                        <div class="space-y-1">
                            <p class="font-medium">Ошибка сохранения:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <label for="hide-alert-checkbox" class="ms-4 p-1.5 inline-flex items-center justify-center rounded-xl text-red-500 hover:bg-red-100 hover:text-red-900 transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </label>
                </div>
            </div>
        @endif

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm p-8"
             x-data="{ isDayOff: {{ old('is_day_off', $schedule->is_day_off) ? 'true' : 'false' }} }">

            <div class="mb-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-normal text-[#1e1f22]">{{ $schedule->specialist->user->first_name }} {{ $schedule->specialist->user->last_name }}</h2>
                        <p class="text-sm text-[#7c7e8c] font-light">
                            {{ $schedule->work_date->translatedFormat('d F Y') }} ({{ $schedule->work_date->translatedFormat('l') }})
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ $schedule->id ? route('admin.schedule.update', $schedule->id) : route('admin.schedule.store') }}"
                  method="POST"
                  class="space-y-6">
                @csrf

                @if($schedule->id)
                    @method('PUT')
                @endif

                @if(!$schedule->id)
                    <input type="hidden" name="specialist_id" value="{{ $schedule->specialist_id }}">
                    <input type="hidden" name="work_date" value="{{ $schedule->work_date->format('Y-m-d') }}">
                @endif

                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 space-y-4">
                    <p class="text-sm tracking-wider text-slate-600">Статус дня:</p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl border border-slate-200 cursor-pointer flex-1 hover:border-pink-300 transition-colors">
                            <input type="radio" name="is_day_off" value="0"
                                   {{ old('is_day_off', $schedule->is_day_off) == 0 ? 'checked' : '' }}
                                   class="w-4 h-4 text-pink-500 border-slate-300 focus:ring-pink-500">
                            <div>
                                <span class="text-sm font-medium text-slate-900 block">Рабочий день</span>
                                <span class="text-sm text-slate-400 font-light">Мастер принимает записи</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl border border-slate-200 cursor-pointer flex-1 hover:border-pink-300 transition-colors">
                            <input type="radio" name="is_day_off" value="1"
                                   {{ old('is_day_off', $schedule->is_day_off) == 1 ? 'checked' : '' }}
                                   class="w-4 h-4 text-pink-500 border-slate-300 focus:ring-pink-500">
                            <div>
                                <span class="text-sm font-medium text-slate-900 block">Выходной</span>
                                <span class="text-sm text-slate-400 font-light">Запись полностью закрыта</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div x-show="!isDayOff" x-collapse class="space-y-6">
                    <div>
                        <h4 class="text-sm tracking-wider text-slate-500 mb-3">Рабочие часы</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-slate-500 block mb-1">Начало смены</label>
                                <input type="time" name="start_time"
                                       value="{{ old('start_time', $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '09:00') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            </div>
                            <div>
                                <label class="text-sm text-slate-500 block mb-1">Конец смены</label>
                                <input type="time" name="end_time"
                                       value="{{ old('end_time', $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '21:00') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div>
                        <h4 class="text-sm tracking-wider text-slate-500 mb-3">Время перерыва</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-slate-500 block mb-1">Начало перерыва</label>
                                <input type="time" name="break_start"
                                       value="{{ old('break_start', $schedule->break_start ? \Carbon\Carbon::parse($schedule->break_start)->format('H:i') : '') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            </div>
                            <div>
                                <label class="text-sm text-slate-500 block mb-1">Конец перерыва</label>
                                <input type="time" name="break_end"
                                       value="{{ old('break_end', $schedule->break_end ? \Carbon\Carbon::parse($schedule->break_end)->format('H:i') : '') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="isDayOff" x-cloak class="p-5 bg-pink-50/50 rounded-2xl border border-dashed border-pink-100 text-center">
                    <p class="text-sm text-pink-600 font-light">
                        День отмечен как выходной. При сохранении часы работы и перерывы автоматически очистятся.
                    </p>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.schedule.index', ['date' => $schedule->work_date->format('Y-m-d')]) }}"
                       class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs  font-normal rounded-xl transition-all duration-200 text-center">
                        Отмена
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs  font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                        Сохранить изменения
                    </button>
                </div>

            </form>
        </div>

    </div>
@endsection
