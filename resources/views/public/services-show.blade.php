<x-app-layout title="Центр Ресниц | {{ $service->name }}">

    {{-- Применяем фирменные шрифты Playfair Display и Manrope --}}
    <style>
        h1, h2, h3, h4, h5, h6,
        .font-serif,
        [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }

        body, p, span, button, a, li, div,
        .text-gray-600, .text-gray-500, .text-gray-400,
        .tracking-widest, .uppercase,
        input, textarea, select {
            font-family: 'Manrope', sans-serif;
        }

        button, a, .btn {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    {{-- Шапка страницы с хлебными крошками --}}
    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            {{-- Хлебные крошки для удобной навигации --}}
            <div class="flex items-center justify-center gap-2 text-xs text-[#7c7e8c] uppercase tracking-wider mb-4">
                <a href="{{ route('services.index') }}" class="hover:text-[#ff5c8a] transition-colors">Услуги</a>
                <span>•</span>
                <span class="text-[#1e1f22] font-medium">{{ mb_strtolower($service->category->display_name ?? 'Категория') }}</span>
            </div>

            <h1 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest uppercase mb-4 font-serif">
                {{ mb_strtolower($service->name) }}
            </h1>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
        </div>
    </div>

    {{-- Основной контент --}}
    <section class="max-w-7xl mx-auto pb-24 px-6 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            {{-- ЛЕВАЯ КОЛОНКА: Изображение категории или услуги --}}
            <div class="lg:col-span-5">
                <div class="bg-white p-4 rounded-3xl border border-[#f1f1f5] shadow-sm">
                    <div class="aspect-[4/3] bg-[#f8f8fa] relative overflow-hidden rounded-2xl">
                        @if($service->category && $service->category->hasMedia('categories'))
                            <img src="{{ $service->category->getFirstMediaUrl('categories') }}"
                                 alt="{{ $service->name }}"
                                 class="w-full h-full object-cover opacity-90">
                        @else
                            {{-- Заглушка, если фото нет --}}
                            <div class="w-full h-full flex items-center justify-center bg-pink-50 text-[#ff5c8a]">
                                <svg class="w-16 h-16 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ПРАВАЯ КОЛОНКА: Детали, описание и карточка записи --}}
            <div class="lg:col-span-7 space-y-8">

                {{-- Карточка с описанием и характеристиками --}}
                <div class="bg-white p-8 lg:p-10 rounded-3xl border border-[#f1f1f5] space-y-6">
                    <div>
                        <h3 class="text-xl text-[#1e1f22] tracking-wide font-normal uppercase font-serif mb-3">
                            описание услуги
                        </h3>
                        <p class="text-sm text-[#7c7e8c] font-light leading-relaxed">
                            {{ $service->description ? mb_strtolower($service->description) : 'описание для данной услуги временно отсутствует. вы можете уточнить детали у нашего администратора при записи.' }}
                        </p>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    {{-- Характеристики: Время и Стоимость --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-2">
                        {{-- Блок времени --}}
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#fff0f3] text-[#ff5c8a] rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#9ca0b0] tracking-wide uppercase">длительность</p>
                                @if($service->levels->isNotEmpty())
                                    @php
                                        $minDuration = $service->levels->min('pivot.duration');
                                    @endphp
                                    <p class="text-sm font-medium text-gray-800">
                                        от {{ str_replace('.', ',', $minDuration / 60) }} {{ ($minDuration / 60) <= 1.5 ? 'часа' : 'часов' }}
                                    </p>
                                @else
                                    <p class="text-sm font-medium text-gray-800">уточняйте</p>
                                @endif
                            </div>
                        </div>

                        {{-- Блок цены --}}
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-pink-100 text-[#ff5c8a] rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#9ca0b0] tracking-wide uppercase">стоимость</p>
                                <p class="text-lg font-semibold text-[#1e1f22]">
                                    {{ number_format($service->price, 0, '.', ' ') }} ₽
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    {{-- Кнопка быстрой записи --}}
                    <div class="pt-2">
                        <button class="w-full bg-[#ff5c8a] text-white py-4 rounded-full text-xs tracking-widest font-normal uppercase hover:bg-[#e04b75] transition-colors duration-300 shadow-md shadow-pink-200 hover:cursor-pointer">
                            записаться на эту услугу
                        </button>
                    </div>
                </div>

                {{-- БЛОК: Мастера, выполняющие услугу --}}
                {{-- Раскомментируй и настрой связь, если у тебя в модели Service настроена связь со специалистами (например, $service->specialists) --}}
                <div class="space-y-4">
                    <h3 class="text-lg text-[#1e1f22] tracking-widest font-normal uppercase font-serif pl-2">
                        наши мастера
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($service->specialists ?? [] as $specialist)
                            <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-[#f1f1f5]">
                                <div class="w-12 h-12 rounded-full bg-gray-100 overflow-hidden flex-shrink-0">
                                    <img src="{{ $specialist->user->avatar_url ?? asset('img/default-avatar.png') }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800">
                                        {{ mb_strtolower($specialist->user->first_name) }}
                                    </h4>
                                    <p class="text-xs text-[#9ca0b0]">
                                        {{ mb_strtolower($specialist->category_label ?? 'мастер') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 pl-2">услугу оказывают все топ-мастера салона.</p>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
    </section>

</x-app-layout>
