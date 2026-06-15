<x-app-layout title="Центр Ресниц | Примеры работ">

    <style>
        h1, h2, h3, h4, h5, h6,
        .font-serif,
        [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }

        body, p, span, button, a, li, div,
        .text-gray-600, .text-gray-500, .text-gray-400,
        .tracking-widest, .,
        input, textarea, select {
            font-family: 'Manrope', sans-serif;
        }

        button, a, .btn {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest  mb-4 font-serif">
                Наши работы
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                Галерея преображений и реальные результаты наших мастеров
            </p>
        </div>
    </div>

    <section x-data="{ activeCategory: 'all' }" class="max-w-7xl mx-auto pt-6 pb-24 px-6">
        <div class="flex flex-wrap justify-center items-center gap-3 mb-12">
            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-[#ff5c8a] text-white border-[#ff5c8a]' : 'bg-white text-[#7c7e8c] border-[#f1f1f5] hover:border-[#ff5c8a]/40'"
                    class="px-5 py-2.5 rounded-full border text-xs tracking-wider  font-normal transition-all duration-300 hover:cursor-pointer shadow-sm">
                все работы
            </button>
            @php
                $uniqueCategories = $works->map(function($work) {
                    return $work->service->category;
                })->filter()->unique('id');
            @endphp

            @foreach($uniqueCategories as $category)
                <button @click="activeCategory = '{{ $category->id }}'"
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-[#ff5c8a] text-white border-[#ff5c8a]' : 'bg-white text-[#7c7e8c] border-[#f1f1f5] hover:border-[#ff5c8a]/40'"
                        class="px-5 py-2.5 rounded-full border text-xs tracking-wider  font-normal transition-all duration-300 hover:cursor-pointer shadow-sm">
                    {{ $category->display_name}}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($works as $work)
                @php
                    $categoryId = $work->service->category_id ?? 'none';
                @endphp
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $categoryId }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="group bg-white rounded-3xl overflow-hidden border border-[#f1f1f5] flex flex-col hover:shadow-sm transition-all duration-300">
                    <div class="aspect-square relative bg-[#f8f8fa] overflow-hidden flex-shrink-0">
                        @if($work->hasMedia('works'))
                            <img src="{{ $work->getFirstMediaUrl('works') }}"
                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                 alt="{{ $work->service->name }}">
                        @else
                             Заглушка, если фотографии временно нет
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 text-xs gap-2">
                                <i class="far fa-image text-2xl opacity-40"></i>
                                <span class="font-light italic">Фото в процессе публикации</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm border border-[#f1f1f5] text-[#1e1f22] text-sm  tracking-wider px-3 py-1.5 rounded-xl font-normal shadow-sm">
                            {{ $work->service->category->display_name ?? 'Услуга' }}
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow text-left">
                        <h4 class="text-xl font-normal text-[#1e1f22] mb-1 group-hover:text-[#ff5c8a] transition-colors duration-300 font-serif">
                            {{ $work->service->name }}
                        </h4>
                        <span class="text-lg font-normal  tracking-wider text-[#ff5c8a] block mb-3">
                            Мастер: {{ $work->specialist->user->first_name }}
                        </span>
                        @if($work->description)
                            <div class="text-gray-500 text-sm leading-relaxed flex-grow pl-3 border-l border-gray-100">
                                {{ $work->description }}
                            </div>
                        @endif

                    </div>

                </div>
            @endforeach
        </div>

    </section>

</x-app-layout>
