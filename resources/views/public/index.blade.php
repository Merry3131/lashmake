<x-app-layout title="Центр Ресниц | Главная">

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

    <div class="relative bg-no-repeat bg-cover bg-center"
         style="background-image: url('{{ asset('img/bg_main.png') }}');">

        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px] pointer-events-none"></div>

        <section class="max-w-7xl mx-auto pt-20 pb-24 md:pt-28 md:pb-36 px-6 flex flex-col md:flex-row items-center justify-between gap-12 relative overflow-hidden">

            <div class="relative z-10 w-full md:w-1/2 flex justify-center md:justify-start hidden md:flex">
                <div class="w-full max-w-sm md:max-w-md aspect-[3/4] overflow-hidden relative group rounded-2xl shadow-xl">
                    <div class="absolute -bottom-4 -left-4 w-full h-full border-2 border-[#bd0055]/20 rounded-2xl -z-10 pointer-events-none transition-transform duration-500 group-hover:-translate-x-1 group-hover:translate-y-1"></div>

                    <img src="{{ asset('img/card_1.jpg') }}"
                         alt="Эстетика ресниц крупным планом"
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent opacity-60 pointer-events-none"></div>
                </div>
            </div>

            <div class="relative z-10 w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left">
                <span class="text-xl text-[#bd0055] font-light mb-4">
                    Салон красоты Алёны Хабибуллиной
                </span>
                <h1 class="text-2xl md:text-4xl text-gray-900">
                    Искусство быть собой
                </h1>
                <p class="mt-6 text-gray-600 text-sm md:text-base tracking-wide leading-relaxed font-light max-w-md mx-auto md:mx-0">
                    Высокое качество материалов и профессионализм мастеров для вашей уверенности и естественной красоты
                </p>

                <div class="mt-10 relative z-20 flex justify-center w-full md:justify-start">
                    <button x-data @click="$store.modalManager.openBooking()"
                            class="group relative border border-pink-500 text-pink-600 px-12 py-3.5 rounded-full text-sm font-light tracking-wide shadow-sm transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-500 overflow-hidden bg-transparent hover:text-white hover:shadow-lg hover:shadow-pink-900/20">
                        <span class="absolute inset-0 bg-pink-500 scale-x-0 group-hover:scale-x-100 group-hover:bg-pink-500 origin-left transition-transform duration-500 ease-out pointer-events-none"></span>
                        <span class="relative z-10">Записаться онлайн</span>
                    </button>
                </div>
            </div>

        </section>
    </div>

    <div class="bg-[#fcfaf7] pb-32 pt-20">
        <section class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">

            <div class="md:col-span-5 aspect-[4/5] bg-gray-100 relative shadow-md overflow-hidden rounded-2xl group">
                <img src="{{ asset('img/in1.webp') }}"
                     alt="Процесс создания идеального взгляда в Центр Ресниц"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out">
                <div class="absolute inset-0 bg-gradient-to-t from-black/5 via-transparent to-transparent pointer-events-none"></div>
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
                    <div class="bg-gray-200 relative group overflow-hidden min-h-[320px] md:min-h-[380px] transition-all duration-500 rounded-2xl hover:z-30 hover:shadow-2xl hover:shadow-pink-500/30
                        {{ $index == 0 ? 'md:col-span-7' : '' }}
                        {{ $index == 1 ? 'md:col-span-5' : '' }}
                        {{ $index == 2 ? 'md:col-span-5' : '' }}
                        {{ $index == 3 ? 'md:col-span-7' : '' }}
                        {{ $index > 3 ? 'md:col-span-6' : '' }}">
                        <div class="absolute inset-0 bg-black/30 transition-colors duration-500 z-10 group-hover:bg-black/20"></div>

                        @if($category->hasMedia('categories'))
                            <img src="{{ $category->getFirstMediaUrl('categories') }}" alt="{{ $category->display_name }}"
                                 class="absolute inset-0 w-full h-full object-cover grayscale brightness-75 group-hover:grayscale-0 group-hover:brightness-100 group-hover:scale-105 transition-all duration-700 ease-out">
                        @endif

                        <div class="absolute inset-0 z-20 p-8 flex flex-col justify-end text-white">
                            <h3 class="text-xl md:text-2xl font-serif tracking-wide mb-2">
                                {{ $category->display_name }}
                            </h3>
                            <p class="text-xs text-gray-200 max-w-sm font-light opacity-90 mb-4 line-clamp-2">
                                {{ $category->description }}
                            </p>
                            <div>
                                <a href="{{ url('/services') }}"
                                   class="inline-block text-[11px] px-4 py-2.5 font-medium tracking-[0.15em] bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-full shadow-md hover:shadow-lg hover:from-pink-600 hover:to-rose-600 transition-all duration-300 ease-out hover:scale-105 active:scale-95">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="bg-[#fcfaf7] pb-32"
         x-data="{
        images: [
            '{{ asset('img/studia/1.jpg') }}',
            '{{ asset('img/studia/2.jpg') }}',
            '{{ asset('img/studia/3.jpg') }}',
            '{{ asset('img/studia/4.webp') }}',
            '{{ asset('img/studia/5.webp') }}',
            '{{ asset('img/studia/6.webp') }}'
        ],

        startIndex: 0,

        get itemsToShow() {
            if (window.innerWidth < 640) return 1;
            if (window.innerWidth < 1024) return 2;
            return 3;
        },

        next() {
            if (this.startIndex + this.itemsToShow < this.images.length) {
                this.startIndex++;
            } else {
                this.startIndex = 0;
            }
        },

        prev() {
            if (this.startIndex > 0) {
                this.startIndex--;
            } else {
                this.startIndex = this.images.length - this.itemsToShow;
            }
        }
     }"
         @resize.window="startIndex = 0"
    >
        <section class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-2xl md:text-3xl font-serif text-gray-900 tracking-wide max-w-xs md:max-w-md">
                    Атмосфера абсолютного комфорта
                </h2>
                <div class="flex gap-2">
                    <button @click="prev()"
                            class="w-10 h-10 border border-gray-300 flex items-center justify-center text-pink-500 hover:text-pink-500 hover:border-pink-500 rounded-xl transition-all duration-200">
                        &#8592;
                    </button>
                    <button @click="next()"
                            class="w-10 h-10 border border-gray-300 flex items-center justify-center text-pink-500 hover:text-pink-500 hover:border-pink-500 rounded-xl transition-all duration-200">
                        &#8594;
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 overflow-hidden">

                <template x-for="(image, index) in images" :key="image">
                    <div x-show="index >= startIndex && index < (startIndex + itemsToShow)"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform scale-95 translate-x-4"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-x-0"
                         class="aspect-[4/3] bg-gray-200 relative shadow-sm overflow-hidden rounded-lg">

                        <img :src="image"
                             alt="Интерьер студии Центр Ресниц"
                             class="w-full h-full object-cover transform scale-100 hover:scale-105 transition-transform duration-700 ease-out">
                    </div>
                </template>

            </div>
        </section>
    </div>

    <div class="bg-[#f5f2ed] py-12 sm:py-16 md:py-24">
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-serif text-center text-gray-900 tracking-wide mb-10 sm:mb-12 md:mb-16">
                Наши специалисты
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                @foreach($specialists->take(4) as $specialist)
                    <div class="text-center group max-w-[400px] mx-auto w-full sm:max-w-none">
                        <div class="aspect-[3/4] bg-gray-300 mb-2 sm:mb-3 md:mb-4 overflow-hidden transition-transform duration-300 group-hover:shadow-md rounded-lg md:rounded-none">
                            @if($specialist->hasMedia('specialists'))
                                <img src="{{ $specialist->getFirstMediaUrl('specialists') }}"
                                     class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                            @endif
                        </div>
                        <h4 class="text-sm sm:text-md md:text-base lg:text-lg text-gray-900 font-medium">
                            {{ $specialist->user->first_name }} {{ $specialist->user->last_name }}
                        </h4>
                        <p class="text-sm sm:text-xs md:text-sm text-gray-400 mt-1 font-light line-clamp-2 md:line-clamp-none px-1">
                            {{ $specialist->bio }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 sm:mt-12 md:mt-14 text-center">
                <a href="/team" class="inline-block border border-gray-900 text-gray-700 px-5 sm:px-6 md:px-8 py-2 sm:py-2.5 md:py-3 rounded-full text-sm sm:text-xs md:text-sm uppercase tracking-widest hover:text-pink-500 hover:border-pink-500 transition-all duration-300">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                @foreach($works->take(4) as $work)
                    <div class="aspect-square bg-gray-100 relative shadow-sm overflow-hidden rounded-xl group max-w-[400px] mx-auto w-full sm:max-w-none">
                        @if($work->hasMedia('works'))
                            <img src="{{ $work->getFirstMediaUrl('works') }}"
                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                 alt="{{ $work->service->name ?? 'Пример работы' }}">
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="bg-[#fcfaf7] pb-24">
        <section class="max-w-7xl mx-auto px-6">
            <div class="bg-no-repeat bg-cover bg-center text-[#3A0007] py-16 px-8 text-center rounded-[2rem] shadow-sm relative overflow-hidden"
                 style="background-image: url('{{ asset('img/bg_block.jpg') }}');">

                <div class="relative z-10 max-w-xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-serif tracking-wide mb-4">
                        Готовы к преображению?
                    </h2>
                    <p class="text-white/90 text-sm md:text-base font-light tracking-wide mb-8 text-[#3A0007]">
                        Запишитесь прямо сейчас к нашему топ-мастеру онлайн и получите бесплатный сеанс расслабляющего массажа тела.
                    </p>
                    <button x-data @click="$store.modalManager.openBooking()"
                            class="bg-gradient-to-r from-rose-500 to-pink-500 text-white px-10 py-4 uppercase text-xs font-bold tracking-[0.2em] hover:from-rose-600 hover:to-pink-600 hover:shadow-xl hover:scale-105 active:scale-95 rounded-full transition-all duration-300 shadow-lg">
                        Записаться
                    </button>
                </div>
                <div class="absolute inset-0 bg-gradient-to-tr from-pink-600/10 to-transparent pointer-events-none"></div>
            </div>
        </section>
    </div>

    <x-booking-modal />
</x-app-layout>
