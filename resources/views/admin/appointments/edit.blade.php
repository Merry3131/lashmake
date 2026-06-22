@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование записи')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div x-data="{
        selectedService: '{{ old('service_id', $appointment->service_id) }}',
        selectedSpecialist: '{{ old('specialist_id', $appointment->specialist_id) }}',
        initialSpecialistId: '{{ old('specialist_id', $appointment->specialist_id) }}', {{-- Сохраняем эталонный ID --}}
        selectedDate: '{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_at)->format('Y-m-d')) }}',
        selectedTime: '{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_at)->format('H:i')) }}',
        availableSlots: [],
        loading: false,
        initialLoad: true,
        specialistsForService: [],
        loadingSpecialists: false,

        async loadSpecialists() {
            if (!this.selectedService) {
                this.specialistsForService = [];
                this.selectedSpecialist = '';
                return;
            }

            this.loadingSpecialists = true;
            try {
                const response = await fetch(`/api/service/${this.selectedService}/specialists`);
                const specialists = await response.json();
                this.specialistsForService = specialists;

                {{-- Принудительно восстанавливаем мастера на первичной загрузке --}}
                if (this.initialLoad && this.initialSpecialistId) {
                    this.selectedSpecialist = this.initialSpecialistId;
                }

                // Если это не первая загрузка и мастер сменился на неподходящего — сбрасываем
                if (!this.initialLoad) {
                    if (this.selectedSpecialist && !this.specialistsForService.some(s => s.id == this.selectedSpecialist)) {
                        this.selectedSpecialist = '';
                    }
                }
            } catch (error) {
                console.error('Ошибка загрузки специалистов:', error);
                this.specialistsForService = [];
            } finally {
                this.loadingSpecialists = false;
            }
        },

        async loadSlots() {
            if (!this.selectedSpecialist || !this.selectedDate || !this.selectedService) {
                this.availableSlots = [];
                return;
            }

            this.loading = true;
            try {
                const response = await fetch(
                    `/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}&service_id=${this.selectedService}&ignore_appointment_id={{ $appointment->id }}`
                );
                let slots = await response.json();
                this.availableSlots = slots;

                if (this.initialLoad && this.selectedTime && !this.availableSlots.includes(this.selectedTime)) {
                    this.availableSlots.push(this.selectedTime);
                    this.availableSlots.sort();
                }

                if (!this.initialLoad && this.selectedTime && !this.availableSlots.includes(this.selectedTime)) {
                    this.selectedTime = null;
                }
            } catch (error) {
                console.error('Ошибка загрузки слотов:', error);
            } finally {
                this.loading = false;
            }
        }
    }"
         x-init="
            // Сначала загружаем только специалистов, затем слоты.
            loadSpecialists().then(() => {
                return loadSlots();
            }).then(() => {
                initialLoad = false;
            });

            {{-- Оптимизировано: ждем загрузку мастеров, прежде чем обновлять слоты при смене услуги --}}
            $watch('selectedService', async () => { await loadSpecialists(); loadSlots(); });
            $watch('selectedSpecialist', () => loadSlots());
            $watch('selectedDate', () => loadSlots());
         ">

        <main class="p-8 w-full">
            @if ($errors->any())
                <div class="w-full mb-5 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm shadow-sm">
                    <strong class="block mb-1">Ошибка изменения записи:</strong>
                    <ul class="list-disc pl-5 space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-semibold text-slate-800">Редактирование записи №{{ $appointment->id }}</h1>
                    <a href="{{ route('admin.appointments.index') }}" class="text-xs text-slate-400 hover:text-slate-600 transition-colors">
                        ← Назад к списку
                    </a>
                </div>

                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-100 space-y-1">
                        <span class="block text-sm tracking-wider text-slate-400">Клиент</span>
                        <div class="text-sm text-slate-800">
                            {{ $appointment->user->last_name }} {{ $appointment->user->name }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ $appointment->user->phone ?? 'Телефон не указан' }}
                        </div>
                    </div>

                    <div>
                        <label for="service_id" class="block text-xs tracking-wider text-slate-500 mb-2">Услуга</label>
                        <select id="service_id"
                                name="service_id"
                                x-model="selectedService"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <option value="">Выберите услугу</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm tracking-wider text-[#7c7e8c]">Мастер</label>
                        <div class="relative">
                            <select name="specialist_id" id="specialist_id"
                                    x-model="selectedSpecialist"
                                    :disabled="loadingSpecialists"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all disabled:opacity-60">

                                <option value="">Все мастера студии (диапазон цен)</option>

                                {{-- ВРЕМЕННАЯ ОПЦИЯ: не дает браузеру сбросить значение до окончания загрузки через API --}}
                                @if($appointment->specialist)
                                    <option value="{{ $appointment->specialist_id }}" x-show="initialLoad">
                                        {{ $appointment->specialist->user->first_name }} {{ $appointment->specialist->user->last_name }}
                                    </option>
                                @endif

                                <option value="" disabled x-show="loadingSpecialists">Загрузка мастеров...</option>
                                <option value="" disabled x-show="!loadingSpecialists && !selectedService">Сначала выберите услугу...</option>
                                <option value="" disabled x-show="!loadingSpecialists && selectedService && specialistsForService.length === 0">Нет мастеров, выполняющих эту услугу</option>

                                <template x-for="specialist in specialistsForService" :key="specialist.id">
                                    <option :value="specialist.id"
                                            x-text="specialist.user.first_name + ' ' + specialist.user.last_name + (specialist.level ? ' (' + specialist.level.name + ')' : '')">
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="appointment_date" class="block text-xs tracking-wider text-slate-500 mb-2">Дата записи</label>
                        <input type="date"
                               id="appointment_date"
                               name="appointment_date"
                               x-model="selectedDate"
                               required
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                        @error('appointment_date')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs tracking-wider text-slate-500 mb-3">
                            Время записи
                            <span x-show="loading" class="text-pink-500 text-xs ml-2 animate-pulse">(Обновление слотов...)</span>
                        </label>

                        <input type="hidden" name="appointment_time" x-model="selectedTime" required>

                        <template x-if="availableSlots.length === 0 && !loading && selectedSpecialist && selectedDate && selectedService">
                            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800 text-xs">
                                У мастера нет окон на эту дату для выбранной услуги.
                            </div>
                        </template>

                        <template x-if="!selectedSpecialist || !selectedDate || !selectedService">
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl text-slate-500 text-xs">
                                Выберите услугу, мастера и дату, чтобы увидеть доступное время.
                            </div>
                        </template>

                        <div class="grid grid-cols-6 gap-2">
                            <template x-for="slot in availableSlots" :key="slot">
                                <button type="button"
                                        @click="selectedTime = slot"
                                        :class="selectedTime === slot
                                            ? 'bg-pink-500 text-white border-pink-500 shadow-md shadow-pink-100'
                                            : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border-slate-200'"
                                        class="py-2 text-center text-xs rounded-lg border transition-all duration-150 cursor-pointer">
                                    <span x-text="slot"></span>
                                </button>
                            </template>
                        </div>

                        @error('appointment_time')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs tracking-wider text-slate-500 mb-2">Статус записи</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Ожидает подтверждения</option>
                            <option value="approved" {{ old('status', $appointment->status) == 'approved' ? 'selected' : '' }}>Подтверждена</option>
                            <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Выполнена</option>
                            <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.appointments.index') }}"
                           class="px-6 py-2 text-center text-slate-600 rounded-lg transition-all tracking-wide text-xs bg-slate-100 hover:bg-slate-200 hover:text-slate-800">
                            Отмена
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md tracking-wide text-xs">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
