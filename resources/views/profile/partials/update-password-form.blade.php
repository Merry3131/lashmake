<section class="font-['Rubik'] text-[#1e1f22]">
    {{-- Карточка изменения пароля в едином стиле с профилем --}}
    <div class="bg-white rounded-3xl border border-[#f1f1f5] p-6 hover:shadow-sm transition-all duration-300 max-w-xl text-left">

        {{-- Заголовок блока --}}
        <div class="pb-4 border-b border-gray-100 mb-6">
            <h2 class="text-xl font-normal text-[#1e1f22] uppercase tracking-wider">
                {{ __('Изменить пароль') }}
            </h2>
            <p class="mt-1 text-xs text-[#7c7e8c] font-light leading-relaxed">
                {{ __('Для обеспечения безопасности убедитесь, что ваша учетная запись использует длинный, случайный пароль.') }}
            </p>
        </div>

        {{-- Форма отправки --}}
        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            {{-- Поле: Текущий пароль --}}
            <div>
                <label for="update_password_current_password" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                    {{ __('Текущий пароль') }}
                </label>
                <input id="update_password_current_password" name="current_password" type="password"
                       class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light"
                       autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
            </div>

            {{-- Поле: Новый пароль --}}
            <div>
                <label for="update_password_password" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                    {{ __('Новый пароль') }}
                </label>
                <input id="update_password_password" name="password" type="password"
                       class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light"
                       autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            {{-- Поле: Подтвердите пароль --}}
            <div>
                <label for="update_password_password_confirmation" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                    {{ __('Подтвердите пароль') }}
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                       class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light"
                       autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>

            {{-- Блок кнопки сохранения и статуса --}}
            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="px-8 py-3 bg-[#ff5c8a] text-white rounded-xl text-xs tracking-wider uppercase font-normal hover:bg-[#e04b75] transition-all duration-300 hover:cursor-pointer text-center shadow-sm">
                    {{ __('Сохранить') }}
                </button>

                {{-- Анимация успешного сохранения из оригинала --}}
                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-xs text-emerald-600 font-light"
                    >{{ __('Сохранено.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>
