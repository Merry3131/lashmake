@extends('admin.layouts.admin_menu')

@section('title', 'Редактировать расписание')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8 font-sans">
        <div class="max-w-xl mx-auto px-4 sm:px-6">

            {{-- Кнопка назад --}}
            <div class="mb-6">
                <a href="{{ route('admin.schedule.index', ['date' => $schedule->work_date->format('Y-m-d')]) }}"
                   class="inline-flex items-center gap-2 text-xs text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-wider">
                    ← Назад к графику
                </a>
            </div>

            {{-- Вывод ошибок валидации --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs space-y-1">
                    <p class="font-bold uppercase tracking-wider mb-1">Ошибка сохранения:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Главная карточка формы --}}
            {{-- Инициализируем Alpine с учетом старых данных из сессии old() или базы данных --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ isDayOff: {{ old('is_day_off', $schedule->is_day_off) ? 'true' : 'false' }} }">

                {{-- Шапка карточки --}}
                <div class="p-6 sm:p-8 bg-cover bg-center relative border-b border-gray-50" style="background-image: url('{{ asset('img/bg_main.png') }}');">
                <span class="text-[10px] text-pink-500 font-bold uppercase tracking-widest block mb-1">
                    настройка рабочего дня
                </span>
                    <h2 class="text-xl text-gray-950 font-normal font-serif">
                        {{ $schedule->specialist->user->first_name }} {{ $schedule->specialist->user->last_name }}
                    </h2>
                    <p class="text-xs text-gray-400 font-light mt-1">
                        {{ $schedule->work_date->translatedFormat('d F Y') }} ({{ $schedule->work_date->translatedFormat('l') }})
                    </p>
                </div>

                {{-- Форма --}}
                <form action="{{ $schedule->id ? route('admin.schedule.update', $schedule->id) : route('admin.schedule.store') }}"
                      method="POST"
                      class="p-6 sm:p-8 space-y-6">
                    @csrf

                    @if($schedule->id)
                        @method('PUT')
                    @endif

                    {{-- Если это создание нового дня, передаем скрытые поля, чтобы контроллер знал куда сохранить --}}
                    @if(!$schedule->id)
                        <input type="hidden" name="specialist_id" value="{{ $schedule->specialist_id }}">
                        <input type="hidden" name="work_date" value="{{ $schedule->work_date->format('Y-m-d') }}">
                    @endif

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-800">Статус дня:</p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            {{-- Вариант: Рабочий день --}}
                            <label class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl border border-gray-100 cursor-pointer flex-1 hover:border-pink-200 transition-colors">
                                <input type="radio" name="is_day_off" value="0"
                                       {{ old('is_day_off', $schedule->is_day_off) == 0 ? 'checked' : '' }}
                                       class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                                <div>
                                    <span class="text-xs font-medium text-gray-900 block">Рабочий день</span>
                                    <span class="text-[10px] text-gray-400 font-light">Мастер принимает записи</span>
                                </div>
                            </label>

                            {{-- Вариант: Выходной день --}}
                            <label class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl border border-gray-100 cursor-pointer flex-1 hover:border-pink-200 transition-colors">
                                <input type="radio" name="is_day_off" value="1"
                                       {{ old('is_day_off', $schedule->is_day_off) == 1 ? 'checked' : '' }}
                                       class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                                <div>
                                    <span class="text-xs font-medium text-gray-900 block">Выходной</span>
                                    <span class="text-[10px] text-gray-400 font-light">Запись полностью закрыта</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Блок рабочих часов (скрывается, если стоит Выходной) --}}
                    <div x-show="!isDayOff" x-collapse class="space-y-6">

                        {{-- Рабочее время --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Рабочие часы</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[11px] text-gray-500 uppercase block mb-1">Начало смены</label>
                                    <input type="time" name="start_time"
                                           value="{{ old('start_time', $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '09:00') }}"
                                           class="w-full text-xs p-3 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all">
                                </div>
                                <div>
                                    <label class="text-[11px] text-gray-500 uppercase block mb-1">Конец смены</label>
                                    <input type="time" name="end_time"
                                           value="{{ old('end_time', $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '21:00') }}"
                                           class="w-full text-xs p-3 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <hr class="border-dashed border-gray-100">

                        {{-- Время перерыва --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Время перерыва</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[11px] text-gray-500 uppercase block mb-1">Начало перерыва</label>
                                    <input type="time" name="break_start"
                                           value="{{ old('break_start', $schedule->break_start ? \Carbon\Carbon::parse($schedule->break_start)->format('H:i') : '') }}"
                                           class="w-full text-xs p-3 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all">
                                </div>
                                <div>
                                    <label class="text-[11px] text-gray-500 uppercase block mb-1">Конец перерыва</label>
                                    <input type="time" name="break_end"
                                           value="{{ old('break_end', $schedule->break_end ? \Carbon\Carbon::parse($schedule->break_end)->format('H:i') : '') }}"
                                           class="w-full text-xs p-3 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all">
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Заглушка для выходного дня --}}
                    <div x-show="isDayOff" x-cloak class="p-6 bg-pink-50/50 rounded-2xl border border-dashed border-pink-100 text-center">
                        <p class="text-xs text-pink-600 font-light">
                            День отмечен как выходной. При сохранении часы работы и перерывы автоматически очистятся.
                        </p>
                    </div>

                    {{-- Кнопки действий --}}
                    <div class="pt-4 flex items-center justify-between border-t border-gray-50 gap-4">
                        <a href="{{ route('admin.schedule.index', ['date' => $schedule->work_date->format('Y-m-d')]) }}"
                           class="text-xs text-gray-400 hover:text-gray-900 transition-colors pb-0.5 border-b border-transparent hover:border-gray-900">
                            Отмена
                        </a>
                        <button type="submit"
                                class="bg-pink-500 text-white px-6 py-3 rounded-full text-xs tracking-widest font-normal uppercase hover:bg-pink-600 transition-all duration-300 shadow-md shadow-pink-100 hover:cursor-pointer">
                            Сохранить изменения
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection
