<x-app-layout title="Центр Ресниц | {{ $service->name }}">

    <style>
        h1, h2, h3, h4, h5, h6,
        .font-serif,
        [class*="font-serif"] {
            font-family: 'Playfair Display', serif !important;
        }

        body, p, span, button, a, li, div,
        .text-gray-600, .text-gray-500, .text-gray-400,
        .st, .,
        input, textarea, select {
            font-family: 'Manrope', sans-serif;
        }

        button, a, .btn {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center relative">
            <h1 class="text-3xl lg:text-4xl font-normal text-[#1e1f22] st mb-4 font-serif">
                {{ $service->name }}
            </h1>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
        </div>
    </div>

    <section class="max-w-7xl mx-auto pb-24 px-6 mt-8">
        <div class="mb-6">
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center gap-2 text-pink-500 hover:text-pink-600 transition-colors duration-300 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Назад к услугам
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <div class="lg:col-span-5">
                <div class="p-4 rounded-3xl shadow-sm">
                    <div class="aspect-[4/3] bg-[#f8f8fa] relative overflow-hidden rounded-2xl">
                        @if($service->category && $service->category->hasMedia('categories'))
                            <img src="{{ $service->category->getFirstMediaUrl('categories') }}"
                                 alt="{{ $service->name }}"
                                 class="w-full h-full object-cover opacity-90">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-pink-50 text-[#ff5c8a]">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-8">
                <div class="bg-white p-8 lg:p-10 rounded-3xl border border-[#f1f1f5] space-y-6">
                    <div>
                        <h3 class="text-xl text-[#1e1f22] font-normal font-serif mb-3">
                            Описание услуги
                        </h3>
                        <p class="text-sm text-[#7c7e8c] font-light leading-relaxed">
                            {{ $service->description ? $service->description : 'описание для данной услуги временно отсутствует. вы можете уточнить детали у нашего администратора при записи.' }}
                        </p>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-4">

                        <div class="flex items-start gap-4">
                            <div class="flex flex-col gap-1 w-full" x-data="{
                                formatDuration(totalMinutes) {
                                    const hours = Math.floor(totalMinutes / 60);
                                    const minutes = totalMinutes % 60;
                                    let result = [];
                                    if (hours > 0) {
                                        let hoursWord = 'часов';
                                        if (hours % 10 === 1 && hours % 100 !== 11) hoursWord = 'час';
                                        else if ([2, 3, 4].includes(hours % 10) && ![12, 13, 14].includes(hours % 100)) hoursWord = 'часа';
                                        result.push(`${hours} ${hoursWord}`);
                                    }
                                    if (minutes > 0 || hours === 0) {
                                        let minutesWord = 'минут';
                                        if (minutes % 10 === 1 && minutes % 100 !== 11) minutesWord = 'минута';
                                        else if ([2, 3, 4].includes(minutes % 10) && ![12, 13, 14].includes(minutes % 100)) minutesWord = 'минуты';
                                        result.push(`${minutes} ${minutesWord}`);
                                    }
                                    return result.join(' ');
                                }
                            }">
                                <p class="text-sm tracking-wide text-[#9ca0b0] uppercase mb-1">Длительность</p>
                                @forelse($service->levels as $level)
                                    <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-100 pb-2 last:border-0 last:pb-0">
                                        <span class="text-pink-500 font-light">{{ $level->display_name }}</span>
                                        <span class="font-semibold text-[#1e1f22] whitespace-nowrap pl-4" x-text="formatDuration({{ $level->pivot->duration }})">
                                            {{ $level->pivot->duration}} мин.
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-[#1e1f22]">Уточняйте</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex flex-col gap-1 w-full">
                                <p class="text-sm tracking-wide text-[#9ca0b0] uppercase mb-1">Стоимость</p>
                                @forelse($service->levels as $level)
                                    <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-100 pb-2 last:border-0 last:pb-0">
                                        <span class="text-pink-500 font-light">{{ $level->display_name }}</span>
                                        <span class="font-semibold text-[#1e1f22] whitespace-nowrap pl-4">
                                            {{ number_format($level->pivot->price, 0, '.', ' ') }} ₽
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-[#1e1f22]">Уточняйте</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    <h3 class="text-lg text-[#1e1f22] font-normal font-serif pl-2">
                        Мастера
                    </h3>
                    <div class="pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($service->specialists ?? [] as $specialist)
                                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-[#f1f1f5]">
                                    <div>
                                        <h4 class="text-sm text-gray-800">
                                            {{ $specialist->user->last_name }} {{ $specialist->user->first_name }}
                                        </h4>
                                        <p class="text-sm text-[#9ca0b0]">
                                            {{ $specialist->level->display_name ?? 'Мастер'}}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 pl-2">Услугу оказывают все топ-мастера салона.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                </div>

            </div>
        </div>
    </section>

</x-app-layout>
