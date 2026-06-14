@extends('admin.layouts.admin_menu')

@section('title', 'Добавление акции')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="container mx-auto px-12 py-10 font-['Rubik'] bg-[#fafafc] min-h-screen"
         x-data="promotionForm()"
         x-init="init()">

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
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           placeholder="Например: Счастливые часы: -10% на классику"
                           class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] placeholder-[#c4c6d0] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="service_id" class="block text-sm text-[#7c7e8c]">Услуга студии</label>
                        <select x-model="serviceId" name="service_id" id="service_id"
                                class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('service_id') border-red-400 @enderror">
                            <option value="">Выберите услугу...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm text-[#7c7e8c]">Мастер (необязательно)</label>
                        <select x-model="specialistId" name="specialist_id" id="specialist_id"
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
                        <input type="number" x-model="discountPercent" name="discount_percent" id="discount_percent"
                               min="1" max="100" step="1"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#ff5c8a] @error('discount_percent') border-red-400 @enderror">
                        @error('discount_percent')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm text-[#7c7e8c]">Базовая цена</label>
                        <div class="text-sm text-[#1e1f22] bg-[#f5f5f7] rounded-xl px-4 py-3.5 border border-[#e2e2e9]">
                            <span x-text="basePriceDisplay">—</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm text-[#7c7e8c]">Цена со скидкой</label>
                        <div class="text-sm font-semibold text-[#ff5c8a] bg-[#fff0f2] rounded-xl px-4 py-3.5 border border-[#ffe1e5]">
                            <span x-text="discountedPriceDisplay">—</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="start_date" class="block text-sm text-[#7c7e8c]">Дата начала акции</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#ff5c8a] @error('start_date') border-red-400 @enderror">
                        @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="block text-sm text-[#7c7e8c]">Дата окончания</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#ff5c8a] @error('end_date') border-red-400 @enderror">
                        @error('end_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <input type="hidden" name="type" value="discount">

                <div class="pt-6 flex justify-between items-center border-t border-[#f1f1f5]">
                    <a href="{{ route('admin.promotions.index') }}" class="text-xs text-[#1e1f22] tracking-wide uppercase hover:text-[#ff5c8a] transition-colors">Отмена</a>
                    <button type="submit" class="bg-[#b30047] hover:bg-[#8a0036] text-white text-sm py-3.5 px-8 rounded-2xl transition-colors duration-200 shadow-sm">Сохранить акцию</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function promotionForm() {
            return {
                priceData: @json($priceData),
                serviceId: '{{ old('service_id') }}',
                specialistId: '{{ old('specialist_id') }}',
                discountPercent: {{ old('discount_percent', 0) }},

                get currentServiceData() {
                    if (!this.serviceId || !this.priceData[this.serviceId]) return null;
                    return this.priceData[this.serviceId];
                },

                get basePriceDisplay() {
                    const data = this.currentServiceData;
                    if (!data) return '— (выберите услугу)';

                    if (this.specialistId) {
                        const specialistPrice = data.specialists[this.specialistId];
                        if (!specialistPrice) return 'Цена не задана для этого мастера';
                        return specialistPrice.price.toLocaleString('ru-RU') + ' ₽';
                    } else {
                        return data.all.price_range;
                    }
                },

                get basePriceValue() {
                    const data = this.currentServiceData;
                    if (!data) return null;
                    if (this.specialistId) {
                        const specialistPrice = data.specialists[this.specialistId];
                        return specialistPrice ? specialistPrice.price : null;
                    }
                    return null; // для диапазона не считаем скидку
                },

                get discountedPriceDisplay() {
                    const base = this.basePriceValue;
                    if (base === null) {
                        if (!this.currentServiceData) return '—';
                        if (!this.specialistId) return 'Для акции на всех мастеров цена зависит от уровня мастеров.';
                        return 'Цена не определена';
                    }
                    const discount = parseFloat(this.discountPercent);
                    if (isNaN(discount) || discount < 0 || discount > 100) return 'Некорректная скидка';
                    const discounted = base * (1 - discount / 100);
                    return discounted.toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ₽';
                },

                init() {
                    // Alpine автоматически отслеживает изменения, ничего дополнительно не требуется
                }
            }
        }
    </script>
@endsection@extends('admin.layouts.admin_menu')

@section('title', 'Добавление акции')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="container mx-auto px-12 py-10 font-['Rubik'] bg-[#fafafc] min-h-screen"
         x-data="promotionForm()"
         x-init="init()">

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
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           placeholder="Например: Счастливые часы: -10% на классику"
                           class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] placeholder-[#c4c6d0] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="service_id" class="block text-sm text-[#7c7e8c]">Услуга студии</label>
                        <select x-model="serviceId" name="service_id" id="service_id"
                                class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('service_id') border-red-400 @enderror">
                            <option value="">Выберите услугу...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm text-[#7c7e8c]">Мастер (необязательно)</label>
                        <select x-model="specialistId" name="specialist_id" id="specialist_id"
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
                        <input type="number" x-model="discountPercent" name="discount_percent" id="discount_percent"
                               min="1" max="100" step="1"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#ff5c8a] @error('discount_percent') border-red-400 @enderror">
                        @error('discount_percent')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm text-[#7c7e8c]">Базовая цена</label>
                        <div class="text-sm text-[#1e1f22] bg-[#f5f5f7] rounded-xl px-4 py-3.5 border border-[#e2e2e9]">
                            <span x-text="basePriceDisplay">—</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm text-[#7c7e8c]">Цена со скидкой</label>
                        <div class="text-sm font-semibold text-[#ff5c8a] bg-[#fff0f2] rounded-xl px-4 py-3.5 border border-[#ffe1e5]">
                            <span x-text="discountedPriceDisplay">—</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="start_date" class="block text-sm text-[#7c7e8c]">Дата начала акции</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#ff5c8a] @error('start_date') border-red-400 @enderror">
                        @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="block text-sm text-[#7c7e8c]">Дата окончания</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#ff5c8a] @error('end_date') border-red-400 @enderror">
                        @error('end_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <input type="hidden" name="type" value="discount">

                <div class="pt-6 flex justify-between items-center border-t border-[#f1f1f5]">
                    <a href="{{ route('admin.promotions.index') }}" class="text-xs text-[#1e1f22] tracking-wide uppercase hover:text-[#ff5c8a] transition-colors">Отмена</a>
                    <button type="submit" class="bg-[#b30047] hover:bg-[#8a0036] text-white text-sm py-3.5 px-8 rounded-2xl transition-colors duration-200 shadow-sm">Сохранить акцию</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function promotionForm() {
            return {
                priceData: @json($priceData),
                serviceId: '{{ old('service_id') }}',
                specialistId: '{{ old('specialist_id') }}',
                discountPercent: {{ old('discount_percent', 0) }},

                get currentServiceData() {
                    if (!this.serviceId || !this.priceData[this.serviceId]) return null;
                    return this.priceData[this.serviceId];
                },

                get basePriceDisplay() {
                    const data = this.currentServiceData;
                    if (!data) return '— (выберите услугу)';

                    if (this.specialistId) {
                        const specialistPrice = data.specialists[this.specialistId];
                        if (!specialistPrice) return 'Цена не задана для этого мастера';
                        return specialistPrice.price.toLocaleString('ru-RU') + ' ₽';
                    } else {
                        return data.all.price_range;
                    }
                },

                get basePriceValue() {
                    const data = this.currentServiceData;
                    if (!data) return null;
                    if (this.specialistId) {
                        const specialistPrice = data.specialists[this.specialistId];
                        return specialistPrice ? specialistPrice.price : null;
                    }
                    return null; // для диапазона не считаем скидку
                },

                get discountedPriceDisplay() {
                    const base = this.basePriceValue;
                    if (base === null) {
                        if (!this.currentServiceData) return '—';
                        if (!this.specialistId) return 'Для акции на всех мастеров цена зависит от уровня мастеров.';
                        return 'Цена не определена';
                    }
                    const discount = parseFloat(this.discountPercent);
                    if (isNaN(discount) || discount < 0 || discount > 100) return 'Некорректная скидка';
                    const discounted = base * (1 - discount / 100);
                    return discounted.toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ₽';
                },

                init() {
                    // Alpine автоматически отслеживает изменения, ничего дополнительно не требуется
                }
            }
        }
    </script>
@endsection
