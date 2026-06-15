@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование акции')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="w-full font-['Manrope'] text-[#1e1f22]"
         x-data="promotionForm()"
         x-init="init()">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider text-[#1e1f22]">Редактирование акции</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Редактирование специального предложения</p>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="text-sm  text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm p-8">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="title" class="block text-sm tracking-wider text-[#7c7e8c]">Название акции</label>
                    <input type="text"
                           name="title"
                           id="title"
                           x-model="title"
                           value="{{ old('title', $promotion->title) }}"
                           placeholder="Например: Весеннее преображение"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('title') border-red-400 @enderror">
                    @error('title')
                    <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="service_id" class="block text-sm tracking-wider text-[#7c7e8c]">Услуга студии</label>
                        <select name="service_id" id="service_id" x-model="serviceId"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <option value="">Выберите услугу...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $promotion->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm tracking-wider text-[#7c7e8c]">Мастер (необязательно)</label>
                        <select name="specialist_id" id="specialist_id" x-model="specialistId"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <option value="">Все мастера студии</option>
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}" {{ old('specialist_id', $promotion->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                    {{ $specialist->user->last_name }} {{ $specialist->user->first_name }}
                                    @if(!empty($specialist->level->name))
                                        ({{ $specialist->level->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="discount_percent" class="block text-sm tracking-wider text-[#7c7e8c]">Размер скидки (%)</label>
                        <input type="number" name="discount_percent" id="discount_percent"
                               x-model="discountPercent"
                               min="1" max="100" step="1"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm tracking-wider text-[#7c7e8c]">Базовая цена</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-sm text-slate-600">
                            <span x-text="basePriceDisplay">—</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm tracking-wider text-[#7c7e8c]">Цена со скидкой</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-pink-200 bg-pink-50 text-sm font-semibold text-pink-600">
                            <span x-text="discountedPriceDisplay">—</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="start_date" class="block text-sm tracking-wider text-[#7c7e8c]">Дата начала акции</label>
                        <input type="date" name="start_date" id="start_date"
                               x-model="startDate"
                               value="{{ old('start_date', is_string($promotion->start_date) ? $promotion->start_date : $promotion->start_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="block text-sm tracking-wider text-[#7c7e8c]">Дата окончания</label>
                        <input type="date" name="end_date" id="end_date"
                               x-model="endDate"
                               value="{{ old('end_date', is_string($promotion->end_date) ? $promotion->end_date : $promotion->end_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.promotions.index') }}"
                       class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs  font-normal rounded-xl transition-all duration-200 text-center">
                        Отмена
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs  font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function promotionForm() {
            return {
                priceData: @json($priceData),
                serviceId: '{{ old('service_id', $promotion->service_id) }}',
                specialistId: '{{ old('specialist_id', $promotion->specialist_id) }}',
                discountPercent: {{ old('discount_percent', $promotion->discount_percent) }},
                title: '{{ old('title', addslashes($promotion->title)) }}',
                startDate: '{{ old('start_date', is_string($promotion->start_date) ? $promotion->start_date : $promotion->start_date->format('Y-m-d')) }}',
                endDate: '{{ old('end_date', is_string($promotion->end_date) ? $promotion->end_date : $promotion->end_date->format('Y-m-d')) }}',

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

                init() {}
            }
        }
    </script>
@endsection
