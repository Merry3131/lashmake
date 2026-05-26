@props(['categories', 'promotions', 'specialists'])
<div x-data="{
        step: 'main',

        selectedSpecialist: null,
        specialistName:'',
        specialistLevel: '',
        specialistBio: '',

        selectedService: null,
        serviceName: '',
        serviceDuration: '',
        servicePrice: '',

        selectedDate: '{{ date('Y-m-d') }}',
        selectedTime: null,
        specialistServices: {},
        serviceSpecialists: [],
        availableSlots: [],
        loading: false,

        async loadSlots() {
            // Если мастер или дата не выбраны, очищаем слоты и выходим
            if (!this.selectedSpecialist || !this.selectedDate) {
                this.availableSlots = [];
                return;
            }
            this.loading = true;
            try {
                // Передаем параметры, которые ожидает твой WorkScheduleController
                let response = await fetch(`/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}`);
                this.availableSlots = await response.json();
            }
            catch(e) {
                console.error('Ошибка загрузки временных слотов: ', e);
                this.availableSlots = [];
            }
            finally {
                this.loading = false;
            }
        },

        // Инициализация слежения за переменными
        init() {
            // Как только меняется мастер или дата — автоматически перерасчитываем окна
            this.$watch('selectedSpecialist', value => this.loadSlots());
            this.$watch('selectedDate', value => this.loadSlots());
        }
    }"
     x-show="$store.modalManager.bookingOpen"
     x-cloak
     @keydown.escape.window="$store.modalManager.closeBooking()"
     class="relative z-50">
    <div x-show="$store.modalManager.bookingOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="$store.modalManager.closeBooking()"
             class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl relative">
            <button @click="$store.modalManager.closeBooking()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-pink-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h1 class="text-2xl font-bold text-pink-500 mb-6 text-center">Запись на услугу</h1>

            {{-- Шаг 1: Главная --}}
            <div x-show="step === 'main'" class="space-y-4">
                <button @click="step = 'services'"
                        class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl font-semibold hover:bg-pink-200 transition-colors">
                    Выбрать услугу
                </button>
                <button @click="step = 'specialists'"
                        class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl font-semibold hover:bg-pink-200 transition-colors">
                    Выбрать мастера
                </button>
            </div>

            {{-- Шаг 2: Услуги --}}
            <div x-show="step === 'services'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите услугу</h1>
                </div>

                <div class="max-h-96 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                    @foreach($promotions as $promotion)
                        <button @click="
                            selectedService = {{ $promotion->service->id }};
                            serviceName = '{{ $promotion->service->name }}';
                            serviceDuration = '{{ $promotion->service->duration }} .мин';
                            servicePrice = '{{ $promotion->service->base_price }} ₽';

                            serviceSpecialists = @js($promotion->service->specialists()->with('user')->get()->groupBy('level_id'));
                            step = 'specialists_at_service';
                            loadSlots();"
                                class="w-full text-left p-3 rounded-xl border-2 border-pink-200 bg-pink-50 hover:bg-pink-100 transition-all">
                            <div class="font-bold text-gray-800">{{ $promotion->title }}</div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $promotion->service->name ?? '' }}</span>
                                <span class="bg-pink-500 text-white px-2 py-0.5 rounded-full">-{{ $promotion->discount_percent }}%</span>
                            </div>
                        </button>
                    @endforeach

                    @foreach($categories as $category)
                        @if($category->services->isNotEmpty())
                            <h2 class="text-sm font-bold uppercase text-gray-400 mt-4">{{ $category->display_name }}</h2>

                            @foreach($category->services as $service)
                                <button @click="
                                        selectedService = {{ $service->id }};
                                        serviceName = '{{ addslashes($service->name) }}';

                                        {{-- Берем минимальную цену и время для отображения на следующих шагах как 'от ...' --}}
                                        servicePrice = 'от {{ number_format($service->levels->min('pivot.price'), 0, '.', '') }} ₽';
                                        serviceDuration = 'от {{ $service->levels->min('pivot.duration') }} мин.';

                                        {{-- Группируем мастеров по имени их уровня. Используем загруженную связь level --}}
                                        serviceSpecialists = @js($service->specialists->load(['user', 'level'])->groupBy(fn($spec) => $spec->level->name ?? 'Без категории'));

                                        step = 'specialists_at_service';
                                        loadSlots();"

                                        class="w-full text-left p-4 mb-2 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all">

                                    <div class="font-bold text-gray-800 mb-2">{{ $service->name }}</div>

                                    {{-- Вывод ценника услуги в зависимости от уровня мастера --}}
                                    <div class="space-y-1.5 mt-2 bg-white/50 p-2 rounded-lg">
                                        @foreach($service->levels as $level)
                                            <div class="flex justify-between items-center text-xs">
                                                <span
                                                    class="text-gray-500 uppercase font-semibold">{{ $level->name }}</span>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-gray-400 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $level->pivot->duration }} мин.
                                                    </span>
                                                    <span class="text-pink-600 font-bold bg-pink-100 px-2 py-0.5 rounded-md">
                                                        {{ number_format($level->pivot->price, 0, '.', ' ') }} ₽
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </button>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Шаг 3: Специалисты --}}
            <div x-show="step === 'specialists'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите специалиста</h1>
                </div>

                <div class="max-h-96 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                    @forelse($team as $specialist)

                        <button @click="


                                selectedSpecialist = {{ $specialist->id }};
                                specialistName = '{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}';
                                specialistLevel = '{{ $specialist->level->name }}';
                                specialistBio = '{{ $specialist->bio }}';

                                specialistServices = @js($specialist->service_specialist()->with(['category', 'levels'])->get()->each(function($service) use ($specialist) {
    // Находим уровень, соответствующий мастеру
    $matchingLevel = $service->levels->firstWhere('id', $specialist->level_id);

    // Записываем чистые свойства прямо в первый уровень объекта услуги
    $service->matched_price = $matchingLevel ? (float)$matchingLevel->pivot->price : (float)($service->base_price ?? 0);
    $service->matched_duration = $matchingLevel ? (int)$matchingLevel->pivot->duration : (int)($service->duration ?? 0);
})->groupBy('category.display_name'));
                                step = 'services_at_specialist';
                                loadSlots();"
                                class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">

                            <div
                                class="w-12 h-12 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 font-bold">
                                {{ mb_substr($specialist->user->first_name ?? 'M', 0, 1) }}
                            </div>

                            <div>
                                <div
                                    class="font-medium text-gray-800">{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}</div>
                                <div class="text-xs text-pink-500 font-bold">{{ $specialist->level_name }}</div>
                                <div class="text-xs text-gray-500 italic">{{ Str::limit($specialist->bio, 60) }}</div>
                            </div>
                        </button>
                    @empty
                        <p class="text-center text-gray-400 py-10">Нет свободных мастеров</p>
                    @endforelse
                </div>
            </div>
            {{-- Шаг 3.1: выбор услуги с учетом мастера --}}
            <div x-show="step === 'services_at_specialist'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'specialists'" class="absolute left-0 text-pink-500 hover:underline text-sm">
                        Назад
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Услуги мастера</h1>
                </div>

                <div class="max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                    {{-- Перебираем категории услуг --}}
                    <template x-for="(services, categoryName) in specialistServices" :key="categoryName">
                        <div class="mb-6">
                            <h2 x-text="categoryName" class="text-xs font-bold uppercase text-gray-400 mb-3 ml-1"></h2>

                            <div class="space-y-2">
                                {{-- Перебираем услуги в категории --}}
                                <template x-for="service in services" :key="service.id">
                                    <button @click="
                            selectedService = service.id;
                            serviceName = service.name;

                            {{-- Получаем цену и длительность напрямую из JS объекта пивота текущей услуги --}}
                            servicePrice = (service.levels?.[0]?.pivot?.price || service.base_price) + ' ₽';
                            serviceDuration = (service.levels?.[0]?.pivot?.duration || service.duration) + ' мин.';

                            step = 'calendar';
                            loadSlots();"
                                            class="w-full text-left p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all">

                                        <div class="font-medium text-gray-700" x-text="service.name"></div>

                                        {{-- Полностью убрали PHP циклы @foreach. Выводим данные через Alpine.js --}}
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-pink-500 font-semibold"
                                                 x-text="(service.levels?.[0]?.pivot?.price ? Math.round(service.levels[0].pivot.price) : Math.round(service.base_price)) + ' ₽'">
                                            </div>

                                            <div class="text-[10px] text-gray-400 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span x-text="(service.levels?.[0]?.pivot?.duration || service.duration) + ' мин.'"></span>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Если список пуст --}}
                    <template x-if="Object.keys(specialistServices).length === 0">
                        <p class="text-center text-gray-400 py-10">У этого мастера пока нет назначенных услуг</p>
                    </template>
                </div>
            </div>

            {{-- Шаг 2.1: выбор мастера с учетом услуги --}}
            <div x-show="step === 'specialists_at_service'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'services'" class="absolute left-0 text-pink-500 hover:underline text-sm">
                        Назад
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите мастера</h1>

                </div>

                <div class="max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="(specialists, levelName) in serviceSpecialists" :key="levelName">
                        <div class="mb-6">

                            <h2 x-text="levelName" class="text-xs font-bold uppercase text-pink-500 mb-3 ml-1"></h2>

                            <div class="space-y-2">
                                <template x-for="specialist in specialists" :key="specialist.id">
                                    <button @click="
                                            selectedSpecialist = specialist.id;

                                            specialistName = specialist.user.first_name + ' ' + (specialist.user.last_name || '');
                                            specialistLevel = levelName;
                                            specialistBio = specialist.bio || '';
                                            step = 'calendar';
                                            loadSlots();"
                                            class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">

                                        <div
                                            class="w-10 h-10 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 font-bold text-sm">

                                        </div>

                                        <div class="flex-1">
                                            <div class="font-medium text-gray-700"
                                                 x-text="specialist.user.first_name + ' ' + (specialist.user.last_name || '')"></div>
                                            <div class="text-[10px] text-gray-400 italic" x-text="specialist.bio"></div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="Object.keys(serviceSpecialists).length === 0">
                        <div class="text-center py-10">
                            <p class="text-gray-400">К сожалению, для этой услуги сейчас нет свободных мастеров</p>
                        </div>
                    </template>
                </div>
            </div>


            {{-- Шаг 4.1: Выбор даты и времени --}}
            <div x-show="step === 'calendar'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'specialists_at_service'"
                            class="absolute left-0 text-pink-500 hover:underline text-sm">Назад
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Дата и время</h1>
                </div>

                {{-- 1. Горизонтальный выбор даты --}}
                <div class="mb-6">
                    <label class="text-xs text-gray-400 uppercase font-bold px-1">Доступные даты</label>
                    <div class="flex gap-2 overflow-x-auto py-2 custom-scrollbar">
                        @for($i = 0; $i < 30; $i++)
                            @php $date = \Carbon\Carbon::today()->addDays($i); @endphp
                            <button @click="selectedDate = '{{ $date->format('Y-m-d') }}'; loadSlots();"
                                    :class="selectedDate === '{{ $date->format('Y-m-d') }}' ? 'bg-pink-500 text-white shadow-lg shadow-pink-200' : 'bg-pink-50 text-pink-600'"
                                    class="flex-shrink-0 w-16 py-3 rounded-2xl text-center transition-all border border-pink-100">
                                <div
                                    class="text-[10px] uppercase font-semibold">{{ $date->translatedFormat('D') }}</div>
                                <div class="text-lg font-bold">{{ $date->format('d') }}</div>
                                <div class="text-[10px] uppercase">{{ $date->translatedFormat('M') }}</div>
                            </button>
                        @endfor
                    </div>
                </div>


                {{-- 2. Информационная карточка выбранного специалиста --}}
                <div class="bg-gray-50 rounded-3xl p-5 mb-4 border border-gray-100 shadow-sm">
                    <h1>Выбранный мастер: </h1>
                    <div class="flex items-center gap-4 mb-3">
                        <div
                            class="w-16 h-16 bg-pink-500 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                            <span x-text="specialistName ? specialistName[0] : 'M'"></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-extrabold text-gray-800 text-lg" x-text="specialistName"></h3>
                                <span
                                    class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-lg uppercase font-bold"
                                    x-text="specialistLevel"></span>
                            </div>
                            <div class="text-sm text-gray-500 mt-0.5 italic">Ваш мастер</div>
                        </div>
                    </div>

                    {{-- Блок BIO --}}
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <p class="text-xs text-gray-600 leading-relaxed" x-text="specialistBio"></p>
                    </div>
                </div>

                {{-- 2. Информационная карточка услуги --}}
                <div class="bg-gray-50 rounded-3xl p-5 mb-4 border border-gray-100 shadow-sm">
                    <h1>Выбранная услуга: </h1>
                    <div class="flex items-center gap-4 mb-3">

                        <div
                            class="w-16 h-16 bg-pink-500 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                            <span x-text="serviceName ? serviceName[0] : 'S'"></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-extrabold text-gray-800 text-lg" x-text="serviceName"></h3>
                                <span
                                    class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-lg uppercase font-bold"
                                    x-text="servicePrice"></span>
                            </div>
                            <div class="text-sm text-gray-500 mt-0.5 italic" x-text="serviceDuration"></div>
                        </div>
                    </div>
                </div>

                {{-- 3. Сетка выбора времени --}}
                <div class="max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                    <label class="text-xs text-gray-400 uppercase font-bold px-1 mb-2 block">Доступные слоты</label>

                    <template x-if="loading">
                        <div class="flex justify-center py-10">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500"></div>
                        </div>
                    </template>

                    <div class="grid grid-cols-4 gap-2" x-show="!loading">
                        <template x-for="slot in availableSlots" :key="slot">
                            <button @click="selectedTime = slot; step = 'final_check'"
                                    class="p-2 text-sm font-bold rounded-xl border border-pink-100 text-pink-600 bg-white hover:bg-pink-500 hover:text-white hover:shadow-md transition-all text-center">
                                <span x-text="slot"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Заглушка, если времени нет --}}
                    <template x-if="!loading && availableSlots.length === 0">
                        <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-400 text-sm">На выбранную дату свободных мест нет</p>
                            <p class="text-xs text-gray-400 mt-1">Попробуйте выбрать другой день</p>
                        </div>
                    </template>
                </div>
            </div>

            {{--Финальный шаг--}}
            <div x-show="step === 'final_check'" class="space-y-6" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'date_time'" class="absolute left-0 text-pink-500 hover:underline text-sm">
                        Назад
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Проверьте детали записи</h1>
                </div>

                <div class="bg-pink-50/50 border border-pink-100 rounded-2xl p-4 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-pink-500 rounded-xl text-white mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase">Услуга</div>
                            <div class="font-bold text-gray-800 text-base" x-text="serviceName"></div>
                            <div class="text-xs text-gray-500" x-text="'Длительность: ' + serviceDuration"></div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-pink-500 rounded-xl text-white mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase">Мастер</div>
                            <div class="font-bold text-gray-800 text-base" x-text="specialistName"></div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-pink-500 rounded-xl text-white mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase">Дата и время</div>
                            <div class="font-bold text-gray-800 text-base">
                                <span
                                    x-text="new Date(selectedDate).toLocaleDateString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                                в <span x-text="selectedTime"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="font-medium text-gray-600">Итого к оплате:</span>
                    <span class="text-2xl font-black text-pink-600" x-text="servicePrice"></span>
                </div>

                <div>
                    @auth
                        <button @click="
               loading = true;
fetch('/api/appointments', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json', // <--- ДОБАВЬ ЭТУ СТРОКУ ОБЯЗАТЕЛЬНО
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        specialist_id: selectedSpecialist,
        service_id: selectedService,
        date: selectedDate,
        time: selectedTime
    })
})
.then(async res => {
    const data = await res.json();
    if (!res.ok) {
        // Если ошибка валидации, выводим подробности в консоль
        console.error('Ошибки валидации от Laravel:', data.errors);
        throw new Error(data.message || 'Ошибка валидации');
    }
    return data;
})
.then(data => {
    loading = false;
    alert('Вы успешно записались!');
    $store.modalManager.closeBooking();
    window.location.href = '/dashboard';
})
.catch(e => {
    loading = false;
    alert('Ошибка при создании записи: ' + e.message);
});"
                                :disabled="loading"
                                class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center disabled:opacity-50">
                            <span x-show="!loading">Подтвердить запись</span>
                            <span x-show="loading">Оформление записи...</span>
                        </button>
                    @else
                        <div class="space-y-2">
                            <div class="text-center text-sm text-gray-500">
                                Для завершения онлайн-записи необходимо авторизоваться
                            </div>
                            <a href="{{ route('login') }}"
                               class="block w-full bg-gray-800 hover:bg-gray-900 text-white font-bold p-4 rounded-xl text-center transition-all">
                                Войти в личный кабинет
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</div>
