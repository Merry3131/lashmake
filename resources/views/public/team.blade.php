<x-app-layout title="Центр Ресниц | Специалисты">
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
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest mb-4 font-serif">
                Наши специалисты
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                Команда профессионалов, влюбленных в свое дело
            </p>
        </div>
    </div>

    <section class="max-w-7xl mx-auto pt-12 pb-24 px-6">
        @foreach($team->groupBy('level.name') as $levelName => $members)
            <div class="mb-20">
                <div class="mb-8 border-l-4 border-[#ff5c8a] pl-4 space-y-1">
                    <h3 class="text-2xl text-[#1e1f22] tracking-wide font-normal font-serif">
                        @switch(mb_strtolower($levelName))
                            @case('lead') Ведущий специалист @break
                            @case('top') Топ-мастер @break
                            @case('master') Мастер @break
                            @default {{ $levelName }}
                        @endswitch
                    </h3>

                    <p class="text-xs text-[#7c7e8c] font-light tracking-wide max-w-2xl">
                        @switch(mb_strtolower($levelName))
                            @case('lead')
                                Эксперт высшей категории, наставник команды и руководитель направлений. Обладает максимальной скоростью работы, филигранной техникой и опытом более 5 лет.
                                @break
                            @case('top')
                                Специалист с повышенной квалификацией и багажом сотен идеальных работ. В совершенстве владеет сложными трендовыми эффектами и ультраточным моделированием взгляда.
                                @break
                            @case('master')
                                Сертифицированный дипломированный профессионал. Безупречно выполняет базовые и классические техники, строго соблюдая все стандарты безопасности и качества нашей студии.
                                @break
                            @default
                                Профессиональный специалист нашей студии.
                        @endswitch
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($members as $member)

                        <div class="group p-6 bg-white rounded-3xl border border-[#f1f1f5] flex flex-col sm:flex-row gap-6 items-center sm:items-start hover:shadow-xl hover:shadow-pink-200 transition-all duration-300">

                            <div class="relative w-44 h-44 flex-shrink-0 bg-[#f8f8fa] rounded-2xl overflow-hidden">
                                @if($member->hasMedia('specialists'))
                                    <img src="{{ $member->getFirstMediaUrl('specialists') }}"
                                         class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                                @endif

                                @if($member->specialization && mb_strtolower($member->specialization) === 'Руководитель')
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-[#bd0055] text-white text-[9px] tracking-widest  px-3 py-1 rounded-full font-medium whitespace-nowrap">
                                        Руководитель
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col flex-grow text-center sm:text-left h-full justify-between">
                                <div>
                                    <span class="text-sm font-normal tracking-widest text-pink-500 block mb-1">
                                        @switch(mb_strtolower($levelName))
                                            @case('lead') Ведущий специалист @break
                                            @case('top') Топ-мастер @break
                                            @case('master') Мастер @break
                                            @default {{ mb_strtolower($member->specialization ?? $levelName) }}
                                        @endswitch
                                    </span>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-3 justify-center sm:justify-start">
                                        <h4 class="text-2xl font-normal text-[#1e1f22] group-hover:text-pink-500 transition-colors duration-300 font-serif">
                                            {{ $member->user->first_name }} {{ $member->user->last_name }}
                                        </h4>


                                        <div class="flex items-center justify-center gap-1 bg-pink-50 px-2.5 py-0.5 rounded-full w-max mx-auto sm:mx-0 border border-pink-100">
                                            <span class="text-xs font-semibold text-[#ff5c8a]">{{ $member->averageRating() }}</span>
                                            <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-[10px] text-gray-400 font-light">({{ $member->reviews->count() }})</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-[#7c7e8c] font-light leading-relaxed mb-4">
                                        {{ $member->bio ?? 'Сертифицированный специалист по созданию идеального взгляда.' }}
                                    </p>
                                </div>


                                <div class="pt-2 flex justify-end">
                                    <button x-data @click="$store.modalManager.openBooking({ specialist_id: {{ $member->id }} })"
                                            class="opacity-0 group-hover:opacity-100 inline-block bg-pink-500 text-white rounded-xl py-3 px-6 text-xs tracking-wider font-normal transition-all duration-300 transform translate-x-4 group-hover:translate-x-0 hover:cursor-pointer w-full sm:w-auto text-center shadow-sm">
                                        Подробнее
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

        @endforeach

    </section>

</x-app-layout>
