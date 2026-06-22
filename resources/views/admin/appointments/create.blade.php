@extends('admin.layouts.admin_menu')

@section('title', 'Создание новой записи')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="flex-1 flex flex-col overflow-y-auto"
         x-data="{
            userMode: '{{ old('user_mode', 'new') }}',
            selectedService: '{{ old('service_id') }}',
            selectedSpecialist: '{{ old('specialist_id') }}',
            selectedDate: '{{ old('appointment_date', date('Y-m-d')) }}',
            selectedTime: '{{ old('appointment_time') }}',
            availableSlots: [],
            loading: false,
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

                    // Если текущий выбранный специалист не входит в новый список, сбросить
                    if (this.selectedSpecialist && !this.specialistsForService.some(s => s.id == this.selectedSpecialist)) {
                        this.selectedSpecialist = '';
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
                    let response = await fetch(`/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}&service_id=${this.selectedService}`);
                    let slots = await response.json();
                    this.availableSlots = slots;

                    if (this.selectedTime && !this.availableSlots.includes(this.selectedTime)) {
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
            loadSpecialists();
            loadSlots();
            $watch('selectedService', () => { loadSpecialists(); loadSlots(); });
            $watch('selectedSpecialist', () => loadSlots());
            $watch('selectedDate', () => loadSlots());
         ">

        <main class="p-8 w-full">
            <div class="w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h1 class="text-xl text-slate-800 mb-6">Создание новой записи</h1>

                <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <input type="hidden" name="user_mode" x-model="userMode">
                    <div class="flex bg-slate-100 p-1 rounded-xl gap-1 mb-4">
                        <button type="button"
                                @click="userMode = 'new'"
                                :class="userMode === 'new' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="w-full py-2 text-xs rounded-lg transition-all tracking-wider">
                            Новый клиент
                        </button>
                        <button type="button"
                                @click="userMode = 'existing'"
                                :class="userMode === 'existing' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="w-full py-2 text-xs rounded-lg transition-all tracking-wider">
                            Выбрать из базы
                        </button>
                    </div>

                    <div x-show="userMode === 'new'" x-transition class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs tracking-wider text-slate-500 mb-2">Имя клиента</label>
                                <input type="text" id="name" name="name" value="{{ old('first_name') }}" ::required="userMode === 'new'"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                                @error('first_name')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-xs tracking-wider text-slate-500 mb-2">Фамилия</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                                @error('last_name')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div x-data="{ phoneValue: '{{ old('phone', '+79') }}' }">
                            <label for="phone" class="block text-xs tracking-wider text-slate-500 mb-2">Номер телефона</label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   x-model="phoneValue"
                                   x-on:input="phoneValue = phoneValue.replace(/[^\d]/g, ''); if (!phoneValue.startsWith('79')) phoneValue = '79' + phoneValue.slice(2); phoneValue = '+79' + phoneValue.slice(2, 11);"
                                   ::required="userMode === 'new'"
                                   maxlength="12"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <p class="text-slate-400 text-xs mt-1.5">Формат: +79XXXXXXXXX (11 цифр)</p>
                            @error('phone')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div x-show="userMode === 'existing'" x-transition>
                        <div>
                            <label for="user_id" class="block text-xs tracking-wider text-slate-500 mb-2">Выберите клиента</label>
                            <select id="user_id" name="user_id" ::required="userMode === 'existing'"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                                <option value="">-- Выберите пользователя из списка --</option>
                                @foreach(\App\Models\User::orderBy('last_name')->get() as $client)
                                    <option value="{{ $client->id }}" {{ old('user_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->last_name }} {{ $client->first_name }} ({{ $client->phone ?? $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-slate-100 my-2">

                    <!-- Услуга (выбирается первой) -->
                    <div>
                        <label for="service_id" class="block text-xs tracking-wider text-slate-500 mb-2">Услуга</label>
                        <select id="service_id"
                                name="service_id"
                                x-model="selectedService"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <option value="">Выберите услугу</option>
                            @foreach(\App\Models\Service::all() as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Мастер (зависит от выбранной услуги) -->
                    <div>
                        <label for="specialist_id" class="block text-xs tracking-wider text-slate-500 mb-2">Мастер</label>
                        <select id="specialist_id"
                                name="specialist_id"
                                x-model="selectedSpecialist"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            <option value="">Выберите мастера</option>
                            <template x-if="loadingSpecialists">
                                <option disabled>Загрузка мастеров...</option>
                            </template>
                            <template x-if="!loadingSpecialists && specialistsForService.length === 0 && selectedService">
                                <option disabled>Нет мастеров для этой услуги</option>
                            </template>
                            <template x-for="specialist in specialistsForService" :key="specialist.id">
                                <option :value="specialist.id" x-text="specialist.user.last_name + ' ' + specialist.user.first_name + ' (' + (specialist.level?.display_name || 'Мастер') + ')'"></option>
                            </template>
                        </select>
                        @error('specialist_id')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_date" class="block text-xs tracking-wider text-slate-500 mb-2">Выбрать дату</label>
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
                            Доступное время
                            <span x-show="loading" class="text-pink-500 text-xs ml-2 font-normal animate-pulse">(Загрузка слотов...)</span>
                        </label>

                        <input type="hidden" name="appointment_time" x-model="selectedTime" required>

                        <template x-if="availableSlots.length === 0 && !loading && selectedSpecialist && selectedDate && selectedService">
                            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800 text-xs font-medium">
                                У мастера нет свободных окон на указанную дату для этой услуги.
                            </div>
                        </template>

                        <template x-if="!selectedSpecialist || !selectedDate || !selectedService">
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl text-slate-500 text-xs font-medium">
                                Выберите услугу, мастера и дату, чтобы увидеть доступное время.
                            </div>
                        </template>

                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="slot in availableSlots" :key="slot">
                                <button type="button"
                                        @click="selectedTime = slot"
                                        :class="selectedTime === slot
                                            ? 'bg-pink-500 text-white border-pink-500 shadow-md shadow-pink-100 scale-[1.02]'
                                            : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border-slate-200'"
                                        class="py-2.5 text-center text-sm font-semibold rounded-xl border transition-all duration-150 cursor-pointer">
                                    <span x-text="slot"></span>
                                </button>
                            </template>
                        </div>

                        @error('appointment_time')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center pt-6">
                        <a href="{{ route('admin.appointments.index') }}"
                           class="px-6 py-2 text-center text-slate-600 font-semibold rounded-lg transition-all tracking-wide text-xs bg-slate-100 hover:bg-slate-200 hover:text-slate-800">
                            Отмена
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md tracking-wide text-xs">
                            Создать запись
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
