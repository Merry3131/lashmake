<x-app-layout title="Центр Ресниц | Оставить отзыв">

    {{-- Фирменные шрифты из твоего проекта --}}
    <style>
        h1, h2, h3, h4, h5, h6, .font-serif, [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }
        body, p, span, button, a, li, div, input, textarea, select {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    {{-- Шапка страницы --}}
    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] tracking-widest uppercase mb-4 font-serif">
                ваш отзыв
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide">
                помогите нам стать еще лучше
            </p>
        </div>
    </div>

    {{-- Основной контейнер формы --}}
    <section class="max-w-2xl mx-auto pb-24 px-6 mt-8">
        <div class="bg-white p-8 lg:p-10 rounded-3xl border border-[#f1f1f5] shadow-sm">

            {{-- Инфо о мастере, которому оставляют отзыв --}}
            <div class="flex items-center gap-4 bg-[#fff0f3] p-4 rounded-2xl mb-8 border border-pink-100">
                <div class="w-12 h-12 bg-pink-200 text-[#ff5c8a] rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] text-pink-500 font-normal uppercase tracking-widest block">вы делитесь впечатлениями о мастере:</span>
                    <h4 class="text-lg font-normal text-[#1e1f22] font-serif">
                        {{ mb_strtolower($appointment->specialist->user->first_name) }} {{ mb_strtolower($appointment->specialist->user->last_name) }}
                    </h4>
                </div>
            </div>

            {{-- Форма --}}
            <form action="{{ route('reviews.store') }}" method="POST" x-data="{ rating: 5 }" class="space-y-6">
                @csrf
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                <input type="hidden" name="rating" :value="rating">

                {{-- Блок выбора рейтинга --}}
                <div class="space-y-2 text-center sm:text-left">
                    <label class="text-xs text-[#7c7e8c] font-light uppercase tracking-wider block">ваша оценка</label>
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <template x-for="i in 5">
                            <button type="button" @click="rating = i" class="focus:outline-none transition-transform active:scale-90">
                                <svg class="w-8 h-8 transition-colors duration-200"
                                     :class="i <= rating ? 'text-yellow-400 fill-current' : 'text-gray-200'"
                                     viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Текстовое поле --}}
                <div class="space-y-2">
                    <label for="comment" class="text-xs text-[#7c7e8c] font-light uppercase tracking-wider block">комментарий</label>
                    <textarea id="comment" name="comment" rows="5" required
                              placeholder="расскажите, как прошел ваш визит, понравился ли вам результат..."
                              class="w-full text-sm p-4 bg-[#f8f8fa] border border-[#f1f1f5] rounded-2xl focus:outline-none focus:border-[#ff5c8a] focus:ring-1 focus:ring-[#ff5c8a] transition-all resize-none leading-relaxed"></textarea>
                </div>

                {{-- Кнопки управления --}}
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-dashed border-gray-100">
                    <a href="{{ route('dashboard') }}" class="text-xs text-[#7c7e8c] hover:text-[#1e1f22] transition-colors border-b border-gray-300 hover:border-gray-600 pb-0.5">
                        вернуться назад
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto bg-[#ff5c8a] text-white px-8 py-3.5 rounded-full text-xs tracking-widest font-normal uppercase hover:bg-[#e04b75] transition-colors duration-300 shadow-md shadow-pink-100 hover:cursor-pointer text-center">
                        отправить отзыв
                    </button>
                </div>
            </form>

        </div>
    </section>

</x-app-layout>
