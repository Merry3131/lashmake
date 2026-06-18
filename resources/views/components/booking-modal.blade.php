@props(['categories', 'promotions', 'specialists'])
<div x-data="{
        step: 'main',
        previousStep: 'main',

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

        guestName: '',
        guestLastName: '',
        guestPhone: '+79',
        guestNameError: '',
        guestPhoneError: '',

         discountPercent: 0,
         promotionSpecialistId: null,

        resetModal() {
            this.step = 'main';
            this.previousStep = 'main';
            this.selectedSpecialist = null;
            this.specialistName = '';
            this.specialistLevel = '';
            this.specialistBio = '';
            this.selectedService = null;
            this.serviceName = '';
            this.serviceDuration = '';
            this.servicePrice = '';
            this.selectedDate = '{{ date('Y-m-d') }}';
            this.selectedTime = null;
            this.availableSlots = [];
            this.discountPercent = 0;
            this.promotionSpecialistId = null;
        },


        async loadSlots() {
            if (!this.selectedSpecialist || !this.selectedDate || !this.selectedService) {
                this.availableSlots = [];
                return;
            }
            this.loading = true;
            try {
                let response = await fetch(`/api/slots?specialist_id=${this.selectedSpecialist}&date=${this.selectedDate}&service_id=${this.selectedService}`);
                if (!response.ok) {
                    this.availableSlots = [];
                    return;
                }
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

        async fetchCustomPriceAndDuration() {
            if (!this.selectedSpecialist || !this.selectedService) return;

            let url = `/api/level-service-info?specialist_id=${this.selectedSpecialist}&service_id=${this.selectedService}`;
            if (this.discountPercent > 0) {
                url += `&discount=${this.discountPercent}`;
            }

            try {
                let response = await fetch(url);
                if (response.ok) {
                    let data = await response.json();
                    this.servicePrice = data.price;          // уже со скидкой
                    this.serviceDuration = data.duration >= 60
                        ? `${Math.floor(data.duration / 60)} ч. ${data.duration % 60 > 0 ? data.duration % 60 + ' мин.' : ''}`
                        : `${data.duration} мин.`;
                }
            } catch(e) {
                console.error('Ошибка получения цены:', e);
            }
        },

        async applyPromotion(promotion) {
            this.selectedService = promotion.service_id;
            this.serviceName = promotion.title;
            this.discountPercent = promotion.discount_percent;
            this.promotionSpecialistId = promotion.specialist_id;

            if (promotion.specialist_id) {
                this.selectedSpecialist = promotion.specialist_id;

                await this.fetchSpecialistInfo(promotion.specialist_id);

                await this.fetchCustomPriceAndDuration();

                this.step = 'calendar';
            } else {
                this.step = 'specialists_at_service';
            }
        },

        async fetchSpecialistInfo(specialistId) {
            try {
                let response = await fetch(`/api/specialist/${specialistId}`);
                if (response.ok) {
                    let data = await response.json();
                    this.specialistName = data.name;
                    this.specialistLevel = data.level;
                    this.specialistBio = data.bio;
                }
            } catch(e) {
                console.error(e);
            }
        },

        init() {
            this.$watch('$store.modalManager.bookingOpen', value => {
                if (!value) {
                    this.resetModal();
                }
            });
            this.$watch('selectedSpecialist', value => { this.loadSlots(); this.fetchCustomPriceAndDuration(); });
            this.$watch('selectedDate', value => this.loadSlots());
            this.$watch('selectedService', value => { this.loadSlots(); this.fetchCustomPriceAndDuration(); });
        },

    }"
     x-show="$store.modalManager.bookingOpen"
     x-cloak
     @keydown.escape.window="if(confirm('Вы точно хотите завершить запись на услугу? Все заполненные данные будут потеряны.')) $store.modalManager.closeBooking()"
     class="relative z-50 font-['Manrope']">
    <div x-show="$store.modalManager.bookingOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">

        <div @click.away="step === 'main' ? $store.modalManager.closeBooking() : (confirm('Вы точно хотите завершить запись на услугу? Все заполненные данные будут потеряны.') && $store.modalManager.closeBooking())"
             class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative overflow-hidden">

            <button @click="step === 'main' ? $store.modalManager.closeBooking() : (confirm('Вы точно хотите завершить запись на услугу? Все заполненные данные будут потеряны.') && $store.modalManager.closeBooking())"
                    class="absolute top-4 right-4 text-gray-400 hover:text-pink-500 z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="p-8 min-h-[550px] relative">
                <h1 class="text-2xl text-pink-500 mb-6 text-center font-[Playfair_Display] font-normal">Запись на услугу</h1>

                {{-- Шаг 1: Главная --}}
                <div x-show="step === 'main'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute inset-0 px-8 pb-8 overflow-y-auto">
                    <div class="h-full flex items-center justify-center min-h-[450px]">
                        <div class="space-y-4 w-full">
                            <button @click="step = 'services'"
                                    class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl hover:bg-pink-200 transition-colors font-[Manrope]">
                                Выбрать услугу
                            </button>
                            <button @click="step = 'specialists'"
                                    class="w-full bg-pink-100 text-pink-600 py-3 rounded-xl hover:bg-pink-200 transition-colors font-[Manrope]">
                                Выбрать мастера
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Шаг 2: Услуги --}}
                <div x-show="step === 'services'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="absolute inset-x-0 top-24 px-8 pb-8 overflow-y-auto"
                     style="top: 80px;">
                    <div class="relative flex items-center justify-center mb-4">
                        <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm font-[Manrope]">Назад</button>
                        <h1 class="text-xl text-gray-800 font-[Playfair_Display]">Выберите услугу</h1>
                    </div>

                    <div class="max-h-96 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                        @foreach($promotions as $promotion)
                            <button @click="applyPromotion({
                                    service_id: {{ $promotion->service_id }},
                                    title: '{{ addslashes($promotion->title) }}',
                                    discount_percent: {{ $promotion->discount_percent }},
                                    specialist_id: {{ $promotion->specialist_id ?? 'null' }}
                                })"
                                    class="w-full text-left p-3 rounded-xl border-2 border-pink-200 bg-pink-50 hover:bg-pink-100 transition-all">
                                <div class="text-gray-800 font-[Manrope]">{{ $promotion->title }}</div>
                                <div class="flex justify-between items-center text-sm text-gray-500 font-[Manrope]">
                                    <span>{{ $promotion->service->name ?? '' }}</span>
                                    <span class="bg-pink-500 text-white px-2 py-0.5 rounded-full">-{{ $promotion->discount_percent }}%</span>
                                </div>
                            </button>
                        @endforeach

                        @foreach($categories as $category)
                            @if($category->services->isNotEmpty())
                                <h2 class="text-sm  text-gray-400 mt-4 font-[Manrope]">{{ $category->display_name }}</h2>

                                @foreach($category->services as $service)
                                    <button @click="
                                            selectedService = {{ $service->id }};
                                            serviceName = '{{ addslashes($service->name) }}';

                                            servicePrice = 'от {{ number_format($service->levels->min('pivot.price'), 0, '.', '') }} ₽';
                                            serviceDuration = 'от {{ $service->levels->min('pivot.duration') }} мин.';

                                            serviceSpecialists = @js($service->specialists->load(['user', 'level'])->groupBy(fn($spec) => $spec->level->name ?? 'Без категории'));

                                            step = 'specialists_at_service';
                                            loadSlots();"

                                            class="w-full text-left p-4 mb-2 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all">

                                        <div class="text-gray-800 mb-2 font-[Manrope]">{{ $service->name }}</div>

                                        <div class="space-y-1.5 mt-2 bg-white/50 p-2 rounded-lg">
                                            @foreach($service->levels as $level)
                                                <div class="flex justify-between items-center text-sm font-[Manrope]">
                                                    <span class="text-gray-500 ">{{ $level->display_name }}</span>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-gray-400 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            {{ $level->pivot->duration }} мин.
                                                        </span>
                                                        <span class="text-pink-600 bg-pink-100 px-2 py-0.5 rounded-md">
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
                <div x-show="step === 'specialists'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="absolute inset-x-0 top-24 px-8 pb-8 overflow-y-auto"
                     style="top: 80px;">
                    <div class="relative flex items-center justify-center mb-4">
                        <button @click="step = 'main'" class="absolute left-0 text-pink-500 hover:underline text-sm font-[Manrope]">Назад</button>
                        <h1 class="text-xl text-gray-800 font-[Playfair_Display] font-normal">Выберите специалиста</h1>
                    </div>

                    <div class="max-h-96 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                        @forelse($team as $specialist)
                            <button @click="
                                    selectedSpecialist = {{ $specialist->id }};
                                    specialistName = '{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}';
                                    specialistLevel = '{{ $specialist->level->display_name }}';
                                    specialistBio = '{{ $specialist->bio }}';

                                    specialistServices = @js($specialist->service_specialist()->with(['category', 'levels'])->get()->each(function($service) use ($specialist) {
                                        $matchingLevel = $service->levels->firstWhere('id', $specialist->level_id);
                                        $service->matched_price = $matchingLevel ? (float)$matchingLevel->pivot->price : (float)($service->base_price ?? 0);
                                        $service->matched_duration = $matchingLevel ? (int)$matchingLevel->pivot->duration : (int)($service->duration ?? 0);
                                    })->groupBy('category.display_name'));
                                    step = 'services_at_specialist';
                                    loadSlots();"
                                    class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">

                                <div class="w-12 h-12 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 font-[Manrope]">
                                    {{ mb_substr($specialist->user->first_name ?? 'M', 0, 1) }}
                                </div>

                                <div>
                                    <div class="font-normal text-gray-800 font-[Manrope]">{{ $specialist->user->first_name }} {{ $specialist->user->last_name }}</div>
                                    <div class="text-sm text-pink-500 font-[Manrope]">{{ $specialist->level->display_name }}</div>
                                    <div class="text-sm text-gray-500 font-[Manrope]">{{ Str::limit($specialist->bio, 60) }}</div>
                                </div>
                            </button>
                        @empty
                            <p class="text-center text-gray-400 py-10 font-[Manrope]">Нет свободных мастеров</p>
                        @endforelse
                    </div>
                </div>

                {{-- Шаг 3.1: выбор услуги с учетом мастера --}}
                <div x-show="step === 'services_at_specialist'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="absolute inset-x-0 top-24 px-8 pb-8 overflow-y-auto"
                     style="top: 80px;">
                    <div class="relative flex items-center justify-center mb-6">
                        <button @click="step = 'specialists'" class="absolute left-0 text-pink-500 hover:text-pink-600 hover:underline text-sm transition-colors flex items-center gap-1 font-[Manrope]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Назад
                        </button>
                        <h1 class="text-xl text-gray-800 font-[Playfair_Display] font-normal">Услуги мастера</h1>
                    </div>

                    <div class="max-h-96 overflow-y-auto pr-2 custom-scrollbar space-y-4">
                        <template x-for="(services, categoryName) in specialistServices" :key="categoryName">
                            <div class="mb-6">
                                <h2 x-text="categoryName" class="text-sm   text-gray-400 mb-3 ml-1 font-[Manrope]"></h2>
                                <div class="space-y-2.5">
                                    <template x-for="service in services" :key="service.id">
                                        <button @click="
                                                selectedService = service.id;
                                                serviceName = service.name;
                                                servicePrice = (service.levels?.[0]?.pivot?.price || service.base_price) + ' ₽';
                                                serviceDuration = (service.levels?.[0]?.pivot?.duration || service.duration) + ' мин.';
                                                previousStep = 'services_at_specialist';
                                                step = 'calendar';
                                                loadSlots();"
                                                class="w-full text-left p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:border-pink-200 hover:bg-pink-50/30 hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between gap-3">
                                            <div class="text-gray-800 text-base leading-snug font-[Manrope]" x-text="service.name"></div>
                                            <div class="flex justify-between items-center w-full border-t border-gray-50 pt-2 mt-1">
                                                <div class="text-sm text-pink-600 bg-pink-50/80 px-2.5 py-1 rounded-lg font-[Manrope]"
                                                     x-text="(service.levels?.[0]?.pivot?.price ? Math.round(service.levels[0].pivot.price) : Math.round(service.base_price)) + ' ₽'">
                                                </div>
                                                <div class="text-sm text-gray-400 flex items-center gap-1.5 font-[Manrope]">
                                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span x-text="(service.levels?.[0]?.pivot?.duration || service.duration) + ' мин.'"></span>
                                                </div>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="Object.keys(specialistServices).length === 0">
                            <div class="text-center py-12 px-4 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm font-[Manrope]">У этого мастера пока нет назначенных услуг</p>
                                <p class="text-sm text-gray-400 mt-1 font-[Manrope]">Пожалуйста, выберите другого специалиста.</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Шаг 2.1: выбор мастера с учетом услуги --}}
                <div x-show="step === 'specialists_at_service'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform -translate-x-4"
                     class="absolute inset-x-0 top-24 px-8 pb-8 overflow-y-auto"
                     style="top: 80px;">
                    <div class="relative flex items-center justify-center mb-4">
                        <button @click="step = 'services'" class="absolute left-0 text-pink-500 hover:underline text-sm font-[Manrope]">Назад</button>
                        <h1 class="text-xl text-gray-800 font-[Playfair_Display]">Выберите мастера</h1>
                    </div>

                    <div class="max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(specialists, levelName) in serviceSpecialists" :key="levelName">
                            <div class="mb-6">
                                <h2 x-text="levelName === 'lead' ? 'Ведущий специалист' : (levelName === 'top' ? 'Топ-мастер' : (levelName === 'master' ? 'Мастер' : levelName))" class="text-sm text-pink-500 mb-3 ml-1 font-[Manrope]"></h2>
                                <div class="space-y-2">
                                    <template x-for="specialist in specialists" :key="specialist.id">
                                        <button @click="
                                                selectedSpecialist = specialist.id;
                                                specialistName = specialist.user.first_name + ' ' + (specialist.user.last_name || '');
                                                specialistLevel = levelName;
                                                specialistBio = specialist.bio || '';
                                                previousStep = 'specialists_at_service';
                                                step = 'calendar';
                                                loadSlots();"
                                                class="w-full flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:border-pink-300 hover:bg-pink-50/50 transition-all text-left">
                                            <div class="w-10 h-10 bg-pink-100 rounded-full flex-shrink-0 flex items-center justify-center text-pink-500 text-sm font-[Manrope]"></div>
                                            <div class="flex-1">
                                                <div class="font-normal text-gray-700 font-[Manrope]"
                                                     x-text="specialist.user.first_name + ' ' + (specialist.user.last_name || '')"></div>
                                                <div class="text-sm text-gray-400 font-[Manrope]" x-text="specialist.bio"></div>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="Object.keys(serviceSpecialists).length === 0">
                            <div class="text-center py-10">
                                <p class="text-gray-400 font-[Manrope]">К сожалению, для этой услуги сейчас нет свободных мастеров</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Шаг 4: Выбор даты и времени --}}
                <div x-show="step === 'calendar'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="absolute inset-0 px-8 pb-8 overflow-y-auto"
                     style="top: 0;">
                    <div class="relative pt-16">
                        <div class="sticky top-0 bg-white pt-4 pb-2 z-10">
                            <div class="relative flex items-center justify-center mb-5">
                                <button @click="step = previousStep" class="absolute left-0 text-pink-500 hover:underline text-sm font-[Manrope]">Назад</button>
                                <h1 class="text-xl text-gray-800 font-[Playfair_Display] font-normal">Дата и время</h1>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm   text-gray-400 px-1 font-[Manrope]">Доступные даты</label>
                            <div class="flex gap-2.5 overflow-x-auto py-2 px-1 custom-scrollbar">
                                @for($i = 0; $i < 30; $i++)
                                    @php $date = \Carbon\Carbon::today()->addDays($i); @endphp
                                    <button @click="selectedDate = '{{ $date->format('Y-m-d') }}'; loadSlots();"
                                            :class="selectedDate === '{{ $date->format('Y-m-d') }}' ? 'bg-pink-500 text-white shadow-lg shadow-pink-200 border-pink-500' : 'bg-pink-50/60 text-pink-600 border-pink-100 hover:bg-pink-100/70'"
                                            class="flex-shrink-0 w-16 py-3 rounded-2xl text-center transition-all duration-200 border font-[Manrope]">
                                        <div class="text-sm   opacity-80">{{ $date->translatedFormat('D') }}</div>
                                        <div class="text-2xl my-0.5 font-normal">{{ $date->format('d') }}</div>
                                        <div class="text-sm  tracking-widest opacity-90">{{ $date->translatedFormat('M') }}</div>
                                    </button>
                                @endfor
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 font-[Manrope] mt-4">
                            <div class="bg-gray-50/60 rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 bg-pink-500 rounded-xl flex-shrink-0 flex items-center justify-center text-white text-xl font-normal shadow-sm">
                                        <span x-text="specialistName ? specialistName[0] : 'M'"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm   text-gray-400 mb-0.5 font-[Manrope]">Выбранный мастер</div>
                                        <h3 class="text-gray-800 text-sm font-[Manrope]" x-text="specialistName"></h3>
                                    </div>
                                </div>
                                <span class="bg-pink-100 text-pink-600 text-sm px-2 py-1 rounded   flex-shrink-0 font-[Manrope]" x-text="specialistLevel === 'lead' ? 'Ведущий специалист' : (specialistLevel === 'top' ? 'Топ-мастер' : (specialistLevel === 'master' ? 'Мастер' : specialistLevel))"></span>
                            </div>

                            <div class="bg-gray-50/60 rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 bg-pink-100 text-pink-500 rounded-xl flex-shrink-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm   text-gray-400 mb-0.5 font-[Manrope]">Выбранная услуга</div>
                                        <h3 class="text-gray-800 text-sm font-[Manrope]" x-text="serviceName"></h3>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-sm text-pink-600 font-[Manrope]" x-text="servicePrice"></div>
                                    <div class="text-sm text-gray-400 flex items-center justify-end gap-1 mt-0.5 font-[Manrope]">
                                        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span x-text="'Длительность: ' + serviceDuration"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 font-[Manrope] m-4">
                            <label class="text-sm   text-gray-400 px-1 block">Доступные слоты</label>

                            <template x-if="loading">
                                <div class="flex justify-center py-12">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500"></div>
                                </div>
                            </template>

                            <div class="max-h-64 overflow-y-auto pr-1 custom-scrollbar" x-show="!loading">
                                <div class="grid grid-cols-4 gap-2.5">
                                    <template x-for="slot in availableSlots" :key="slot">
                                        <button @click="selectedTime = slot; step = 'final_check'"
                                                class="p-2.5 text-sm rounded-xl border border-pink-100 text-pink-600 bg-white hover:bg-pink-500 hover:text-white hover:border-pink-500 hover:-translate-y-0.5 hover:shadow-md hover:shadow-pink-100 transition-all duration-200 text-center font-[Manrope]">
                                            <span x-text="slot"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <template x-if="!loading && availableSlots.length === 0">
                                <div class="text-center py-10 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200 px-4">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm font-[Manrope]">На выбранную дату свободных мест нет</p>
                                    <p class="text-sm text-gray-400 mt-1 font-[Manrope]">Пожалуйста, попробуйте выбрать другой день на календаре выше.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Финальный шаг --}}
                <div x-show="step === 'final_check'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute inset-x-0 top-24 px-8 pb-8 overflow-y-auto custom-scrollbar"
                     style="top: 80px;">
                    <div class="max-h-96 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                        <div class="relative flex items-center justify-center mb-5">
                            <button @click="step = 'calendar'" class="absolute left-0 text-pink-500 hover:underline text-sm font-[Manrope]">Назад</button>
                            <h1 class="text-xl text-gray-800 font-[Playfair_Display] font-normal">Проверьте детали записи</h1>
                        </div>

                        <div class="bg-pink-50/50 border border-pink-100 rounded-2xl p-4 space-y-4 ">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-pink-500 rounded-xl text-white mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400 font-[Manrope]">Услуга</div>
                                    <div class="text-gray-800 text-base font-[Manrope]" x-text="serviceName"></div>
                                    <div class="text-sm text-gray-500 font-[Manrope]" x-text="'Длительность: ' + serviceDuration"></div>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-pink-500 rounded-xl text-white mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400 font-[Manrope]">Мастер</div>
                                    <div class="text-gray-800 text-base font-[Manrope]" x-text="specialistName"></div>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-pink-500 rounded-xl text-white mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400 font-[Manrope]">Дата и время</div>
                                    <div class="text-gray-800 text-base font-[Manrope]">
                                        <span x-text="new Date(selectedDate).toLocaleDateString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                                        в <span x-text="selectedTime"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 mt-4">
                            <span class="font-normal text-gray-600 font-[Manrope]">Итого к оплате:</span>
                            <span class="text-2xl text-pink-600 font-[Manrope]" x-text="servicePrice"></span>
                        </div>

                        <div class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-100 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <div>
                                    <h4 class="text-lg text-gray-800 font-[Manrope]">Зарегистрируйтесь и получите больше возможностей!</h4>
                                    <ul class="mt-1.5 space-y-1">
                                        <li class="text-sm text-gray-600 font-[Manrope] flex items-center gap-2">
                                            <span class="text-pink-500">✓</span> История всех записей в одном месте
                                        </li>
                                        <li class="text-sm text-gray-600 font-[Manrope] flex items-center gap-2">
                                            <span class="text-pink-500">✓</span> Напоминания о предстоящих визитах
                                        </li>
                                        <li class="text-sm text-gray-600 font-[Manrope] flex items-center gap-2">
                                            <span class="text-pink-500">✓</span> Возможность оставить отзыв после услуги
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2.5">
                            @auth
                                <button @click="
                                    loading = true;
                                    fetch('/api/appointments', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
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
                                        class="w-full bg-pink-500 hover:bg-pink-600 text-white p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center disabled:opacity-50 font-[Manrope]">
                                    <span x-show="!loading">Подтвердить запись</span>
                                    <span x-show="loading">Оформление записи...</span>
                                </button>
                            @else

                                <button @click="step = 'guest_form'"
                                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 p-4 rounded-xl transition-all text-center font-[Manrope] border border-gray-200">
                                    Записаться без аккаунта
                                </button>


                                <a href="{{ route('login') }}"
                                   class="block w-full bg-pink-500 hover:bg-gray-900 text-white p-4 rounded-xl text-center transition-all font-[Manrope]">
                                    Войти в аккаунт
                                </a>

                            @endauth
                        </div>
                    </div>

                </div>

                <!-- Форма для гостя -->
                <div x-show="step === 'guest_form'"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute inset-x-0 top-24 px-8 pb-8 overflow-y-auto scrollbar-thin scrollbar-thumb-pink-300 scrollbar-track-transparent"
                     style="top: 80px; max-height: calc(100vh - 160px);">
                    <div class="max-h-96 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                        <div class="relative flex items-center justify-center mb-5">
                            <button @click="step = 'final_check'" class="absolute left-0 text-pink-500 hover:underline text-sm font-[Manrope]">Назад</button>
                            <h1 class="text-xl text-gray-800 font-[Playfair_Display] font-normal">Введите ваши данные</h1>
                        </div>

                        <div class=" rounded-2xl p-6 space-y-4">
                            <p class="text-sm text-gray-500 font-[Manrope] text-center mb-2">
                                Заполните поля для создания записи
                            </p>

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">Имя <span class="text-pink-500">*</span></label>
                                <input type="text"
                                       x-model="guestName"
                                       @input="guestNameError = ''"
                                       placeholder="Введите ваше имя"
                                       class="w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                                       :class="guestNameError ? 'border-red-500 focus:border-red-500' : ''">
                                <p class="text-xs text-red-500 mt-1" x-show="guestNameError" x-text="guestNameError"></p>
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">Фамилия</label>
                                <input type="text"
                                       x-model="guestLastName"
                                       placeholder="Введите вашу фамилию"
                                       class="w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">Номер телефона <span class="text-pink-500">*</span></label>
                                <input type="tel"
                                       x-model="guestPhone"
                                       @input="
                                       guestPhone = guestPhone.replace(/[^0-9+]/g, '');
                                       if (guestPhone.length > 0 && !guestPhone.startsWith('+79')) {
                                           guestPhone = '+79' + guestPhone.replace(/[^0-9]/g, '').slice(0, 9);
                                       }
                                       if (guestPhone.length > 12) {
                                           guestPhone = guestPhone.slice(0, 12);
                                       }
                                       guestPhoneError = '';
                                   "
                                       placeholder="+7 (999) 000-00-00"
                                       class="w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                                       :class="guestPhoneError ? 'border-red-500 focus:border-red-500' : ''">
                                <p class="text-xs text-red-500 mt-1" x-show="guestPhoneError" x-text="guestPhoneError"></p>
                                <p class="text-xs text-gray-400 mt-1 font-light">Формат: +79XXXXXXXXX (11 цифр)</p>
                            </div>

                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 mt-2">
                                <span class="font-normal text-gray-600 font-[Manrope]">Итого к оплате:</span>
                                <span class="text-2xl text-pink-600 font-[Manrope]" x-text="servicePrice"></span>
                            </div>

                            <button @click="
    let hasError = false;

    if (!guestName || !guestName.trim()) {
        guestNameError = 'Имя обязательно для заполнения';
        hasError = true;
    } else {
        guestNameError = '';
    }

    if (!guestPhone || guestPhone.length < 12 || !guestPhone.startsWith('+79')) {
        guestPhoneError = 'Введите корректный номер телефона (например: +79001234567)';
        hasError = true;
    } else {
        guestPhoneError = '';
    }

    if (hasError) return;

    loading = true;
    console.log('Отправляю запрос...');
    console.log('Данные:', {
        specialist_id: selectedSpecialist,
        service_id: selectedService,
        date: selectedDate,
        time: selectedTime,
        guest_name: guestName,
        guest_last_name: guestLastName,
        guest_phone: guestPhone
    });

    fetch('/api/appointments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            specialist_id: selectedSpecialist,
            service_id: selectedService,
            date: selectedDate,
            time: selectedTime,
            guest_name: guestName,
            guest_last_name: guestLastName,
            guest_phone: guestPhone
        })
    })
    .then(res => {
        console.log('Статус ответа:', res.status);
        console.log('Заголовки:', res.headers);
        return res.json();
    })
    .then(data => {
        console.log('Данные от сервера:', data);
        if (!data.success) {
            throw new Error(data.message || 'Ошибка записи');
        }
        loading = false;
        alert('✅ Вы успешно записались! Мы свяжемся с вами для подтверждения.');
        $store.modalManager.closeBooking();
    })
    .catch(e => {
        loading = false;
        console.error('Полная ошибка:', e);
        alert('❌ Ошибка: ыува' + e.message);
    });
"
                                    :disabled="loading"
                                    class="w-full bg-pink-500 hover:bg-pink-600 text-white p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center disabled:opacity-50 font-[Manrope]">
                                <span x-show="!loading">Записаться</span>
                                <span x-show="loading">Оформление записи...</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
