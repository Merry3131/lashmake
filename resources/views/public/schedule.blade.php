@php
    \Carbon\Carbon::setLocale('ru');
@endphp
<x-app-layout title="Расписание мастера">
    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">

        {{-- Градиентное наложение для размытия нижнего края в белый цвет --}}
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest uppercase mb-4 font-serif">
                Расписание
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
        </div>
    </div>
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            {{-- Верхняя панель управления --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-6 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div>
                    <p class="text-sm text-gray-500 mt-1 font-light">
                        Период: <span class="font-medium text-gray-800">{{ $startOfWeek->format('d.m.Y') }}</span> — <span class="font-medium text-gray-800">{{ $endOfWeek->format('d.m.Y') }}</span>
                    </p>
                </div>


                <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl w-full sm:w-auto">
                    <a href="{{ route('schedule.index', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}"
                       class="flex-1 sm:flex-none px-4 py-2 text-xs font-medium text-gray-600 bg-white sm:bg-transparent rounded-lg hover:bg-white hover:text-gray-900 transition-all text-center">
                        ← Прошлая
                    </a>
                    <a href="{{ route('schedule.index', ['date' => \Carbon\Carbon::now()->format('Y-m-d')]) }}"
                       class="flex-1 sm:flex-none px-4 py-2 text-xs font-semibold text-pink-600 bg-white rounded-lg shadow-sm hover:text-pink-700 transition-all text-center">
                        Сегодня
                    </a>
                    <a href="{{ route('schedule.index', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}"
                       class="flex-1 sm:flex-none px-4 py-2 text-xs font-medium text-gray-600 bg-white sm:bg-transparent rounded-lg hover:bg-white hover:text-gray-900 transition-all text-center">
                        Следующая →
                    </a>
                </div>
            </div>

            {{-- Вертикальная лента дней недели --}}
            <div class="space-y-8">
                @foreach($weekDays as $dateStr => $dayData)
                    <div class="bg-white rounded-2xl border transition-all duration-300 {{ $dayData['is_today'] ? 'border-pink-300 ring-1 ring-pink-300 shadow-md shadow-pink-950/5' : 'border-gray-100 shadow-sm hover:shadow-md' }}">

                        {{-- Левая/Верхняя шапка дня --}}
                        <div class="px-6 py-4 border-b border-gray-50 bg-[#fdfcfb] rounded-t-2xl flex justify-between items-center">
                            <div class="flex items-baseline gap-3">
                                <h3 class="text-xl font-serif capitalize {{ $dayData['is_today'] ? 'text-pink-600 font-bold' : 'text-gray-900' }}">
                                    {{ $dayData['date']->isoFormat('dddd') }}
                                </h3>
                                <span class="text-sm text-gray-400 font-light">
                                    {{ $dayData['date']->format('d.m.Y') }}
                                </span>
                            </div>

                            {{-- Счетчик записей на день --}}
                            <div>
                                @if($dayData['appointments']->isEmpty())
                                    <span class="text-[11px] text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full font-light">Нет сеансов</span>
                                @else
                                    <span class="text-[11px] text-pink-600 bg-pink-50 border border-pink-100 px-2.5 py-1 rounded-full font-medium">
                                        Записей: {{ $dayData['appointments']->count() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Тело дня --}}
                        <div class="p-6">
                            @if($dayData['appointments']->isEmpty())
                                <div class="py-4 text-center">
                                    <p class="text-sm text-gray-400 font-light italic">На этот день записи в системе отсутствуют</p>
                                </div>
                            @else
                                <div class="divide-y divide-gray-100 space-y-4">
                                    @foreach($dayData['appointments'] as $index => $appointment)
                                        <div class="pt-4 first:pt-0 flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-8 transition-colors">

                                            {{-- Время сеанса --}}
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center text-sm font-bold text-white bg-pink-500 px-3 py-1.5 rounded-xl shadow-sm shadow-pink-500/10 min-w-[65px]">
                                                    {{ $appointment->appointment_at->format('H:i') }}
                                                </span>
                                            </div>

                                            {{-- Информация об услуге и клиенте --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                                    <h4 class="text-base font-medium text-gray-900 truncate">
                                                        {{ $appointment->service->name }}
                                                    </h4>
                                                    <span class="text-xs font-light text-gray-400">
                                                        Цена: <span class="text-gray-600 font-normal">{{ number_format($appointment->final_price, 0, '.', ' ') }} ₽</span>
                                                    </span>
                                                </div>

                                                <p class="text-xs text-gray-500 mt-1 font-light">
                                                    Клиент: <span class="font-medium text-gray-700">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</span>
                                                </p>

                                                {{-- Заметка/Комментарий клиента --}}
                                                @if($appointment->notes)
                                                    <div class="mt-2 text-xs bg-amber-50/60 border border-amber-100/70 p-2.5 rounded-xl text-amber-800 max-w-2xl font-light">
                                                        <span class="font-medium text-amber-900">Заметка:</span> "{{ $appointment->notes }}"
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Статус записи --}}
                                            <div class="flex-shrink-0 self-start md:self-center">
                                                @if($appointment->status === 'cancelled')
                                                    <span class="px-2.5 py-1 text-[11px] font-medium text-red-600 bg-red-50 rounded-lg border border-red-100">
                                                        Отменено
                                                    </span>
                                                @elseif($appointment->status === 'completed')
                                                    <span class="px-2.5 py-1 text-[11px] font-medium text-green-600 bg-green-50 rounded-lg border border-green-100">
                                                        Завершено
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 text-[11px] font-medium text-pink-600 bg-pink-50/50 rounded-lg border border-pink-100">
                                                        Подтверждено
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
