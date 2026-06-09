@extends('admin.layouts.admin_menu')

@section('title', 'Добавление акции')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <div class="container mx-auto px-12 py-10 font['Rubik'] bg-[#fafafc] min-h-screen">

        <div class="flex justify-between items-center mb-4">
            <div class="text-[#9ca0b0] text-xs space-x-1 tracking-wide">
                <span>главная</span>
                <span>/</span>
                <a href="{{ route('admin.promotions.index') }}" class="hover:underline">акции студии</a>
                <span>/</span>
                <span class="text-[#ff5c8a]">новая акция</span>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="text-[#9ca0b0] hover:text-[#1e1f22] text-xs tracking-wide transition-colors">
                ← Назад к списку
            </a>
        </div>

        <h1 class="text-[#1e1f22] text-4xl tracking-tight mb-8">Новая акция</h1>

        <div class="bg-white border border-[#f1f1f5] rounded-3xl p-10 shadow-sm max-w-3xl">
            <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="title" class="block text-sm text-[#7c7e8c]">Название акции (отображаемое на сайте)</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           placeholder="Например: Счастливые часы: -10% на классику"
                           class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] placeholder-[#c4c6d0] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('title') border-red-400 @enderror">
                    @error('title')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="service_id" class="block text-sm text-[#7c7e8c]">Услуга студии</label>
                        <select name="service_id"
                                id="service_id"
                                class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('service_id') border-red-400 @enderror">
                            <option value="" disabled selected>Выберите услугу...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm text-[#7c7e8c]">Мастер (необязательно)</label>
                        <select name="specialist_id"
                                id="specialist_id"
                                class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                            <option value="">Все мастера студии (на всю услугу)</option>
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}" {{ old('specialist_id') == $specialist->id ? 'selected' : '' }}>
                                    {{ $specialist->user->first_name }} {{ $specialist->user->last_name }} ({{ $specialist->level->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="discount_percent" class="block text-sm text-[#7c7e8c]">Размер скидки (%)</label>
                        <input type="number"
                               name="discount_percent"
                               id="discount_percent"
                               min="1"
                               max="100"
                               value="{{ old('discount_percent', 0) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('discount_percent') border-red-400 @enderror">
                        @error('discount_percent')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="start_date" class="block text-sm text-[#7c7e8c]">Дата начала акции</label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               value="{{ old('start_date', date('Y-m-d')) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('start_date') border-red-400 @enderror">
                        @error('start_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="end_date" class="block text-sm text-[#7c7e8c]">Дата окончания</label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               value="{{ old('end_date') }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('end_date') border-red-400 @enderror">
                        @error('end_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="type" value="discount">

                <div class="pt-6 flex justify-between items-center border-t border-[#f1f1f5]">
                    <a href="{{ route('admin.promotions.index') }}"
                       class="text-xs text-[#1e1f22] tracking-wide uppercase hover:text-[#ff5c8a] transition-colors">
                        Отмена
                    </a>

                    <button type="submit"
                            class="bg-[#b30047] hover:bg-[#8a0036] text-white text-sm py-3.5 px-8 rounded-2xl transition-colors duration-200 shadow-sm">
                        Сохранить акцию
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
