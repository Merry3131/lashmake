<x-app-layout title="Центр Ресниц | Акции">
    <section class="max-w-7xl mx-auto pt-20 pb-12 px-6">
        <div class="mb-16">
            <h2 class="text-4xl lg:text-5xl font-serif text-center relative flex items-center justify-center gap-6 text-gray-900 mx-auto max-w-2xl">
                <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
                <span class="relative z-10 px-2 uppercase tracking-widest">Акции</span>
                <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
            </h2>
            <p class="text-center text-gray-500 italic mt-4">Искусство преображения взгляда</p>
        </div>

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
                            {{ $promotion->type_label }} {{-- Используем ваш аксессор для русского языка --}}
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

                            {{-- Декоративный круг на фоне --}}
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-pink-200/20 rounded-full blur-2xl"></div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </section>
</x-app-layout>
