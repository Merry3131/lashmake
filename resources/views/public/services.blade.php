<x-app-layout title="Центр Ресниц | Услуги">
    <section class="max-w-7xl mx-auto pt-30 pb-12 px-6">
        <div class="mb-16">
            <h2 class="text-4xl lg:text-5xl font-serif text-center relative flex items-center justify-center gap-6 text-gray-900 mx-auto max-w-2xl">
                <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
                <span class="relative z-10 px-2 uppercase tracking-widest">Наши услуги</span>
                <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
            </h2>
            <p class="text-center text-gray-500 italic mt-4">Искусство преображения взгляда</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
            @foreach($categories as $category)
                <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col h-full">
                    <div class="aspect-[4/3] bg-gray-200 relative overflow-hidden flex-shrink-0">
                        <div class="absolute inset-0 bg-pink-900/10 group-hover:bg-transparent transition-colors duration-500"></div>
                        <img src="/img/categories/{{ $category->slug }}.jpg" alt="{{ $category->display_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>

                    <div class="p-8 text-center flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold mb-4 group-hover:text-pink-500 transition-colors">{{ $category->display_name }}</h3>
                        <p class="text-gray-600 font-light mb-8 leading-relaxed flex-grow">
                            {{ $category->description }}
                        </p>
                        <div class="mt-auto">
                            <a href="#category-{{ $category->id }}" class="block border border-pink-500 rounded-full py-2 px-6 w-full mt-6 font-medium hover:bg-pink-400 hover:text-white transition-all duration-300">
                                Смотреть услуги
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="max-w-5xl mx-auto mt-24 px-6 pb-20">

        @if($promotions->isNotEmpty())
            <section class="max-w-7xl mx-auto mt-20 px-6">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-widest inline-flex items-center gap-3">
                        <span class="text-pink-500">✨</span> Специальные предложения
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-4 w-full">
                    {{-- вывод  скидок--}}
                    @foreach($promotions as $promotion)
                        <div class="relative overflow-hidden bg-gradient-to-br from-pink-50 to-white border border-pink-100 p-8 rounded-3xl flex flex-col sm:flex-row items-center gap-6 group hover:shadow-md transition-all duration-300">

                            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-110 transition-transform">
                                {{ $promotion->type === 'model' ? '📸' : '🎁' }}
                            </div>

                            <div class="flex-grow text-center sm:text-left">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2 justify-center sm:justify-start">
                            <span class="text-xs font-bold uppercase tracking-tighter text-pink-500 bg-pink-100 px-2 py-0.5 rounded-md w-fit mx-auto sm:mx-0">
                                {{ $promotion->type_label }}
                            </span>
                                    <span class="text-gray-400 text-xs italic">
                                До {{ \Carbon\Carbon::parse($promotion->end_date)->translatedFormat('d F') }}
                            </span>
                                </div>
                                <h4 class="text-xl font-bold text-gray-800 mb-1">{{ $promotion->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    Услуга: <span class="font-medium text-gray-700">{{ $promotion->service->name }}</span>
                                    @if($promotion->specialist)
                                        • Мастер: <span class="font-medium text-pink-600">{{ $promotion->specialist->user->first_name }}</span>
                                    @endif
                                </p>
                            </div>

                            {{-- Процент скидки --}}
                            @if($promotion->discount_percent > 0)
                                <div class="flex-shrink-0">
                                    <div class="text-3xl font-black text-pink-500">
                                        -{{ $promotion->discount_percent }}%
                                    </div>
                                </div>
                            @endif

                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-pink-200/20 rounded-full blur-2xl"></div>
                        </div>

                    @endforeach
                </div>
            </section>
        @endif

        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold text-gray-900 uppercase tracking-widest">Прайс-лист</h3>
            <div class="w-24 h-1 bg-pink-400 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="space-y-12">
            @foreach($categories as $index => $category)
                <div id="category-{{ $category->id }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="text-2xl font-bold text-pink-500 mb-8 flex items-center gap-4">
                        <span class="w-8 h-8 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center text-sm">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        {{ $category->display_name }}
                    </h4>
                    <div class="space-y-6">
                        @forelse($category->services as $service)
                            <div class="group flex justify-between items-center border-b border-dashed border-gray-200 pb-4 hover:border-pink-300 transition-colors duration-300">
                                <div class="max-w-[60%]">
                                    <span class="text-lg font-medium text-gray-800 group-hover:text-pink-600 transition-colors">{{ $service->name }}</span>
                                    @if($service->description)
                                        <p class="text-sm text-gray-400">{{ $service->description }}</p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4">
                                    {{-- Цена --}}
                                    <span class="text-xl font-bold text-gray-900 whitespace-nowrap group-hover:translate-x-[-10px] transition-transform duration-300">
                                        {{ number_format($service->price, 0, '.', ' ') }} ₽
                                    </span>
                                    <button class="opacity-0 group-hover:opacity-100 bg-pink-500 text-white px-4 py-2 rounded-full text-xs uppercase tracking-wider font-bold transition-all duration-300 transform translate-x-4 group-hover:translate-x-0 hover:cursor-pointer">
                                        Записаться
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic">В данной категории пока нет услуг.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Блок обратной связи --}}
        <div class="mt-20 text-center bg-pink-50 rounded-3xl p-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Не нашли нужную услугу?</h3>
            <p class="text-gray-600 mb-8">Свяжитесь с мастером Алёной Хабибуллиной для консультации.</p>
            <a href="tel:+70000000000" class="inline-block bg-pink-500 text-white px-10 py-4 rounded-full font-bold hover:bg-pink-400 transition-all">
                Позвонить в салон
            </a>
        </div>
    </section>
</x-app-layout>
