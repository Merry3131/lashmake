<div class="space-y-6">
    <div>
        <h2 class="text-xl font-normal text-[#1e1f22] font-[Playfair_Display] tracking-wide">
            Данные пользователя
        </h2>
        <p class="mt-1 text-sm text-[#7c7e8c] font-light font-[Manrope]">
            Обновите данные вашей учетной записи.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="first_name" class="block text-sm tracking-wider text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Имя
            </label>
            <input id="first_name" name="first_name" type="text"
                   value="{{ old('first_name', $user->first_name) }}"
                   required autofocus autocomplete="name"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]" />
            @if($errors->get('first_name'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->first('first_name') }}</p>
            @endif
        </div>

        <div>
            <label for="last_name" class="block text-sm tracking-wider text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Фамилия
            </label>
            <input id="last_name" name="last_name" type="text"
                   value="{{ old('last_name', $user->last_name) }}"
                   required autofocus autocomplete="name"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]" />
            @if($errors->get('last_name'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->first('last_name') }}</p>
            @endif
        </div>

        <div>
            <label for="phone" class="block text-sm tracking-wider text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Номер телефона
            </label>
            <input id="phone" name="phone" type="tel"
                   value="{{ old('phone', $user->phone) }}"
                   required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]" />
            @if($errors->get('phone'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->first('phone') }}</p>
            @endif
        </div>

        <div>
            <label for="email" class="block   text-[#7c7e8c] mb-1.5 font-[Manrope]">
                Электронная почта
            </label>
            <input id="email" name="email" type="email"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="username"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-[Manrope]" />
            @if($errors->get('email'))
                <p class="mt-1.5 text-sm text-rose-500 font-light">{{ $errors->first('email') }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-amber-600 font-light">
                        Ваш адрес электронной почты не подтвержден.
                        <button form="send-verification" class="text-pink-500 hover:text-pink-600 underline font-medium">
                            Нажмите здесь, чтобы повторно отправить письмо с подтверждением.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-emerald-600 font-light">
                            На ваш электронный адрес отправлена новая ссылка для подтверждения.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="px-6 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md tracking-wide text-sm cursor-pointer">
                Сохранить
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-emerald-600 font-light">
                    Сохранено
                </p>
            @endif
        </div>
    </form>
</div>
