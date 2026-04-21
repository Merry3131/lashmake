<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-pink-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-sm">L</div>
                        <span class="text-2xl font-extrabold tracking-tight text-gray-800">LASH<span class="text-pink-500">MAKE</span></span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Главная
                    </x-nav-link>
                    <x-nav-link :href="url('/services')" :active="request()->is('services*')">
                        Услуги
                    </x-nav-link>
                    <x-nav-link :href="url('/team')" :active="request()->is('master*')">
                        Специалисты
                    </x-nav-link>
                    <x-nav-link :href="url('/reviews')" :active="request()->is('reviews*')">
                        Отзывы
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-pink-200 text-sm leading-4 font-medium rounded-full text-gray-600 bg-pink-50 hover:bg-pink-100 hover:text-pink-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->first_name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 text-pink-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                Личный кабинет
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                Профиль
                            </x-dropdown-link>
                            <hr class="border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span class="text-red-500">Выйти</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <button @click="$dispatch('open-auth-modal')" class="inline-flex items-center px-4 py-2 bg-pink-500 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-600 focus:bg-pink-600 active:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            Вход
                        </button>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Главная</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/services')">Услуги</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/master')">Мастера</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/reviews')">Отзывы</x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 bg-pink-50">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->first_name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')">Личный кабинет</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">Профиль</x-responsive-nav-link>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <x-responsive-nav-link :href="route('login')">Войти</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">Регистрация</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
