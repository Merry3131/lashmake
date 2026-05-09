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
                    <h2 class="text-xl font-bold text-gray-800 mb-6">С возвращением! 🌸</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <x-input-label for="login_email" value="Email" />
                            <x-text-input id="login_email" class="block mt-1 w-full" type="email" name="email" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="login_password" value="Пароль" />
                            <x-text-input id="login_password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
                        <div class="mt-6">
                            <x-primary-button class="w-full justify-center bg-pink-500 hover:bg-pink-600 shadow-lg shadow-pink-200">
                                Войти
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div x-show="tab === 'register'" x-transition>
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Стать клиентом ✨</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div>
                            <x-input-label for="reg_name" value="Имя" />
                            <x-text-input id="reg_name" class="block mt-1 w-full" type="text" name="first_name" required />
                        </div>
                        <div>
                            <x-input-label for="reg_last_name" value="Фамилия" />
                            <x-text-input id="reg_last_name" class="block mt-1 w-full" type="text" name="last_name" required />
                        </div>
                        <div>
                            <x-input-label for="reg_phone" value="Номер телефона" />
                            <x-text-input id="reg_phone" class="block mt-1 w-full" type="text" name="phone" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="reg_email" value="Email" />
                            <x-text-input id="reg_email" class="block mt-1 w-full" type="email" name="email" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="reg_password" value="Пароль" />
                            <x-text-input id="reg_password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" value="Подтвердите пароль" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        </div>
                        @if($errors->any())
                            <div class="mb-4 text-red-600 text-sm">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mt-6">
                            <x-primary-button class="w-full justify-center bg-pink-500 hover:bg-pink-600 shadow-lg shadow-pink-200">
                                Зарегистрироваться
                            </x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
