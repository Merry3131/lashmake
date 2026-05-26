@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование записи')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <div class="flex-1 flex flex-col overflow-y-auto"
         x-data="{
            selectedSpecialist: '{{ $appointment->specialist_id }}',
            selectedDate: '{{ \Carbon\Carbon::parse($appointment->appointment_at)->format('Y-m-d') }}',
            selectedTime: '{{ \Carbon\Carbon::parse($appointment->appointment_at)->format('H:i') }}',
            availableSlots: [],
            loading: false,

            async loadSlots() {
                if (!this.selectedSpecialist || !this.selectedDate) {
                    this.availableSlots = [];
                    return;
                }

                this.loading = true;

                try {
                    let response = await fetch(`/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}&ignore_appointment_id={{ $appointment->id }}`);
                    let slots = await response.json();

                    this.availableSlots = slots;

                    // Если старое время недоступно в новом дне/у мастера — сбрасываем его
                    if (this.selectedTime && !slots.includes(this.selectedTime)) {
                        this.selectedTime = null;
                    }
                }
                catch(e) {
                    console.error('Ошибка загрузки временных слотов: ', e);
                    this.availableSlots = [];
                }
                finally {
                    this.loading = false;
                }
            }
         }"
         x-init="loadSlots()"> <header class="px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800">Перенос и редактирование записи</h1>
            <a href="{{ route('admin.appointments.index') }}"
               class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                ← Назад к списку
            </a>
        </header>

        <main class="p-8">
            <div class="max-w-xl space-y-6">

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200/60 space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Информация о визите</h2>
                    <div>
                        <span class="text-xs text-slate-400 block">Клиент:</span>
                        <strong class="text-sm text-slate-800">
                            {{ $appointment->user->last_name ?? '' }} {{ $appointment->user->first_name ?? 'Неизвестный' }}
                        </strong>
                        @if($appointment->user && $appointment->user->phone)
                            <span class="text-xs text-slate-500 block mt-0.5">{{ $appointment->user->phone }}</span>
                        @endif
                    </div>
                    <div class="pt-2 border-t border-slate-200">
                        <span class="text-xs text-slate-400 block">Выбранная услуга:</span>
                        <strong class="text-sm text-slate-800">{{ $appointment->service->name ?? 'Услуга удалена' }}</strong>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="appointment_date" :value="selectedDate" required>
                        <input type="hidden" name="appointment_time" :value="selectedTime" required>

                        <div>
                            <label for="specialist_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Назначенный мастер
                            </label>
                            <select id="specialist_id" name="specialist_id"
                                    x-model="selectedSpecialist"
                                    @change="loadSlots()"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                                @foreach($specialists as $specialist)
                                    <option value="{{ $specialist->id }}">
                                        {{ $specialist->user->last_name ?? '' }} {{ $specialist->user->first_name ?? '' }}
                                        ({{ match($specialist->level->name ?? '') { 'master' => 'Мастер', 'top' => 'Топ-Мастер', 'lead' => 'Ведущий', default => $specialist->level->name ?? 'Без уровня' } }})
                                    </option>
                                @endforeach
                            </select>
                            @error('specialist_id')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Доступные даты визита
                            </label>

                            <div class="flex gap-2 overflow-x-auto py-2 custom-scrollbar" style="scrollbar-width: thin;">
                                @php
                                    // Умная генерация дат: если запись была в прошлом, начинаем отсчет от нее, а не от сегодня!
                                    $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_at)->startOfDay();
                                    $today = \Carbon\Carbon::today();
                                    $startDate = $appointmentDate->lessThan($today) ? $appointmentDate : $today;
                                @endphp

                                @for($i = 0; $i < 30; $i++)
                                    @php
                                        $date = $startDate->copy()->addDays($i);
                                        $dateValue = $date->format('Y-m-d');
                                    @endphp

                                    <button type="button"
                                            @click="selectedDate = '{{ $dateValue }}'; loadSlots();"
                                            :class="selectedDate === '{{ $dateValue }}' ? 'bg-pink-500 text-white shadow-lg shadow-pink-200 border-pink-500' : 'bg-pink-50/60 text-pink-600 border-pink-100/70 hover:bg-pink-50'"
                                            class="flex-shrink-0 w-16 py-3 rounded-2xl text-center transition-all border font-medium focus:outline-none">
                                        <div class="text-[10px] uppercase font-semibold opacity-80">{{ $date->translatedFormat('D') }}</div>
                                        <div class="text-lg font-bold leading-tight my-0.5">{{ $date->format('d') }}</div>
                                        <div class="text-[10px] uppercase opacity-80">{{ $date->translatedFormat('M') }}</div>
                                    </button>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                Свободные окна на выбранный день
                            </label>

                            <div x-show="loading">
                                <p class="text-sm text-slate-400">Загрузка окон...</p>
                            </div>

                            <div x-show="!loading && availableSlots.length === 0">
                                <p class="text-sm text-rose-500 font-medium">Нет свободных окон или у мастера выходной день</p>
                            </div>

                            <div x-show="!loading && availableSlots.length > 0" class="grid grid-cols-4 gap-2.5">
                                <template x-for="slot in availableSlots" :key="slot">
                                    <button type="button"
                                            @click="selectedTime = slot"
                                            :class="selectedTime === slot ? 'bg-pink-500 text-white shadow-lg shadow-pink-200 border-pink-500 font-bold' : 'bg-slate-50 border-slate-200/80 text-slate-700 font-semibold hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200'"
                                            class="py-2.5 rounded-xl border text-sm transition-all focus:outline-none"
                                            x-text="slot">
                                    </button>
                                </template>
                            </div>

                            @error('appointment_time')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Статус визита
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                                <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>
                                    Ожидает подтверждения
                                </option>
                                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>
                                    Подтверждена
                                </option>
                                <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                                    Отменена
                                </option>
                            </select>
                            @error('status')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('admin.appointments.index') }}"
                               class="w-full py-3 text-center text-black font-bold rounded-xl transition-all tracking-wide text-xs uppercase flex items-center justify-center bg-slate-100 hover:bg-slate-200">
                                Отмена
                            </a>
                            <button type="submit"
                                    class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl transition-all shadow-md shadow-pink-100 tracking-wide text-xs uppercase">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
@endsection
