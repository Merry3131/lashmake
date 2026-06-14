<x-guest-layout>
    <div class="flex flex-col h-full justify-between">
        <div>
            <div class="text-center mb-8 pt-4">
                <h2 class="text-2xl font-normal text-[#1e1f22] tracking-wide uppercase mb-2">Войти в кабинет</h2>
                <p class="text-xs text-[#7c7e8c] font-light">Добро пожаловать в студию идеального взгляда</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">Email</label>
                    <input id="email" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium">Пароль</label>
                        @if (Route::has('password.request'))
                            <a class="text-[11px] text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors" href="{{ route('password.request') }}">
                                Забыли пароль?
                            </a>
                        @endif
                    </div>
                    <input id="password" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                           type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="block pt-1">
                    <label for="remember_me" class="inline-flex items-center hover:cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-[#f1f1f5] text-[#ff5c8a] focus:ring-[#ff5c8a]/20 w-4 h-4" name="remember">
                        <span class="ms-2 text-xs text-[#7c7e8c] font-light">Запомнить меня на этом устройстве</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="pt-6 pb-2 space-y-4">
            <button type="submit" form="login-form" class="w-full bg-[#ff5c8a] text-white rounded-xl py-3.5 text-xs tracking-wider uppercase font-normal hover:bg-[#e04b75] transition-all duration-300 hover:cursor-pointer text-center shadow-sm">
                Войти в аккаунт
            </button>

            <div class="text-center pt-1">
                <a class="text-xs text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors" href="{{ route('register') }}">
                    Еще нет аккаунта? <span class="font-normal border-b border-gray-300 hover:border-[#ff5c8a]">Зарегистрироваться</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
