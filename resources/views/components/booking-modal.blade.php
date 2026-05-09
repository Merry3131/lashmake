@props(['categories', 'promotions', 'specialists'])

<div x-data="{ step: 'main' }"
     x-show="$store.modalManager.bookingOpen"
     x-cloak
     @keydown.escape.window="$store.modalManager.closeBooking()"
     class="relative z-50">
    <div x-show="$store.modalManager.bookingOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="$store.modalManager.closeBooking()"
             class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl relative">
            <button @click="$store.modalManager.closeBooking()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-pink-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h1 class="text-2xl font-bold text-pink-500 mb-6 text-center">Запись на услугу</h1>

            {{-- Шаг 1: Главная --}}
            <div x-show="step === 'main'" class="space-y-4">
                <button @click="step = 'services'" class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl font-semibold hover:bg-pink-200 transition-colors">Выбрать услугу</button>
                <button @click="step = 'specialists'" class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl font-semibold hover:bg-pink-200 transition-colors">Выбрать мастера</button>
            </div>

            {{-- Шаг 2: Услуги --}}
            <div x-show="step === 'services'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите услугу</h1>
                </div>

                <div class="max-h-96 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                    @foreach($promotions as $promotion)
                        <button class="w-full text-left p-3 rounded-xl border-2 border-pink-200 bg-pink-50 hover:bg-pink-100 transition-all">
                            <div class="font-bold text-gray-800">{{ $promotion->title }}</div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $promotion->service->name ?? '' }}</span>
                                <span class="bg-pink-500 text-white px-2 py-0.5 rounded-full">-{{ $promotion->discount_percent }}%</span>
                            </div>
                        </button>
                    @endforeach

                    @foreach($categories as $category)
                        @if($category->services->isNotEmpty())
                            <h2 class="text-sm font-bold uppercase text-gray-400 mt-4">{{ $category->display_name }}</h2>
                            @foreach($category->services as $service)
                                <button class="w-full text-left p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50">
                                    <div class="font-medium text-gray-700">{{ $service->name }}</div>
                                    <div class="text-xs text-pink-500">{{ number_format($service->base_price, 0, '.', ' ') }} ₽</div>
                                </button>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Шаг 3: Специалисты --}}
            <div x-show="step === 'specialists'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите специалиста</h1>
                </div>

                <div class="max-h-96 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                    @forelse($team as $specialist)
                        <button class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">
                            <div class="w-12 h-12 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 font-bold">
                                {{ mb_substr($specialist->user->first_name ?? 'M', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $specialist->user->first_name}} {{ $specialist->user->last_name }}</div>

                                <div class="text-xs text-gray-500 italic">{{ $specialist->level_name}}</div>
                            </div>
                        </button>
                    @empty
                        <p class="text-center text-gray-400 py-10">Нет свободных мастеров</p>
                    @endforelse
                </div>
            </div>



        </div>
    </div>
</div>
