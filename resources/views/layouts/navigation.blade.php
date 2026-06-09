<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="text-2xl tracking-tight text-gray-800">Центр<span class="text-pink-500"> Ресниц</span></span>
                </a>
            </div>

            <div class="hidden sm:flex sm:items-center space-x-10">

                <div class="flex space-x-8 sm:-my-px">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Главная
                    </x-nav-link>
                    <x-nav-link :href="url('/services')" :active="request()->is('services*')">
                        Услуги
                    </x-nav-link>
                    <x-nav-link :href="url('/team')" :active="request()->is('team*')">
                        Специалисты
                    </x-nav-link>
                    <x-nav-link :href="url('/reviews')" :active="request()->is('reviews*')">
                        Отзывы
                    </x-nav-link>
                    <x-nav-link :href="url('/example_of_works')" :active="request()->is('example_of_works*')">
                        Примеры работ
                    </x-nav-link>
                </div>

                <div class="flex items-center">
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
                                @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                                    <x-dropdown-link :href="route('admin.dashboard')">
                                        Админ-панель
                                    </x-dropdown-link>
                                @endif
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
                            <button @click="$dispatch('open-auth-modal')" class="inline-flex items-center px-5 py-2 bg-pink-500 border border-transparent rounded-full font-light text-sm text-white tracking-wide hover:bg-pink-600 transition ease-in-out duration-150 shadow-sm">
                                Вход
                            </button>
                        </div>
                    @endauth
                </div>

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
            <x-responsive-nav-link :href="url('/team')">Мастера</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/example_of_works')">Примеры работ</x-responsive-nav-link>
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
                <x-responsive-nav-link as="button" @click="$dispatch('open-register-modal')" class="cursor-pointer">{{ __('Регистрация') }}</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
