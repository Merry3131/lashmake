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
        specialistServices: [],
        serviceSpecialists: [],
        availableSlots: [],
        loading: false,

        async loadSlots() {
            if (!this.selectedSpecialist || !this.selectedDate) return;
            this.loading = true;
            try{
                let response = await fetch(`/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}`);
                this.availableSlots = await response.json();
            }
            catch(e) {
                console.error('Ошибка загрузки: ', e);
            }
            finally {
                this.loading = false;
            }
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h1 class="text-2xl font-bold text-pink-500 mb-6 text-center">Запись на услугу</h1>

            {{-- Шаг 1: Главная --}}
            <div x-show="step === 'main'" class="space-y-4">
                <button @click="step = 'services'" class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl font-semibold hover:bg-pink-200 transition-colors">Выбрать услугу</button>
                <button @click="step = 'specialists'" class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl font-semibold hover:bg-pink-200 transition-colors">Выбрать мастера</button>
            </div>

            {{-- Шаг 2: Услуги --}}
            <div x-show="step === 'services'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите услугу</h1>
                </div>

                <div class="max-h-96 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                    @foreach($promotions as $promotion)
                        <button @click="
                            selectedService = {{ $promotion->service->id }};
                            serviceName = '{{ $promotion->service->name }}';
                            serviceDuration = '{{ $promotion->service->duration }} .мин';
                            servicePrice = '{{ $promotion->service->base_price }} ₽';

                            serviceSpecialists = @js($promotion->service->specialists()->with('user')->get()->groupBy('level_name'));
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
                                    serviceName = '{{ $service->name }}';
                                    serviceDuration = '{{ $service->duration }} .мин';
                                    servicePrice = '{{ $service->base_price }} ₽';

                                    serviceSpecialists = @js($service->specialists()->with('user')->get()->map(function($spec) {
                                        // Добавляем level_name прямо в объект, чтобы JS его видел
                                        $spec->level_name = $spec->level_name;
                                        return $spec;
                                    })->groupBy('level_name'));
                                    step = 'specialists_at_service';
                                    loadSlots();"

                                    class="w-full text-left p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50">
                                    <div class="font-medium text-gray-700">{{ $service->name }}</div>
                                    <div class="text-xs text-pink-500">{{ number_format($service->base_price, 0, '.', ' ') }} ₽</div>
                                </button>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Шаг 3: Специалисты --}}
            <div x-show="step === 'specialists'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
                    <h1 class="text-xl font-bold text-gray-800">Выберите специалиста</h1>
                </div>

                <div class="max-h-96 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                    @forelse($team as $specialist)

                        <button @click="
                                selectedSpecialist = {{ $specialist->id }};
                                specialistName = '{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}';
                                specialistLevel = '{{ $specialist->level }}';
                                specialistBio = '{{ $specialist->bio }}';

                                specialistServices = {{ $specialist->specialists->groupBy('category.display_name')->toJson() }};
                                step = 'services_at_specialist';
                                loadSlots();"
                                class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">

                            <div class="w-12 h-12 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 font-bold">
                                {{ mb_substr($specialist->user->first_name ?? 'M', 0, 1) }}
                            </div>

                            <div>
                                <div class="font-medium text-gray-800">{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}</div>
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
                    <button @click="step = 'specialists'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
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
                                        servicePrice = service.base_price + ' ₽';
                                        serviceDuration = service.duration + ' мин.';
                                        step = 'calendar';
                                        loadSlots();"
                                            class="w-full text-left p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all">

                                        <div class="font-medium text-gray-700" x-text="service.name"></div>

                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-pink-500 font-semibold" x-text="service.base_price + ' ₽'"></div>

                                            <div class="text-[10px] text-gray-400 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span x-text="service.duration + ' мин.'"></span>
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
                    <button @click="step = 'services'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
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
                                            step = 'calendar_2';
                                            loadSlots();"
                                            class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">

                                        <div class="w-10 h-10 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 font-bold text-sm">

                                        </div>

                                        <div class="flex-1">
                                            <div class="font-medium text-gray-700" x-text="specialist.user.first_name + ' ' + (specialist.user.last_name || '')"></div>
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
                    <button @click="step = 'services_at_specialist'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
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
                            <div class="text-[10px] uppercase font-semibold">{{ $date->translatedFormat('D') }}</div>
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
                        <div class="w-16 h-16 bg-pink-500 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                            <span x-text="specialistName ? specialistName[0] : 'M'"></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-extrabold text-gray-800 text-lg" x-text="specialistName"></h3>
                                <span class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-lg uppercase font-bold" x-text="specialistLevel"></span>
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

                        <div class="w-16 h-16 bg-pink-500 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                            <span x-text="serviceName ? serviceName[0] : 'S'"></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-extrabold text-gray-800 text-lg" x-text="serviceName"></h3>
                                <span class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-lg uppercase font-bold" x-text="servicePrice"></span>
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
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-400 text-sm">На выбранную дату свободных мест нет</p>
                            <p class="text-xs text-gray-400 mt-1">Попробуйте выбрать другой день</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Шаг 4.1: Выбор даты и времени --}}
            <div x-show="step === 'calendar_2'" class="space-y-4" x-transition>
                <div class="relative flex items-center justify-center mb-4">
                    <button @click="step = 'specialists_at_service'" class="absolute left-0 text-pink-500 hover:underline text-sm">Назад</button>
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
                                <div class="text-[10px] uppercase font-semibold">{{ $date->translatedFormat('D') }}</div>
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
                        <div class="w-16 h-16 bg-pink-500 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                            <span x-text="specialistName ? specialistName[0] : 'M'"></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-extrabold text-gray-800 text-lg" x-text="specialistName"></h3>
                                <span class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-lg uppercase font-bold" x-text="specialistLevel"></span>
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

                        <div class="w-16 h-16 bg-pink-500 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                            <span x-text="serviceName ? serviceName[0] : 'S'"></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-extrabold text-gray-800 text-lg" x-text="serviceName"></h3>
                                <span class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-lg uppercase font-bold" x-text="servicePrice"></span>
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
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-400 text-sm">На выбранную дату свободных мест нет</p>
                            <p class="text-xs text-gray-400 mt-1">Попробуйте выбрать другой день</p>
                        </div>
                    </template>
                </div>
            </div>



        </div>
    </div>
</div>
