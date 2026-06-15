
<div x-data="{
    open: false,
    tab: 'login',
    forgotMode: false
}"
     @open-auth-modal.window="open = true; tab = 'login'; forgotMode = false"
     @open-register-modal.window="open = true; tab = 'register'; forgotMode = false">

    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-[60] flex items-center justify-center p-4 backdrop-blur-sm">

        <div @click.away="open = false"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative">

            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-pink-500 transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="flex border-b border-gray-100" x-show="!forgotMode">
                <button @click="tab = 'login'; forgotMode = false"
                        :class="tab === 'login' ? 'text-pink-500 border-pink-500 bg-pink-50/50' : 'text-gray-400 border-transparent hover:text-gray-600'"
                        class="flex-1 py-4 text-center font-bold text-sm uppercase tracking-widest border-b-2 transition-all font-[Manrope]">
                    Вход
                </button>
                <button @click="tab = 'register'; forgotMode = false"
                        :class="tab === 'register' ? 'text-pink-500 border-pink-500 bg-pink-50/50' : 'text-gray-400 border-transparent hover:text-gray-600'"
                        class="flex-1 py-4 text-center font-bold text-sm uppercase tracking-widest border-b-2 transition-all font-[Manrope]">
                    Регистрация
                </button>
            </div>

            <div class="p-4 pb-0" x-show="forgotMode">
                <button @click="forgotMode = false" class="text-gray-500 hover:text-pink-500 transition flex items-center gap-2 text-sm font-[Manrope]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Назад к входу
                </button>
            </div>

            <div class="p-8 min-h-[400px] relative">
                <!-- Форма входа -->
                <div x-show="tab === 'login' && !forgotMode"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="absolute inset-0 p-8 overflow-y-auto">

                    <h2 class="text-2xl text-gray-800 text-center font-[Playfair_Display] font-normal tracking-wide">С возвращением!</h2>
                    @if($errors->any())
                        <div class="mb-4 mt-2 text-rose-500 text-xs pl-1">
                            <ul class="list-disc pl-4 space-y-0.5 font-[Manrope]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="login_email" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Email</label>
                            <input id="login_email" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="email" name="email" required pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Пожалуйста, введите корректный email, содержащий символ @" placeholder="Email должен содержать символ '@'"/>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label for="login_password" class="block text-xs font-bold uppercase tracking-wide text-gray-400 pl-1 font-[Manrope]" >Пароль</label>
                            </div>
                            <input id="login_password" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="password" name="password" required placeholder="Пароль должен содержать не менее 5 символов"/>
                        </div>

                        <div class="pt-14">
                            <button type="submit" class="w-full  bg-pink-500 hover:bg-pink-600 text-white font-bold p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center text-sm tracking-wide uppercase font-[Manrope]">
                                Войти
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Форма регистрации -->
                <div x-show="tab === 'register' && !forgotMode"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="absolute inset-0 p-8 overflow-y-auto">
                    <h2 class="text-2xl text-gray-800 mb-6 text-center font-[Playfair_Display] font-normal tracking-wide">Стать клиентом</h2>
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="reg_name" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Имя</label>
                            <input id="reg_name" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="text" name="first_name" required />
                        </div>
                        <div>
                            <label for="reg_last_name" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Фамилия</label>
                            <input id="reg_last_name" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="text" name="last_name" required />
                        </div>
                        <div>
                            <label for="reg_phone" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Номер телефона</label>
                            <input id="reg_phone" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="tel" name="phone" required pattern="\+79\d{9}" title="Введите номер телефона в формате: +79342342133 (11 цифр после +79)" maxlength="12" x-data x-init="() => { $el.value = '+79'; }" x-on:input="() => { let value = $el.value.replace(/[^\d]/g, ''); if (!value.startsWith('79')) value = '79' + value.slice(2); value = '+79' + value.slice(2, 11); $el.value = value; }"  />
                        </div>
                        <div>
                            <label for="reg_email" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Email</label>
                            <input id="reg_email" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="email" name="email" required pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Пожалуйста, введите корректный email, содержащий символ @" placeholder="Email должен содержать символ '@'"/>
                        </div>
                        <div>
                            <label for="reg_password" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Пароль</label>
                            <input id="reg_password" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="password" name="password" required minlength="6" title="Пароль должен содержать не менее 6 символов" placeholder="Пароль должен содержать не менее 5 символов"/>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1 font-[Manrope]">Подтвердите пароль</label>
                            <input id="password_confirmation" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none font-[Manrope]" type="password" name="password_confirmation" required minlength="6" />
                        </div>

                        @if($errors->any())
                            <div class="mb-4 text-rose-500 text-xs pl-1">
                                <ul class="list-disc pl-4 space-y-0.5 font-[Manrope]">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center text-sm tracking-wide uppercase font-[Manrope]">
                                Зарегистрироваться
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
