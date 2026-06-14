<x-app-layout title="Центр Ресниц | Отзывы">

    {{-- Применяем шрифты Playfair Display и Manrope --}}
    <style>
        /* Все заголовки и элементы с классом font-serif получают Playfair Display */
        h1, h2, h3, h4, h5, h6,
        .font-serif,
        [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }

        /* Основной текст — Manrope */
        body, p, span, button, a, li, div,
        .text-gray-600, .text-gray-500, .text-gray-400,
        .tracking-widest, .uppercase,
        input, textarea, select {
            font-family: 'Manrope', sans-serif;
        }

        /* Для кнопок и ссылок сохраняем Manrope */
        button, a, .btn {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    {{-- 1. ШАПКА СТРАНИЦЫ (ПОЛНОШИРИННАЯ С ФОНОМ И РАЗМЫТИЕМ) --}}
    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        {{-- Градиентное наложение для размытия нижнего края в цвет фона --}}
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest uppercase mb-4 font-serif">
                Отзывы наших клиентов
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                Ваши искренние эмоции — главная мотивация нашей команды
            </p>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- ОСНОВНОЙ КОНТЕЙНЕР С ФИЛЬТРАМИ И КАРТОЧКАМИ                        --}}
    {{-- ================================================================== --}}
    {{-- Инициализируем Alpine.js: activeCategory по умолчанию 'all' --}}
    <section x-data="{ activeCategory: 'all' }" class="max-w-7xl mx-auto pt-6 pb-24 px-6">

        {{-- КНОПКИ ФИЛЬТРАЦИИ ИЗ МАКЕТА --}}
        <div class="flex flex-wrap justify-center items-center gap-3 mb-12">
            {{-- Кнопка Все --}}
            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-[#ff5c8a] text-white border-[#ff5c8a]' : 'bg-white text-[#7c7e8c] border-[#f1f1f5] hover:border-[#ff5c8a]/40'"
                    class="px-5 py-2.5 rounded-full border text-xs tracking-wider uppercase font-normal transition-all duration-300 hover:cursor-pointer shadow-sm">
                все отзывы
            </button>

            {{-- Собираем уникальные категории услуг из отзывов --}}
            @php
                $uniqueCategories = $reviews->map(function($review) {
                    return $review->appointment->service->category;
                })->unique('id');
            @endphp

            {{-- Динамические кнопки категорий --}}
            @foreach($uniqueCategories as $category)
                <button @click="activeCategory = '{{ $category->id }}'"
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-[#ff5c8a] text-white border-[#ff5c8a]' : 'bg-white text-[#7c7e8c] border-[#f1f1f5] hover:border-[#ff5c8a]/40'"
                        class="px-5 py-2.5 rounded-full border text-xs tracking-wider uppercase font-normal transition-all duration-300 hover:cursor-pointer shadow-sm">
                    {{ mb_strtolower($category->display_name) }}
                </button>
            @endforeach
        </div>

        {{-- СЕТКА ОТЗЫВОВ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
                @php
                    $categoryId = $review->appointment->service->category_id;
                @endphp

                {{-- Карточка отзыва управляется Alpine.js через директиву x-show --}}
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $categoryId }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="bg-white rounded-3xl border border-[#f1f1f5] flex flex-col p-6 hover:shadow-sm transition-all duration-300">

                    {{-- Шапка карточки: Имя автора, дата и звезды --}}
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <h5 class="text-base font-normal text-[#1e1f22] leading-tight mb-1 font-serif">
                                {{ $review->user->first_name }} {{ $review->user->last_name }}
                            </h5>
                            <span class="text-[10px] text-[#9ca0b0] font-light uppercase tracking-wider">
                                {{ $review->created_at->format('d.m.Y') }}
                            </span>
                        </div>

                        {{-- Звезды рейтинга --}}
                        <div class="flex text-[#ff5c8a] text-xs gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>

                    {{-- Блок метаданных: Услуга и Мастер --}}
                    {{-- Блок метаданных: Категория, Услуга и Мастер --}}
                    <div class="flex flex-wrap gap-2 mb-5">
                        {{-- Категория услуги --}}
                        <div class="flex items-center gap-2  text-[#7c7e8c] px-3 py-1 rounded-sm text-[10px] font-normal tracking-wide border border-[#f1f1f5]">
                            <span class="text-sm">Категория:</span>
                            <span class="lowercase">{{ mb_strtolower($review->appointment->service->category->display_name) }}</span>
                        </div>

                        {{-- Услуга --}}
                        <div class="flex items-center gap-2 bg-[#fff0f3] text-[#ff5c8a] px-3 py-1 rounded-xl text-[10px] font-normal tracking-wide">
                            <span class="opacity-60 uppercase font-light">услуга:</span>
                            <span class="lowercase">{{ mb_strtolower($review->appointment->service->name) }}</span>
                        </div>

                        {{-- Мастер --}}
                        <div class="flex items-center gap-2 bg-[#f8f8fa] text-[#7c7e8c] px-3 py-1 rounded-xl text-[10px] font-normal tracking-wide border border-[#f1f1f5]">
                            <span class="opacity-60 uppercase font-light">мастер:</span>
                            <span class="lowercase">{{ mb_strtolower($review->specialist->user->first_name) }}</span>
                        </div>
                    </div>

                    {{-- Текст отзыва --}}
                    <div class="flex-grow">
                        <p class="text-gray-600 text-xs font-light leading-relaxed pl-4 border-l border-gray-200">
                            «{{ mb_strtolower($review->comment) }}»
                        </p>
                    </div>

                </div>
            @endforeach
        </div>


    </section>

</x-app-layout>
