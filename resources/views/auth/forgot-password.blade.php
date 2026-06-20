    <x-guest-layout>
    <div class="flex flex-col h-full justify-between">
        <div>
            <div class="text-center mb-8 pt-4">
                <h2 class="text-2xl font-normal text-[#1e1f22] tracking-wide uppercase mb-2">Восстановление пароля</h2>
                <p class="text-xs text-[#7c7e8c] font-light">Введите email для получения ссылки сброса</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">Email</label>
                    <input id="email" class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none"
                           type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <button type="submit" class="w-full bg-[#ff5c8a] text-white rounded-xl py-3.5 text-xs tracking-wider uppercase font-normal hover:bg-[#e04b75] transition-all duration-300 hover:cursor-pointer text-center shadow-sm">
                    Отправить ссылку для сброса
                </button>

                <div class="text-center pt-4">
                    <a class="text-xs text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors" href="{{ route('login') }}">
                        Вернуться ко входу
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
