@php
    \Carbon\Carbon::setLocale('ru');
@endphp
<x-app-layout>
    {{-- 1. ШАПКА СТРАНИЦЫ (ПОЛНОШИРИННАЯ С ФОНОМ И РАЗМЫТИЕМ) --}}
    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest uppercase mb-4 font-[Playfair_Display]">
                Личный кабинет
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide font-[Manrope]">
                Управление вашими записями и личными данными
            </p>
        </div>
    </div>
    <div class="fixed top-6 right-6 z-50 space-y-3 max-w-sm w-full font-sans" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">

        {{-- Ошибка (Вы уже оставляли отзыв) --}}
        @if(session('error'))
            <div class="bg-[#fff5f5] border border-[#ffe3e3] p-4 rounded-2xl shadow-lg flex items-center gap-3 text-xs text-[#1e1f22]">
                <div class="w-7 h-7 bg-[#ffe3e3] text-[#e53e3e] rounded-xl flex items-center justify-center flex-shrink-0 font-bold">!</div>
                <p class="flex-grow font-light">{{ mb_strtolower(session('error')) }}</p>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">×</button>
            </div>
        @endif

        {{-- Успех (Отзыв сохранен) --}}
        @if(session('success'))
            <div class="bg-[#fff0f3] border border-pink-100 p-4 rounded-2xl shadow-lg flex items-center gap-3 text-xs text-[#1e1f22]">
                <div class="w-7 h-7 bg-pink-100 text-[#ff5c8a] rounded-xl flex items-center justify-center flex-shrink-0 font-bold">✓</div>
                <p class="flex-grow font-light">{{ mb_strtolower(session('success')) }}</p>
                <button @click="show = false" class="text-gray-400 hover:text-pink-500">×</button>
            </div>
        @endif

    </div>
    {{-- ================================================================== --}}
    {{-- ОСНОВНОЙ КОНТЕЙНЕР (ДВУХКОЛОНОЧНЫЙ МАКЕТ ПО МАКЕТУ)                 --}}
    {{-- ================================================================== --}}
    <div class="max-w-7xl mx-auto pt-6 pb-24 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- ЛЕВАЯ КОЛОНКА: ЛИЧНЫЕ ДАННЫЕ (1/3 ширины) --}}
            <div class="bg-white rounded-3xl border border-[#f1f1f5] p-6 hover:shadow-sm transition-all duration-300">
                <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 mb-6">
                    <div class="w-20 h-20 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-[#ff5c8a] mb-3 shadow-sm">
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-normal text-[#1e1f22] font-[Playfair_Display]">
                        Личные данные
                    </h3>
                </div>

                <div class="space-y-4 text-gray-700">
                    <div>
                        <span class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1 font-[Manrope]">Имя</span>
                        <p class="text-sm text-[#1e1f22] font-light bg-[#f8f8fa] border border-[#f1f1f5] rounded-xl px-4 py-2 font-[Manrope]">
                            {{ Auth::user()->first_name }}
                        </p>
                    </div>

                    <div>
                        <span class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1 font-[Manrope]">Фамилия</span>
                        <p class="text-sm text-[#1e1f22] font-light bg-[#f8f8fa] border border-[#f1f1f5] rounded-xl px-4 py-2 font-[Manrope]">
                            {{ Auth::user()->last_name }}
                        </p>
                    </div>

                    <div>
                        <span class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1 font-[Manrope]">Телефон</span>
                        <p class="text-sm text-[#1e1f22] font-light bg-[#f8f8fa] border border-[#f1f1f5] rounded-xl px-4 py-2.5 font-[Manrope]">
                            {{ Auth::user()->phone }}
                        </p>
                    </div>

                    <div>
                        <span class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1 font-[Manrope]">Электронная почта</span>
                        <p class="text-sm text-[#1e1f22] font-light bg-[#f8f8fa] border border-[#f1f1f5] rounded-xl px-4 py-2.5 break-all font-[Manrope]">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <div class="pt-6 flex">
                    <a href="{{ route('profile.edit') }}" class="w-full border border-[#ff5c8a] text-[#ff5c8a] rounded-xl py-3 text-xs tracking-wider uppercase font-normal hover:bg-[#ff5c8a] hover:text-white text-center transition-all duration-300 shadow-sm font-[Manrope]">
                        Редактировать
                    </a>
                </div>
            </div>

            {{-- ПРАВАЯ КОЛОНКА: ИСТОРИЯ ЗАПИСЕЙ (2/3 ширины) --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-normal text-[#1e1f22] uppercase tracking-wider font-[Playfair_Display]">
                        История записей
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($appointments as $appointment)
                        {{-- Стилизованная карточка визита под макет --}}
                        <div class="group bg-white rounded-3xl border border-[#f1f1f5] p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:shadow-sm transition-all duration-300">

                            {{-- Блок даты и времени --}}
                            <div class="flex items-center gap-4 min-w-[140px]">
                                <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-2xl flex flex-col items-center justify-center text-center p-1 flex-shrink-0 group-hover:border-[#ff5c8a]/30 transition-colors">
                                    <span class="text-xs text-gray-400 font-light leading-none uppercase mb-0.5 font-[Manrope]">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_at)->isoFormat('dd') }}
                                    </span>
                                    <span class="text-base font-normal text-[#1e1f22] leading-none font-[Manrope]">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_at)->format('d') }}
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-normal text-[#1e1f22] font-[Manrope]">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_at)->isoFormat('MMMM YYYY') }}
                                    </span>
                                    <span class="text-xs text-[#7c7e8c] font-light flex items-center gap-1 font-[Manrope]">
                                        <svg class="w-3.5 h-3.5 text-[#ff5c8a]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_at)->format('H:i') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Информационный блок услуги и мастера --}}
                            <div class="flex-grow space-y-1 sm:pl-4 sm:border-l border-gray-100">
                                <h4 class="text-base font-normal text-[#1e1f22] group-hover:text-[#ff5c8a] transition-colors duration-300 leading-tight font-[Playfair_Display]">
                                    {{ $appointment->service->name }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-[#7c7e8c] font-light">
                                    <span class="flex items-center gap-1 font-[Manrope]">
                                        <span class="w-1 h-1 bg-[#ff5c8a] rounded-full"></span>
                                        Мастер: <strong class="font-normal text-[#1e1f22]">{{ $appointment->specialist->user->first_name }} {{ $appointment->specialist->user->last_name }}</strong>
                                    </span>
                                </div>
                            </div>

                            {{-- Стоимость и пастельный статус записи --}}
                            <div class="flex sm:flex-col items-end justify-between sm:justify-center gap-2 pt-3 sm:pt-0 border-t sm:border-t-0 border-dashed border-gray-100 min-w-[120px]">
                                <span class="text-lg font-normal text-gray-900 whitespace-nowrap font-[Manrope]">
                                    {{ number_format($appointment->final_price, 0, '.', ' ') }} ₽
                                </span>

                                {{-- Условия вывода статусов из твоего оригинала, упакованные в новые бейджи --}}
                                @if($appointment->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-normal uppercase tracking-wider rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 font-[Manrope]">
                                        Визит прошел
                                    </span>
                                @elseif($appointment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-normal uppercase tracking-wider rounded-xl bg-amber-50 text-amber-600 border border-amber-100 font-[Manrope]">
                                        Ожидается
                                    </span>
                                @elseif($appointment->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-normal uppercase tracking-wider rounded-xl bg-rose-50 text-rose-600 border border-rose-100 font-[Manrope]">
                                        Отменена
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('reviews.create', ['appointment' => $appointment->id]) }}"
                               class="inline-block border border-[#ff5c8a] text-[#ff5c8a] hover:bg-[#ff5c8a] hover:text-white rounded-xl py-2 px-4 text-xs tracking-wide font-normal transition-colors duration-300">
                                оставить отзыв
                            </a>
                        </div>
                    @empty
                        {{-- Заглушка из твоего оригинала --}}
                        <div class="text-center py-16 bg-white rounded-3xl border border-[#f1f1f5] p-8">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <p class="text-[#7c7e8c] font-light text-sm font-[Manrope]">
                                У вас пока нет активных записей.
                            </p>
                        </div>
                    @endforelse
                </div>
                {{--УВЕДОМЛНЕИЯ--}}
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm max-w-4xl mx-auto mt-8 font-sans">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium uppercase tracking-wider text-gray-900 font-[Playfair_Display]">
                            Ваши уведомления
                        </h3>

                        @if(auth()->user()->notifications->isNotEmpty())
                            <form action="{{ route('notifications.clear') }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить все уведомления?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] text-gray-400 hover:text-red-500 font-light uppercase tracking-wider border-b border-transparent hover:border-red-500 transition-all duration-200 cursor-pointer">
                                    очистить всё
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @forelse(\Illuminate\Support\Facades\Auth::user()->notifications as $notification)
                            <div class="p-4 rounded-2xl border transition-all {{ $notification->read_at ? 'bg-gray-50/50 border-gray-100 opacity-60' : 'bg-pink-50/30 border-pink-100' }}">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        {{-- Маркер непрочитанного уведомления --}}
                                        @if(!$notification->read_at)
                                            <span class="w-1.5 h-1.5 bg-[#ff5c8a] rounded-full flex-shrink-0"></span>
                                        @endif
                                        <span class="text-[10px] uppercase tracking-wider font-medium {{ $notification->data['type'] === 'confirmed' ? 'text-green-600' : 'text-[#ff5c8a]' }}">
                                            {{ $notification->data['title'] }}
                                        </span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-light">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                </div>

                                <p class="text-xs text-gray-700 font-light mt-1.5 leading-relaxed">
                                    {{ $notification->data['message'] }}
                                </p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic font-light text-center py-4">У вас пока нет уведомлений.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-app-layout>
