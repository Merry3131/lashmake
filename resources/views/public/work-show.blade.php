<x-app-layout title="Центр Ресниц | {{ $works->service->name ?? 'Работа' }}">

    <style>
        h1, h2, h3, h4, h5, h6,
        .font-serif,
        [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }

        body, p, span, button, a, li, div,
        .text-gray-600, .text-gray-500, .text-gray-400,
        input, textarea, select {
            font-family: 'Manrope', sans-serif;
        }

        button, a, .btn {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    <div class="w-full bg-cover bg-center pt-12 sm:pt-16 pb-12 sm:pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-16 sm:h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center relative">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal text-[#1e1f22] mb-3 sm:mb-4 font-serif">
                {{ $works->service->name ?? 'Работа' }}
            </h1>
            <div class="w-20 sm:w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-3 sm:mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                {{ $works->service->category->display_name ?? 'Работа' }}
            </p>
        </div>
    </div>

    <section class="max-w-7xl mx-auto pb-16 sm:pb-24 px-4 sm:px-6 mt-6 sm:mt-8">
        <div class="mb-6">
            <a href="{{ route('works.index') }}"
               class="inline-flex items-center gap-2 text-pink-500 hover:text-pink-600 transition-colors duration-300 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Назад к работам
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12">
            <div class="lg:col-span-6">
                <div class="px-4 rounded-3xl h-full">
                    <div class="aspect-square relative overflow-hidden rounded-2xl h-full">
                        @if($works->hasMedia('works'))
                            <img src="{{ $works->getFirstMediaUrl('works') }}"
                                 alt="{{ $works->service->name ?? 'Работа' }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-pink-50 text-[#ff5c8a] text-sm">
                                Фото отсутствует
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 space-y-6 sm:space-y-8">
                <div class="bg-white p-6 sm:p-8 lg:p-10 rounded-3xl border border-[#f1f1f5] space-y-6 h-full flex flex-col justify-between">
                    <div class="flex-1 space-y-6">
                        <div>
                            <h3 class="text-xl text-[#1e1f22] font-normal font-serif mb-3">
                                О работе
                            </h3>
                            <p class="text-sm text-[#7c7e8c] font-light leading-relaxed">
                                {{ $works->description ?? 'Описание работы временно отсутствует.' }}
                            </p>
                        </div>

                        <hr class="border-dashed border-gray-200">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 py-4">
                            <div class="flex flex-col gap-3">
                                <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Услуга</p>
                                <span class="text-sm text-[#1e1f22] font-medium">
                                    {{ $works->service->name ?? 'Услуга' }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Мастер</p>
                                <span class="text-sm text-[#1e1f22] font-medium">
                                    {{ $works->specialist->user->first_name ?? 'Мастер' }} {{ $works->specialist->user->last_name ?? '' }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Категория</p>
                                <span class="text-sm text-[#1e1f22] font-medium">
                                    {{ $works->service->category->display_name ?? 'Категория' }}
                                </span>
                            </div>

                            @if($works->created_at)
                                <div class="flex flex-col gap-3">
                                    <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Дата</p>
                                    <span class="text-sm text-[#1e1f22] font-medium">
                                        {{ $works->created_at->format('d.m.Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <hr class="border-dashed border-gray-200">
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('team.show', $works->specialist->getKey()) }}"
                           class="inline-block border border-pink-500 text-pink-500 text-center rounded-xl py-3 px-6 text-sm tracking-wider font-medium transition-all duration-300 hover:bg-pink-50 hover:shadow-lg hover:scale-[1.02] active:scale-95 w-full sm:w-auto">
                            Смотреть мастера
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($works->service->description ?? false)
            <div class="mt-8 sm:mt-12">
                <div class="bg-white p-6 sm:p-8 lg:p-10 rounded-3xl border border-[#f1f1f5]">
                    <h3 class="text-xl text-[#1e1f22] font-normal font-serif mb-4">
                        Об услуге
                    </h3>
                    <p class="text-sm text-[#7c7e8c] font-light leading-relaxed">
                        {{ $works->service->description }}
                    </p>
                </div>
            </div>
        @endif
    </section>

</x-app-layout>
