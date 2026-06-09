<x-app-layout title="Центр Ресниц | Главная">

    <div class="relative bg-no-repeat bg-cover bg-center"
         style="background-image: url('{{ asset('img/bg_main.png') }}');">

        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px] pointer-events-none"></div>

        <section class="max-w-7xl mx-auto pt-20 pb-24 md:pt-28 md:pb-36 px-6 flex flex-col md:flex-row items-center justify-between gap-12 relative overflow-hidden">

            <div class="relative z-10 w-full md:w-1/2 flex justify-center md:justify-start">
                <div class="w-full max-w-sm md:max-w-md aspect-[3/4] overflow-hidden relative group rounded-2xl shadow-xl">
                    <div class="absolute -bottom-4 -left-4 w-full h-full border-2 border-[#bd0055]/20 rounded-2xl -z-10 pointer-events-none transition-transform duration-500 group-hover:-translate-x-1 group-hover:translate-y-1"></div>

                    <img src="{{ asset('img/card_1.jpg') }}"
                         alt="Эстетика ресниц крупным планом"
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent opacity-60 pointer-events-none"></div>
                </div>
            </div>

            <div class="relative z-10 w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left">
            <span class="text-xs tracking-widest text-[#bd0055] font-light mb-4">
                Студия красоты Алёны Хабибуллиной
            </span>
                <h1 class="text-4xl md:text-6xl font-serif text-gray-900 leading-tight">
                    Искусство быть собой
                </h1>
                <p class="mt-6 text-gray-600 text-sm md:text-base tracking-wide leading-relaxed font-light max-w-md">
                    Высокое качество материалов и профессионализм мастеров для вашей уверенности и естественной красоты
                </p>

                <div class="mt-10 relative z-20 flex justify-between w-full">
                    <button x-data @click="$store.modalManager.openBooking()"
                            class="bg-pink-500 text-white px-12 py-3.5 rounded-full text-sm font-light tracking-wide transition-all duration-500 shadow-md shadow-pink-950/10 hover:bg-pink-800 hover:shadow-lg hover:shadow-pink-900/20 transform hover:-translate-y-0.5 active:translate-y-0">
                        Записаться онлайн
                    </button>
                </div>
            </div>

        </section>
    </div>

    <div class="bg-[#fcfaf7] pb-32 pt-20">
        <section class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-5 aspect-[4/5] bg-gray-200 relative shadow-sm overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest p-4 text-center">
                    [ Эстетичное фото инструментов/процесса ]
                </div>
            </div>

            <div class="md:col-span-7 md:pl-8">
                <span class="text-5xl text-[#bd0055] font-serif block mb-1">“</span>
                <h2 class="text-2xl md:text-3xl font-serif text-gray-900 leading-snug mb-6">
                    Мы не просто создаем образ, мы подчеркиваем вашу уникальность.
                </h2>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed max-w-xl font-light">
                    В нашей студии каждый клиент — это история. Мы используем только премиальные материалы и авторские техники, подбирая идеальный изгиб, длину и эффект индивидуально под форму ваших глаз и архитектуру лица.
                </p>
            </div>
        </section>
    </div>

    <div id="services" class="bg-[#fcfaf7] pb-32">
        <section class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-serif text-center text-gray-900 tracking-wide mb-16">
                Наши Услуги
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                @foreach($categories as $index => $category)
                    <div class="bg-gray-200 relative group overflow-hidden min-h-[320px] md:min-h-[380px]
                        {{ $index == 0 ? 'md:col-span-7' : '' }}
                        {{ $index == 1 ? 'md:col-span-5' : '' }}
                        {{ $index == 2 ? 'md:col-span-5' : '' }}
                        {{ $index == 3 ? 'md:col-span-7' : '' }}
                        {{ $index > 3 ? 'md:col-span-6' : '' }}">

                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-500 z-10"></div>

                        <img src="/img/categories/{{ $category->slug }}.jpg" alt="{{ $category->display_name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                        <div class="absolute inset-0 z-20 p-8 flex flex-col justify-end text-white">
                            <h3 class="text-xl md:text-2xl font-serif tracking-wide mb-2">
                                {{ $category->display_name }}
                            </h3>
                            <p class="text-xs text-gray-200 max-w-sm font-light opacity-90 mb-4 line-clamp-2">
                                {{ $category->description }}
                            </p>
                            <div>
                                <a href="{{ url('/services') }}" class="inline-block text-[10px] uppercase tracking-[0.2em] border-b border-white/60 pb-1 hover:border-white transition-all">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="bg-[#fcfaf7] pb-32">
        <section class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-2xl md:text-3xl font-serif text-gray-900 tracking-wide max-w-xs md:max-w-md">
                    Атмосфера абсолютного комфорта
                </h2>
                <div class="flex gap-2">
                    <button class="w-10 h-10 border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-200">
                        &#8592;
                    </button>
                    <button class="w-10 h-10 border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-200">
                        &#8594;
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="aspect-[4/3] bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest">[ Интерьер 1 ]</div>
                <div class="aspect-[4/3] bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest">[ Интерьер 2 ]</div>
                <div class="aspect-[4/3] bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest hidden sm:flex">[ Интерьер 3 ]</div>
            </div>
        </section>
    </div>

    <div class="bg-[#f5f2ed] py-24">
        <section class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-serif text-center text-gray-900 tracking-wide mb-16">
                Наши специалисты
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-10">
                @foreach($specialists as $specialist)

                    <div class="text-center group">
                        <div class="aspect-[3/4] bg-gray-300 mb-4 overflow-hidden transition-transform duration-300 group-hover:shadow-md">
                            <img src="" alt="" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                        </div>
                        <h4 class="font-semibold text-sm md:text-base text-gray-900">
                            {{ $specialist->user->first_name }} {{ $specialist->user->last_name }}
                        </h4>
                        <p class="text-xs text-gray-400 mt-1 font-light">
                            {{ $specialist->bio }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="/team" class="inline-block border border-gray-400 text-gray-700 px-8 py-3 rounded-full text-xs uppercase tracking-widest hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300">
                    Показать всех
                </a>
            </div>
        </section>
    </div>

    <div class="bg-[#fcfaf7] py-24">
        <section class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-2xl md:text-3xl font-serif text-gray-900 tracking-wide">
                    Примеры работ
                </h2>
                <a href="{{ url('/example_of_works') }}" class="text-xs uppercase tracking-widest border-b border-gray-400 pb-1 text-gray-500 hover:text-gray-900 transition-colors duration-200">
                    Смотреть все →
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest">[ Работа 1 ]</div>
                <div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest">[ Работа 2 ]</div>
                <div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest">[ Работа 3 ]</div>
                <div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest">[ Работа 4 ]</div>
            </div>
        </section>
    </div>

    <div class="bg-[#fcfaf7] pb-24 " >
        <section class="max-w-7xl mx-auto px-6">
            <div class=" bg-no-repeat bg-cover bg-center text-[#3A0007] py-16 px-8 text-center rounded-[2rem] shadow-sm relative overflow-hidden"
                 style="background-image: url('{{ asset('img/bg_block.jpg') }}');"> {{-- <- Закрыта кавычка здесь --}}

                <div class="relative z-10 max-w-xl mx-auto ">
                    <h2 class="text-3xl md:text-4xl font-serif tracking-wide mb-4 ">
                        Готовы к преображению?
                    </h2>
                    <p class="text-white/90 text-sm md:text-base font-light tracking-wide mb-8 text-[#3A0007]">
                        Запишитесь прямо сейчас к нашему топ-мастеру онлайн и получите бесплатный сеанс расслабляющего массажа тела.
                    </p>
                    <button x-data @click="$store.modalManager.openBooking()"
                            class="bg-white text-red-600 border border-pink-200 px-10 py-4 uppercase text-xs font-bold tracking-[0.2em] hover:bg-gray-900 hover:text-white hover:border-gray-900 rounded-xl transition-all duration-300 shadow-sm">
                        Записаться
                    </button>
                </div>
                <div class="absolute inset-0 bg-gradient-to-tr from-pink-600/10 to-transparent pointer-events-none"></div>
            </div>
        </section>
    </div>

    <x-booking-modal />
</x-app-layout>
