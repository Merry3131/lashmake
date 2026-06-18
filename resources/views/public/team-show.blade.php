<x-app-layout title="Центр Ресниц | {{ $team->user->first_name }} {{ $team->user->last_name }}">

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
                {{ $team->user->first_name }} {{ $team->user->last_name }}
            </h1>
            <div class="w-20 sm:w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-3 sm:mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                {{ $team->level->display_name ?? 'Специалист' }}
            </p>
        </div>
    </div>

    <section class="max-w-7xl mx-auto pb-16 sm:pb-24 px-4 sm:px-6 mt-6 sm:mt-8">
        <div class="mb-6">
            <a href="{{ route('team.index') }}"
               class="inline-flex items-center gap-2 text-pink-500 hover:text-pink-600 transition-colors duration-300 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Назад к специалистам
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 mb-4">
            <div class="lg:col-span-4 md:col-span-4">
                <div class="p-4 rounded-3xl ">
                    <div class="aspect-[3/4] relative overflow-hidden rounded-2xl max-w-[300px] mx-auto">
                        @if($team->hasMedia('specialists'))
                            <img src="{{ $team->getFirstMediaUrl('specialists') }}"
                                 alt="{{ $team->user->first_name }} {{ $team->user->last_name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-pink-50 text-[#ff5c8a] text-sm">
                                Фото отсутствует
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 md:col-span-8 space-y-6 sm:space-y-8">
                <div class="bg-white p-6 sm:p-8 lg:p-10 rounded-3xl border border-[#f1f1f5] space-y-6">
                    <div>
                        <h3 class="text-xl text-[#1e1f22] font-normal font-serif mb-3">
                            О специалисте
                        </h3>
                        <p class="text-sm text-[#7c7e8c] font-light leading-relaxed">
                            {{ $team->bio ?? 'Сертифицированный специалист по созданию идеального взгляда.' }}
                        </p>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 py-4">
                        <div class="flex flex-col gap-3">
                            <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Квалификация</p>
                            <span class="text-sm text-[#1e1f22] font-medium">
                                {{ $team->level->display_name ?? 'Мастер' }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-3">
                            <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Стаж</p>
                            <span class="text-sm text-[#1e1f22] font-medium">
                                {{ $team->experience ?? 'Уточняется' }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-3 sm:col-span-2">
                            <p class="text-sm tracking-wide text-[#9ca0b0] uppercase">Рейтинг</p>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-[#1e1f22] font-medium">
                                    {{ $team->averageRating() }}
                                </span>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($team->averageRating()) ? 'text-yellow-400 fill-current' : 'text-gray-300 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-400 font-light">
                                    ({{ $team->reviews->count() }} {{ trans_choice('отзыв|отзыва|отзывов', $team->reviews->count()) }})
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    <div>
                        <h3 class="text-lg text-[#1e1f22] font-normal font-serif mb-4">
                            Услуги мастера
                        </h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                            @forelse($team->service_specialist as $service)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-[#f8f8fa] p-3 sm:p-4 rounded-xl border border-[#f1f1f5] gap-2 sm:gap-0 overflow-hidden">
                                    <span class="text-sm text-[#1e1f22] font-medium break-words w-full sm:w-auto max-w-[70%] sm:max-w-[60%]">
                                        {{ $service->name ?? 'Услуга' }}
                                    </span>
                                    <span class="text-sm text-pink-500 font-semibold whitespace-nowrap flex-shrink-0">
                                        @php
                                            $price = $service->pivot->price ?? ($service->levels->first()?->pivot->price ?? 0);
                                        @endphp
                                        {{ number_format($price, 0, '.', ' ') }} ₽
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 col-span-2">Услуги временно не добавлены</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 sm:mt-12">
            <div class="bg-white p-6 sm:p-8 lg:p-10 rounded-3xl border border-[#f1f1f5] mx-auto">
                <h3 class="text-xl text-[#1e1f22] font-normal mb-6">
                    Отзывы
                </h3>
                <div class="space-y-4">
                    @forelse($team->reviews as $review)
                        <div class="bg-[#f8f8fa] p-4 sm:p-6 rounded-xl border border-[#f1f1f5]">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="text-sm font-medium text-[#1e1f22]">
                                        {{ $review->user->first_name ?? 'Клиент' }} {{ $review->user->last_name ?? '' }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300 fill-current' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-400 font-light">
                                    {{ $review->created_at->format('d.m.Y') }}
                                </span>
                            </div>
                            <p class="text-sm text-[#7c7e8c] font-light leading-relaxed">
                                {{ $review->comment }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Отзывов пока нет</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
