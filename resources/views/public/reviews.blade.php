<x-app-layout title="Центр Ресниц | Отзывы">
    <style>
        h1, h2, h3, h4, h5, h6,
        .font-serif,
        [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }

        body, p, span, button, a, li, div,
        .text-gray-600, .text-gray-500, .text-gray-400,
        .tracking-widest,
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
                Отзывы наших клиентов
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                Ваши искренние эмоции — главная мотивация нашей команды
            </p>
        </div>
    </div>

    <section x-data="{ activeCategory: 'all' }" class="max-w-7xl mx-auto pt-6 pb-24 px-6">

        <div class="flex flex-wrap justify-center items-center gap-3 mb-12">

            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-[#ff5c8a] text-white border-[#ff5c8a]' : 'bg-white text-[#7c7e8c] border-[#f1f1f5] hover:border-[#ff5c8a]/40'"
                    class="px-5 py-2.5 rounded-full border text-xs tracking-wider font-normal transition-all duration-300 hover:cursor-pointer shadow-sm">
                Все отзывы
            </button>
            @php
                $uniqueCategories = $reviews->map(function($review) {
                    return $review->appointment->service->category;
                })->unique('id');
            @endphp

            @foreach($uniqueCategories as $category)
                <button @click="activeCategory = '{{ $category->id }}'"
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-[#ff5c8a] text-white border-[#ff5c8a]' : 'bg-white text-[#7c7e8c] border-[#f1f1f5] hover:border-[#ff5c8a]/40'"
                        class="px-5 py-2.5 rounded-full border text-xs tracking-wider font-normal transition-all duration-300 hover:cursor-pointer shadow-sm">
                    {{ $category->display_name }}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
                @php
                    $categoryId = $review->appointment->service->category_id;
                @endphp
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $categoryId }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="bg-white rounded-2xl border border-pink-100 flex flex-col p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <div class="flex justify-between items-start mb-5 bg-pink-100 -mx-6 -mt-6 p-4 rounded-t-2xl">
                        <div>
                            <h5 class="text-base text-gray-800 leading-tight">
                                <span class="text-xs text-pink-500 tracking-wider">Клиент:</span> {{ $review->user->first_name }} {{ $review->user->last_name }}
                            </h5>
                            <span class="text-sm text-gray-400 font-light tracking-wider">
                        {{ $review->created_at->format('d.m.Y') }}
                    </span>
                        </div>

                        <div class="flex text-amber-400 text-xs gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 mb-5">
                        <div class="flex items-baseline gap-2">
                            <span class="text-xs font-semibold text-pink-500 tracking-wider">Категория:</span>
                            <span class="text-sm text-gray-700 font-normal">{{ $review->appointment->service->category->display_name }}</span>
                        </div>

                        <div class="flex items-baseline gap-2">
                            <span class="text-xs font-semibold text-pink-500 tracking-wider">Услуга:</span>
                            <span class="text-sm text-gray-700 font-normal">{{ $review->appointment->service->name }}</span>
                        </div>

                        <div class="flex items-baseline gap-2">
                            <span class="text-xs font-semibold text-pink-500 tracking-wider">Мастер:</span>
                            <span class="text-sm text-gray-700 font-normal">{{ $review->specialist->user->first_name }}</span>
                        </div>
                    </div>

                    <div class="flex-grow">
                        <div class="relative">
                            <svg class="absolute -top-1 -left-1 w-5 h-5 text-pink-200 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                            <p class="text-gray-600 text-sm font-light leading-relaxed pl-5 italic">
                                «{{ $review->comment }}»
                            </p>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>


    </section>

</x-app-layout>
