@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование записи')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="flex-1 flex flex-col overflow-y-auto"
         x-data="{
            selectedSpecialist: '{{ old('specialist_id', $appointment->specialist_id) }}',
            selectedService: '{{ old('service_id', $appointment->service_id) }}',
            selectedDate: '{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_at)->format('Y-m-d')) }}',
            selectedTime: '{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_at)->format('H:i')) }}',
            availableSlots: [],
            loading: false,
            initialLoad: true, {{-- Чтобы не сбрасывать текущее время записи при первой загрузке слотов --}}

            async loadSlots() {
                if (!this.selectedSpecialist || !this.selectedDate || !this.selectedService) {
                    this.availableSlots = [];
                    return;
                }

                this.loading = true;

                try {
                    // Передаем service_id, чтобы учитывалась длительность, и ID текущей записи, чтобы её же время не заблокировалось
                    let response = await fetch(`/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}&service_id=${this.selectedService}&ignore_appointment_id={{ $appointment->id }}`);
                    let slots = await response.json();

                    this.availableSlots = slots;

                    // Если это первая загрузка и текущего времени записи нет в свободных слотах, принудительно добавим его в массив слотов, чтобы плитка отобразилась активной
                    if (this.initialLoad && this.selectedTime && !this.availableSlots.includes(this.selectedTime)) {
                        this.availableSlots.push(this.selectedTime);
                        this.availableSlots.sort();
                    }

                    // Если админ вручную меняет параметры (уже после первой загрузки) и старое время не подходит — сбрасываем его
                    if (!this.initialLoad && this.selectedTime && !this.availableSlots.includes(this.selectedTime)) {
                        this.selectedTime = null;
                    }
                } catch (error) {
                    console.error('Ошибка загрузки слотов:', error);
                } finally {
                    this.loading = false;
                    this.initialLoad = false;
                }
            }
         }"
         x-init="
            loadSlots();
            $watch('selectedSpecialist', () => loadSlots());
            $watch('selectedDate', () => loadSlots());
            $watch('selectedService', () => loadSlots());
         ">

        <main class="p-8">
            {{-- Вывод ошибок валидации, если бэкенд вернет редирект --}}
            @if ($errors->any())
                <div class="max-w-xl mb-5 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm shadow-sm">
                    <strong class="block mb-1">⚠️ Ошибка изменения записи:</strong>
                    <ul class="list-disc pl-5 space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="max-w-xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-bold text-slate-800">Редактирование записи №{{ $appointment->id }}</h1>
                    <a href="{{ route('admin.appointments.index') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">
                        ← Назад к списку
                    </a>
                </div>

                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- ИНФОРМАЦИЯ О КЛИЕНТЕ (Только для чтения) --}}
                    <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-100 space-y-1">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Клиент</span>
                        <div class="text-sm font-bold text-slate-800">
                            {{ $appointment->user->last_name }} {{ $appointment->user->name }}
                        </div>
                        <div class="text-xs text-slate-500 font-medium">
                            {{ $appointment->user->phone ?? 'Телефон не указан' }}
                        </div>
                    </div>

                    {{-- ВЫБОР УСЛУГИ --}}
                    <div>
                        <label for="service_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Услуга</label>
                        <select id="service_id"
                                name="service_id"
                                x-model="selectedService"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                            @foreach(\App\Models\Service::all() as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ВЫБОР МАСТЕРА --}}
                    <div>
                        <label for="specialist_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Мастер</label>
                        <select id="specialist_id"
                                name="specialist_id"
                                x-model="selectedSpecialist"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                            @foreach(\App\Models\Specialist::with('user')->get() as $specialist)
                                <option value="{{ $specialist->id }}">
                                    {{ $specialist->user->last_name }} {{ $specialist->user->name }} ({{ $specialist->level->name ?? 'Мастер' }})
                                </option>
                            @endforeach
                        </select>
                        @error('specialist_id')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ВЫБОР ДАТЫ --}}
                    <div>
                        <label for="appointment_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Дата записи</label>
                        <input type="date"
                               id="appointment_date"
                               name="appointment_date"
                               x-model="selectedDate"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                        @error('appointment_date')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- СЛОТЫ ВРЕМЕНИ --}}
                    <div class="mt-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            Время записи
                            <span x-show="loading" class="text-pink-500 text-xs ml-2 font-normal animate-pulse">(Обновление слотов...)</span>
                        </label>

                        <input type="hidden" name="appointment_time" x-model="selectedTime" required>

                        {{-- Если слотов нет --}}
                        <template x-if="availableSlots.length === 0 && !loading && selectedSpecialist && selectedDate && selectedService">
                            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800 text-xs font-medium">
                                ⚠️ У мастера нет окон на эту дату для выбранной услуги.
                            </div>
                        </template>

                        {{-- Сетка плиток времени --}}
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

                    {{-- СТАТУС ЗАПИСИ --}}
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Статус записи</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                            <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Ожидает подтверждения (Pending)</option>
                            <option value="approved" {{ old('status', $appointment->status) == 'approved' ? 'selected' : '' }}>Подтверждена (Approved)</option>
                            <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Выполнена (Completed)</option>
                            <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Отменена (Cancelled)</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- КНОПКИ УПРАВЛЕНИЯ --}}
                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.appointments.index') }}"
                           class="w-full py-3.5 text-center text-slate-700 font-bold rounded-xl transition-all tracking-wide text-xs uppercase bg-slate-100 hover:bg-slate-200">
                            Отмена
                        </a>
                        <button type="submit"
                                class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl transition-all shadow-md shadow-pink-100 tracking-wide text-xs uppercase">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
