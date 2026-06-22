@extends('admin.layouts.admin_menu')

@section('title', 'Добавление акции')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="w-full font-['Manrope'] text-[#1e1f22]"
         x-data="promotionForm()"
         x-init="init()">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal text-[#1e1f22]">Добавление акции</h1>
                <p class=" text-[#7c7e8c] font-light mt-1">Создание нового специального предложения</p>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="text-xs  text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm p-8">
            <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="title" class="block  text-sm text-[#7c7e8c]">Название акции</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           placeholder="Например: Счастливые часы: -10% на классику"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('title') border-red-400 @enderror">
                    @error('title')<p class=" text-red-500 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="service_id" class="block  text-sm text-[#7c7e8c]">Услуга студии</label>
                        <select x-model="serviceId" name="service_id" id="service_id"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('service_id') border-red-400 @enderror">
                            <option value="">Выберите услугу...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')<p class=" text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm text-[#7c7e8c]">Мастер</label>
                        <div class="relative">
                            <select name="specialist_id" id="specialist_id"
                                    x-model="specialistId"
                                    :disabled="loadingSpecialists"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all disabled:opacity-60">

                                <option value="">Все мастера (диапазон цен)</option>

                                <option value="" disabled x-show="loadingSpecialists">Загрузка мастеров...</option>
                                <option value="" disabled x-show="!loadingSpecialists && !serviceId">Сначала выберите услугу...</option>
                                <option value="" disabled x-show="!loadingSpecialists && serviceId && specialistsForService.length === 0">Нет мастеров, выполняющих эту услугу</option>

                                <template x-for="specialist in specialistsForService" :key="specialist.id">
                                    <option :value="specialist.id"
                                            x-text="specialist.user.first_name + ' ' + specialist.user.last_name + (specialist.level ? ' (' + specialist.level.name + ')' : '')">
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="discount_percent" class="block  text-sm text-[#7c7e8c]">Размер скидки (%)</label>
                        <input type="number" x-model="discountPercent" name="discount_percent" id="discount_percent"
                               min="1" max="100" step="1"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('discount_percent') border-red-400 @enderror">
                        @error('discount_percent')<p class=" text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block  text-sm text-[#7c7e8c]">Базовая цена</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-sm text-slate-600">
                            <span x-text="basePriceDisplay">—</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block  text-sm text-[#7c7e8c]">Цена со скидкой</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-pink-200 bg-pink-50 text-sm font-semibold text-pink-600">
                            <span x-text="discountedPriceDisplay">—</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="start_date" class="block  text-sm text-[#7c7e8c]">Дата начала акции</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('start_date') border-red-400 @enderror">
                        @error('start_date')<p class=" text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="block  text-sm text-[#7c7e8c]">Дата окончания</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('end_date') border-red-400 @enderror">
                        @error('end_date')<p class=" text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                <input type="hidden" name="type" value="discount">

                <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.promotions.index') }}"
                       class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs  font-normal rounded-xl transition-all duration-200 text-center">
                        Отмена
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs  font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer ">
                        Сохранить акцию
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function promotionForm() {
            return {
                priceData: @json($priceData), // Оставляем для расчета цен на лету
                serviceId: '{{ old('service_id', '') }}',
                specialistId: '{{ old('specialist_id', '') }}',
                discountPercent: {{ old('discount_percent', 0) }},

                // Новые свойства для AJAX-загрузки мастеров
                specialistsForService: [],
                loadingSpecialists: false,

                // Асинхронная функция загрузки мастеров (как в edit.blade.php)
                async loadSpecialists() {
                    if (!this.serviceId) {
                        this.specialistsForService = [];
                        this.specialistId = '';
                        return;
                    }

                    this.loadingSpecialists = true;
                    try {
                        const response = await fetch(`/api/service/${this.serviceId}/specialists`);
                        if (!response.ok) throw new Error('Ошибка сети');

                        const specialists = await response.json();
                        this.specialistsForService = specialists;

                        // Если старый выбранный мастер (например, из old()) не делает эту услугу — сбрасываем его
                        if (this.specialistId && !this.specialistsForService.some(s => s.id == this.specialistId)) {
                            this.specialistId = '';
                        }
                    } catch (error) {
                        console.error('Ошибка при загрузке мастеров:', error);
                        this.specialistsForService = [];
                    } finally {
                        this.loadingSpecialists = false;
                    }
                },

                get currentServiceData() {
                    if (!this.serviceId || !this.priceData[this.serviceId]) return null;
                    return this.priceData[this.serviceId];
                },

                get basePriceDisplay() {
                    const data = this.currentServiceData;
                    if (!data) return '— (выберите услугу)';

                    if (this.specialistId) {
                        // Проверяем цену конкретного мастера в priceData
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
                    return null;
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
                    // Если страница открылась и услуга уже выбрана (например, вернулись ошибки валидации формы)
                    if (this.serviceId) {
                        this.loadSpecialists();
                    }

                    // Следим за изменением выбранной услуги и дергаем AJAX
                    this.$watch('serviceId', () => {
                        this.loadSpecialists();
                    });
                }
            }
        }
    </script>
@endsection
