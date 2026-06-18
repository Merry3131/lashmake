<x-guest-layout>
    <div class="flex flex-col h-full justify-between">
        <div class="overflow-y-auto">
            <!-- Кнопка назад -->
            <div class="mb-3">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 text-pink-500 hover:text-pink-600 transition-colors duration-300 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    На главную
                </a>
            </div>

            <div class="text-center mb-4 pt-1">
                <h2 class="text-xl font-normal text-[#1e1f22] tracking-wide uppercase mb-0.5">Регистрация</h2>
                <p class="text-xs text-[#7c7e8c] font-light">Создайте личный кабинет для управления записями</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-2"
                  x-data="{
                    first_name: '{{ old('first_name') }}',
                    last_name: '{{ old('last_name') }}',
                    email: '{{ old('email') }}',
                    phone: '{{ old('phone') }}',
                    emailError: '',
                    phoneError: '',
                    passwordError: '',
                    password: '',
                    passwordConfirm: '',
                    serverErrors: {}
                  }"
                  @submit.prevent="
                    let hasError = false;

                    if (email && !email.includes('@')) {
                        emailError = 'Введите корректный email с символом @';
                        hasError = true;
                    } else {
                        emailError = '';
                    }

                    if (phone.length > 0 && (!phone.startsWith('+79') || phone.length < 12)) {
                        phoneError = phone.length < 12 ? 'Номер должен содержать 11 цифр (например: +79001234567)' : 'Номер должен начинаться с +79';
                        hasError = true;
                    } else {
                        phoneError = '';
                    }

                    if (password && passwordConfirm && password !== passwordConfirm) {
                        passwordError = 'Пароли не совпадают';
                        hasError = true;
                    } else if (password && password.length < 8) {
                        passwordError = 'Пароль должен содержать минимум 8 символов';
                        hasError = true;
                    } else {
                        passwordError = '';
                    }

                    if (!hasError) {
                        document.getElementById('register-form').submit();
                    }
                  ">
                @csrf

                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <label for="first_name" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-0.5">Имя</label>
                        <input id="first_name"
                               class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2.5 transition-colors duration-200 outline-none"
                               :class="serverErrors.first_name ? 'border-red-500 focus:border-red-500' : ''"
                               type="text"
                               name="first_name"
                               x-model="first_name"
                               required
                               autofocus />
                        <p class="text-xs text-red-500 mt-0.5" x-show="serverErrors.first_name" x-text="serverErrors.first_name"></p>
                    </div>

                    <div>
                        <label for="last_name" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-0.5">Фамилия</label>
                        <input id="last_name"
                               class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2.5 transition-colors duration-200 outline-none"
                               :class="serverErrors.last_name ? 'border-red-500 focus:border-red-500' : ''"
                               type="text"
                               name="last_name"
                               x-model="last_name"
                               required />
                        <p class="text-xs text-red-500 mt-0.5" x-show="serverErrors.last_name" x-text="serverErrors.last_name"></p>
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-0.5">Номер телефона</label>
                    <input id="phone"
                           class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2.5 transition-colors duration-200 outline-none"
                           :class="phoneError || serverErrors.phone ? 'border-red-500 focus:border-red-500' : ''"
                           type="tel"
                           name="phone"
                           x-model="phone"
                           @input="
                               phone = phone.replace(/[^0-9+]/g, '');
                               if (phone.length > 0 && !phone.startsWith('+79')) {
                                   phone = '+79' + phone.replace(/[^0-9]/g, '').slice(0, 9);
                               }
                               if (phone.length > 12) {
                                   phone = phone.slice(0, 12);
                               }
                               phoneError = '';
                               serverErrors.phone = '';
                           "
                           required
                           placeholder="+7 (999) 000-00-00" />
                    <p class="text-xs text-red-500 mt-0.5" x-show="phoneError" x-text="phoneError"></p>
                    <p class="text-xs text-red-500 mt-0.5" x-show="serverErrors.phone" x-text="serverErrors.phone"></p>
                </div>

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-0.5">Email</label>
                    <input id="email"
                           class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2.5 transition-colors duration-200 outline-none"
                           :class="emailError || serverErrors.email ? 'border-red-500 focus:border-red-500' : ''"
                           type="email"
                           name="email"
                           x-model="email"
                           @input="emailError = ''; serverErrors.email = '';"
                           required />
                    <p class="text-xs text-red-500 mt-0.5" x-show="emailError" x-text="emailError"></p>
                    <p class="text-xs text-red-500 mt-0.5" x-show="serverErrors.email" x-text="serverErrors.email"></p>
                </div>

                <div>
                    <label for="password" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-0.5">Пароль</label>
                    <input id="password"
                           class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2.5 transition-colors duration-200 outline-none"
                           :class="passwordError || serverErrors.password ? 'border-red-500 focus:border-red-500' : ''"
                           type="password"
                           name="password"
                           x-model="password"
                           @input="passwordError = ''; serverErrors.password = '';"
                           required
                           autocomplete="new-password" />
                    <p class="text-xs text-red-500 mt-0.5" x-show="passwordError" x-text="passwordError"></p>
                    <p class="text-xs text-red-500 mt-0.5" x-show="serverErrors.password" x-text="serverErrors.password"></p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-0.5">Подтверждение</label>
                    <input id="password_confirmation"
                           class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2.5 transition-colors duration-200 outline-none"
                           :class="passwordError || serverErrors.password ? 'border-red-500 focus:border-red-500' : ''"
                           type="password"
                           name="password_confirmation"
                           x-model="passwordConfirm"
                           @input="passwordError = ''; serverErrors.password = '';"
                           required
                           autocomplete="new-password" />
                    <p class="text-xs text-red-500 mt-0.5" x-show="passwordError" x-text="passwordError"></p>
                </div>

                <!-- Скрытое поле для передачи ошибок с сервера -->
                @if ($errors->any())
                    <div x-data="{
                        init() {
                            let errors = {};
                            @foreach ($errors->toArray() as $field => $messages)
                                errors['{{ $field }}'] = '{{ implode(', ', $messages) }}';
                            @endforeach
                            this.$parent.serverErrors = errors;

                            // Прокрутить к первому полю с ошибкой
                            let firstErrorField = Object.keys(errors)[0];
                            if (firstErrorField) {
                                let element = document.getElementById(firstErrorField);
                                if (element) {
                                    element.focus();
                                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }
                        }
                    }"></div>
                @endif
            </form>
        </div>

        <div class="pt-3 pb-1 space-y-2.5 flex-shrink-0">
            <button type="submit"
                    form="register-form"
                    class="w-full bg-[#ff5c8a] text-white rounded-xl py-3 text-sm tracking-wider uppercase font-normal hover:bg-[#e04b75] transition-all duration-300 hover:cursor-pointer text-center shadow-sm">
                Зарегистрироваться
            </button>

            <div class="text-center">
                <a class="text-xs text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors" href="{{ route('login') }}">
                    Уже зарегистрированы? <span class="font-normal border-b border-gray-300 hover:border-[#ff5c8a]">Войти</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
