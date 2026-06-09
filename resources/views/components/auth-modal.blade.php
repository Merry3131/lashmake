<div x-data="{
    open: false,
    tab: 'login'
}"
     @open-auth-modal.window="open = true; tab = 'login'"
     @open-register-modal.window="open = true; tab = 'register'">

    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-[60] flex items-center justify-center p-4 backdrop-blur-sm">

        <div @click.away="open = false"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative">

            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-pink-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="flex border-b border-gray-100">
                <button @click="tab = 'login'"
                        :class="tab === 'login' ? 'text-pink-500 border-pink-500 bg-pink-50/50' : 'text-gray-400 border-transparent hover:text-gray-600'"
                        class="flex-1 py-4 text-center font-bold text-sm uppercase tracking-widest border-b-2 transition-all">
                    Вход
                </button>
                <button @click="tab = 'register'"
                        :class="tab === 'register' ? 'text-pink-500 border-pink-500 bg-pink-50/50' : 'text-gray-400 border-transparent hover:text-gray-600'"
                        class="flex-1 py-4 text-center font-bold text-sm uppercase tracking-widest border-b-2 transition-all">
                    Регистрация
                </button>
            </div>

            <div class="p-8">
                <div x-show="tab === 'login'" x-transition>
                    <h2 class="text-xl text-gray-800 mb-6 text-center">С возвращением!</h2>
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="login_email" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Email</label>
                            <input id="login_email" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="email" name="email" required />
                        </div>
                        <div>
                            <label for="login_password" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Пароль</label>
                            <input id="login_password" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="password" name="password" required />
                        </div>
                        <div class="pt-2">
                            <button class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center text-sm tracking-wide uppercase">
                                Войти
                            </button>
                        </div>
                    </form>
                </div>

                <div x-show="tab === 'register'" x-transition>
                    <h2 class="text-xl  text-gray-800 mb-6 text-center">Стать клиентом</h2>
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="reg_name" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Имя</label>
                            <input id="reg_name" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="text" name="first_name" required />
                        </div>
                        <div>
                            <label for="reg_last_name" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Фамилия</label>
                            <input id="reg_last_name" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="text" name="last_name" required />
                        </div>
                        <div>
                            <label for="reg_phone" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Номер телефона</label>
                            <input id="reg_phone" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="text" name="phone" required />
                        </div>
                        <div>
                            <label for="reg_email" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Email</label>
                            <input id="reg_email" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="email" name="email" required />
                        </div>
                        <div>
                            <label for="reg_password" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Пароль</label>
                            <input id="reg_password" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="password" name="password" required />
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5 pl-1">Подтвердите пароль</label>
                            <input id="password_confirmation" class="block w-full p-3.5 bg-gray-50 border border-gray-100 focus:border-pink-300 focus:bg-white focus:ring-1 focus:ring-pink-300 rounded-xl transition-all text-sm text-gray-700 outline-none" type="password" name="password_confirmation" required />
                        </div>

                        @if($errors->any())
                            <div class="mb-4 text-rose-500 text-xs pl-1">
                                <ul class="list-disc pl-4 space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="pt-2">
                            <button class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold p-4 rounded-xl shadow-lg shadow-pink-200 transition-all text-center text-sm tracking-wide uppercase">
                                Зарегистрироваться
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
