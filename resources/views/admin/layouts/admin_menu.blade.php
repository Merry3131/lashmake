<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lashmake Admin')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 font-sans">

<div class="flex h-screen overflow-hidden">
    <div class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0">
        <div class="p-5 text-xl font-bold text-white tracking-wider border-b border-slate-800">
            Lashmake Admin
        </div>
        <nav class="flex-1 p-4 space-y-6 overflow-y-auto">

            <div>
        <span class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">
            Операционная работа
        </span>
                <div class="space-y-1 px-4">
                    <a href="{{ route('admin.appointments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        Записи на услуги
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        График работы
                    </a>
                </div>
            </div>

            <div>
        <span class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">
            Управление салоном
        </span>
                <div class="space-y-1 px-4">
                    <a href="{{ route('admin.categories.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-medium
                      {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        Категории услуг
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        Услуги
                    </a>
                    <a href="{{ route('admin.specialists.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        Специалисты (Мастера)
                    </a>
                </div>
            </div>

            <div>
        <span class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">
            Контент и Маркетинг
        </span>
                <div class="space-y-1 px-4">
                    <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        Акции и Скидки
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        Примеры работ
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        Модерация отзывов
                    </a>
                </div>
            </div>

        </nav>
        <div class="p-4 border-t border-slate-800 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-all">
                ← На главную сайта
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 transition-all cursor-pointer">
                    Выйти из системы
                </button>
            </form>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-y-auto">
        <main class="p-8">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
