<x-guest-layout>
    <div class="flex flex-col h-full justify-between">
        <div>
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

            <div class="text-center mb-8 pt-4">
                <h2 class="text-2xl font-normal text-[#1e1f22] tracking-wide uppercase mb-2">Войти в кабинет</h2>
                <p class="text-sm text-[#7c7e8c] font-light">Добро пожаловать в студию идеального взгляда</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5" x-data="{ email: '{{ old('email') }}', emailError: false }">
                @csrf
                <div>
                    <label for="email" class="block text-sm uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">Email</label>
                    <input id="email"
                           class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                           :class="emailError ? 'border-red-500 focus:border-red-500' : ''"
                           type="email"
                           name="email"
                           x-model="email"
                           @input="emailError = false"
                           required
                           autofocus
                           autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    <p class="text-sm text-red-500 mt-1" x-show="emailError" x-text="email && !email.includes('@') ? 'Введите корректный email с символом @' : ''"></p>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm uppercase tracking-wider text-[#7c7e8c] font-medium">Пароль</label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors" href="{{ route('password.request') }}">
                                Забыли пароль?
                            </a>
                        @endif
                    </div>
                    <input id="password" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                           type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

            </form>
        </div>

        <div class="pt-6 pb-2 space-y-4">
            <button type="submit"
                    form="login-form"
                    class="w-full bg-[#ff5c8a] text-white rounded-xl py-3.5 text-sm tracking-wider uppercase font-normal hover:bg-[#e04b75] transition-all duration-300 hover:cursor-pointer text-center shadow-sm"
                    @click.prevent="
                        if (email && !email.includes('@')) {
                            emailError = true;
                            document.getElementById('email').focus();
                        } else {
                            document.getElementById('login-form').submit();
                        }
                    ">
                Войти в аккаунт
            </button>

            <div class="text-center pt-1">
                <a class="text-sm text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors" href="{{ route('register') }}">
                    Еще нет аккаунта? <span class="font-normal border-b border-gray-300 hover:border-[#ff5c8a]">Зарегистрироваться</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
