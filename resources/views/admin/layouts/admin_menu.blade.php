<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Center of Lashes Admin')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body class="bg-[#fafafc] font-['Manrope'] text-[#1e1f22]">

<div class="flex h-screen overflow-hidden">

    <div class="w-64 bg-white text-[#1e1f22] flex flex-col shrink-0 border-r border-[#f1f1f5]">

        <div class="p-6 text-lg font-normal tracking-wider  text-[#1e1f22] border-b border-[#f1f1f5] font-[Playfair_Display]">
            Центр ресниц <br>
            Админ-панель
        </div>


        <nav class="flex-1 p-4 space-y-6 overflow-y-auto">


            <div>
                <span class="px-4 text-sm font-semibold text-pink-500  tracking-widest block mb-2.5">
                    Операционная работа
                </span>
                <div class="space-y-1.5 px-2">
                    <a href="{{ route('admin.appointments.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Записи на услуги
                    </a>
                    <a href="{{ route('admin.specialists.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Мастера салона
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Категории услуг
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Прайс-лист услуг
                    </a>
                    <a href="{{ route('admin.schedule.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        График работы
                    </a>
                </div>
            </div>


            <div>
                <span class="px-4 text-sm font-semibold text-pink-500  tracking-widest block mb-2.5">
                    Контент и настройки
                </span>
                <div class="space-y-1.5 px-2">
                    <a href="{{ route('admin.promotions.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Акции и Скидки
                    </a>
                    <a href="{{ route('admin.works.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Примеры работ
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-light text-[#1e1f22] hover:bg-[#fafafc] hover:text-[#ff5c8a] transition-all duration-200">
                        Модерация отзывов
                    </a>
                </div>
            </div>

        </nav>


        <div class="p-4 border-t border-[#f1f1f5] flex flex-col gap-1.5 bg-[#fafafc]/50">
            <a href="{{ route('home') }}"
               class="px-4 py-2 text-sm font-light text-[#7c7e8c] hover:text-[#1e1f22] transition-colors duration-200">
                ← На главную сайта
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm font-normal text-rose-500 hover:text-rose-600 transition-colors duration-200 cursor-pointer">
                    Выйти из системы
                </button>
            </form>
        </div>
    </div>


    <div class="flex-1 flex flex-col overflow-y-auto bg-[#fafafc] p-8 md:p-12">
        @yield('content')
    </div>
</div>

</body>
</html>
