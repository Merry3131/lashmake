<div class="space-y-6">
    <div>
        <h2 class="text-xl font-normal text-[#1e1f22] font-[Playfair_Display] tracking-wide">
            Изменить пароль
        </h2>
        <p class="mt-1 text-sm text-[#7c7e8c] font-light font-[Manrope]">
            Для обеспечения безопасности убедитесь, что ваша учетная запись использует длинный, случайный пароль.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm  text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Текущий пароль
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]"
                   autocomplete="current-password" />
            @if($errors->updatePassword->get('current_password'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm  text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Новый пароль
            </label>
            <input id="update_password_password" name="password" type="password"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]"
                   autocomplete="new-password" />
            @if($errors->updatePassword->get('password'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm  text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Подтвердите пароль
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]"
                   autocomplete="new-password" />
            @if($errors->updatePassword->get('password_confirmation'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="px-6 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md tracking-wide text-sm cursor-pointer">
                Сохранить
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-emerald-600 font-light">
                    Сохранено.
                </p>
            @endif
        </div>
    </form>
</div>
