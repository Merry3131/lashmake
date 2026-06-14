<x-guest-layout>
    <div class="flex flex-col h-full justify-between">
        <div>
            <div class="text-center mb-5 pt-2">
                <h2 class="text-2xl font-normal text-[#1e1f22] tracking-wide uppercase mb-1">Регистрация</h2>
                <p class="text-xs text-[#7c7e8c] font-light">Создайте личный кабинет для управления записями</p>
            </div>

            <form method="POST" action="{{ route('register') }} " id="register-form" class="space-y-2.5">
                @csrf

                <div>
                    <label for="first_name" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1">Имя</label>
                    <input id="first_name" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2 transition-colors duration-200 outline-none"
                           type="text" name="first_name" :value="old('first_name')" required autofocus />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-0.5" />
                </div>

                <div>
                    <label for="last_name" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1">Фамилия</label>
                    <input id="last_name" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2 transition-colors duration-200 outline-none"
                           type="text" name="last_name" :value="old('last_name')" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-0.5" />
                </div>

                <div>
                    <label for="phone" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1">Номер телефона</label>
                    <input id="phone" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2 transition-colors duration-200 outline-none"
                           type="text" name="phone" :value="old('phone')" required placeholder="+7 (999) 000-00-00" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-0.5" />
                </div>

                <div>
                    <label for="email" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1">Email</label>
                    <input id="email" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2 transition-colors duration-200 outline-none"
                           type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-0.5" />
                </div>

                <div>
                    <label for="password" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1">Пароль</label>
                    <input id="password" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2 transition-colors duration-200 outline-none"
                           type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-0.5" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1">Подтверждение</label>
                    <input id="password_confirmation" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-2 transition-colors duration-200 outline-none"
                           type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-0.5" />
                </div>
            </form>
        </div>

        <div class="pt-4 pb-2 space-y-3">
            <button type="submit" form="register-form" class="w-full bg-[#ff5c8a] text-white rounded-xl py-3 text-xs tracking-wider uppercase font-normal hover:bg-[#e04b75] transition-all duration-300 hover:cursor-pointer text-center shadow-sm">
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
