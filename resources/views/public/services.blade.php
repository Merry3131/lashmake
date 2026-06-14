<x-app-layout title="Центр Ресниц | Услуги">

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

    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">

        {{-- Градиентное наложение для размытия нижнего края в белый цвет --}}
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest uppercase mb-4 font-serif">
                Наши услуги
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                Искусство преображения взгляда
            </p>
        </div>
    </div>

    <section>
        <section class="max-w-7xl mx-auto pb-12 px-6">

            @if($promotions->isNotEmpty())
                <div class="w-full mt-16 mb-16">
                    <div class="text-center mb-12">
                        <h3 class="text-2xl text-[#1e1f22] tracking-widest font-normal uppercase font-serif">
                            специальные предложения
                        </h3>
                        <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mt-4 rounded-full"></div>
                    </div>

                    <div class="p-8 rounded-3xl border border-[#f1f1f5] space-y-6 bg-pink-100">
                        @foreach($promotions as $promotion)
                            <div class="group flex justify-between items-center border-b border-dashed border-gray-500 pb-4 hover:border-[#ff5c8a] transition-colors duration-300">

                                <div class="max-w-[65%] text-left space-y-1">
                                    <div class="flex items-center gap-3 text-xs tracking-wide">
                                        <span class="text-[#ff5c8a]">
                                            {{ mb_strtolower($promotion->type_label) }}
                                        </span>
                                        <span class="text-[#9ca0b0]">
                                            до {{ \Carbon\Carbon::parse($promotion->end_date)->translatedFormat('d F') }}
                                        </span>
                                    </div>

                                    <h4 class="text-lg font-normal text-gray-800 group-hover:text-[#ff5c8a] transition-colors font-serif">
                                        {{ mb_strtolower($promotion->title) }}
                                    </h4>

                                    <p class="text-xs text-[#7c7e8c] tracking-wide">
                                        услуга: <span class="text-[#1e1f22]">{{ mb_strtolower($promotion->service->name) }}</span>
                                        @if($promotion->specialist)
                                            • мастер: <span class="text-[#ff5c8a]">{{ mb_strtolower($promotion->specialist->user->first_name) }}</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="flex items-center gap-4">
                                    @if($promotion->discount_percent > 0)
                                        <span class="text-xl font-normal text-gray-900 whitespace-nowrap group-hover:translate-x-[-10px] transition-transform duration-300">
                                            -{{ $promotion->discount_percent }}%
                                        </span>
                                    @endif
                                    <button class="opacity-0 group-hover:opacity-100 bg-[#ff5c8a] text-white px-4 py-2 rounded-full text-xs tracking-wider font-normal transition-all duration-300 transform translate-x-4 group-hover:translate-x-0 hover:cursor-pointer">
                                        записаться
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- СЕТКА КАРТОЧЕК С КАТЕГОРИЯМИ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12 mb-24">
                @foreach($categories as $category)
                    <div class="group p-5 bg-white rounded-3xl overflow-hidden border border-[#f1f1f5] flex flex-col h-full hover:bg-pink-200 hover:shadow-lg transition-all duration-500 hover:shadow-pink-300">

                        <div class="aspect-[4/3] bg-[#f8f8fa] relative overflow-hidden flex-shrink-0">
                            @if($category->hasMedia('categories'))
                                <img src="{{ $category->getFirstMediaUrl('categories') }}" alt="{{ $category->display_name }}"
                                     class="w-full h-full object-cover opacity-90 transition-opacity duration-500">
                            @endif
                        </div>

                        <div class="p-8 text-center flex flex-col flex-grow relative overflow-hidden h-[180px]">
                            <h3 class="text-xl text-[#1e1f22] font-normal mb-3 tracking-tight font-serif">
                                {{ mb_strtolower($category->display_name) }}
                            </h3>

                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 flex flex-col flex-grow">
                                <p class="text-xs text-[#7c7e8c] font-light leading-relaxed max-w-xs mx-auto">
                                    {{ mb_strtolower($category->description) }}
                                </p>

                                <div class="opacity-0 translate-y-8 group-hover:opacity-100 group-hover:translate-y-4 transition-all duration-500 mt-2">
                                    <a href="#category-{{ $category->id }}"
                                       class="inline-block border border-[#ff5c8a] bg-[#ff5c8a] text-white rounded-full py-2.5 px-8 text-xs tracking-wider font-normal hover:bg-[#e04b75] hover:border-[#e04b75] transition-colors duration-300">
                                        смотреть услуги
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- ПОДРОБНЫЙ ПРАЙС-ЛИСТ С УСЛУГАМИ ВНУТРИ КАТЕГОРИЙ --}}
            <div class="text-center mb-12">
                <h3 class="text-2xl text-[#1e1f22] tracking-widest font-normal uppercase font-serif">прайс-лист</h3>
                <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="space-y-12 max-w-7xl mx-auto">
                @foreach($categories as $index => $category)
                    <div id="category-{{ $category->id }}" class="bg-white p-8 rounded-3xl border border-[#f1f1f5] scroll-mt-32">

                        <h4 class="text-xl font-normal text-[#1e1f22] mb-8 flex items-center gap-4 font-serif">
                            <span class="w-8 h-8 bg-[#fff0f3] text-[#ff5c8a] rounded-full flex items-center justify-center text-xs font-normal">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            {{ mb_strtolower($category->display_name) }}
                        </h4>

                        <div class="space-y-6">
                            @forelse($category->services as $service)
                                <a href="{{ route('services.show', $service->getKey()) }}">
                                <div class="group flex justify-between items-center border-b border-dashed border-gray-200 pb-4 hover:border-[#ff5c8a] transition-colors duration-300">

                                    <div class="max-w-[60%] text-left space-y-1">
                                        <span class="text-base font-normal text-gray-800 group-hover:text-[#ff5c8a] transition-colors">
                                            {{ mb_strtolower($service->name) }}
                                        </span>

                                        {{-- Длительность --}}
                                        @if($service->levels->isNotEmpty())
                                            @php
                                                $minDuration = $service->levels->min('pivot.duration');
                                            @endphp
                                            @if($minDuration)
                                                <p class="text-xs text-[#9ca0b0] font-light tracking-wide">
                                                    время: от {{ str_replace('.', ',', $minDuration / 60) }} {{ ($minDuration / 60) <= 1.5 ? 'часа' : 'часов' }}
                                                </p>
                                            @endif
                                        @endif

                                        @if($service->description)
                                            <p class="text-xs text-gray-400 font-light leading-relaxed pt-0.5">
                                                {{ mb_strtolower($service->description) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-xl font-normal text-gray-900 whitespace-nowrap group-hover:translate-x-[-10px] transition-transform duration-300">
                                            @if($service->levels->isNotEmpty())
                                                от {{ number_format($service->levels->min('pivot.price'), 0, '.', ' ') }} ₽
                                            @else
                                                цена не задана
                                            @endif
                                        </span>

                                        <button class="opacity-0 group-hover:opacity-100 bg-[#ff5c8a] text-white px-4 py-2 rounded-full text-xs tracking-wider font-normal transition-all duration-300 transform translate-x-4 group-hover:translate-x-0 hover:cursor-pointer">
                                            записаться
                                        </button>
                                    </div>
                                </div>
                                </a>
                            @empty
                                <p class="text-gray-400 font-light text-left text-xs">в данной категории пока нет услуг.</p>
                            @endforelse
                        </div>

                    </div>
                @endforeach
            </div>

        </section>
    </section>

</x-app-layout>
