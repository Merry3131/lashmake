<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | Lashmake</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 font-sans">

<div class="flex h-screen">
    <div class="w-64 bg-slate-900 text-slate-300 flex flex-col">
        <div class="p-5 text-xl font-bold text-white tracking-wider border-b border-slate-800">
            Lashmake Admin
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-xl bg-slate-800 text-white font-medium">
                Главная панель
            </a>
            <a href="#" class="block px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all">
                Записи на услуги
            </a>
            <a href="#" class="block px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all">
                Управление услугами
            </a>
            <a href="#" class="block px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-all">
                Мастера салона
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <a href="{{ route('home') }}">Сайт</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300">
                    Выйти из системы
                </button>
            </form>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800">Обзор студии</h1>
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-slate-600">{{ Auth::user()->first_name }} (Администратор)</span>
            </div>
        </header>

        <main class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                    <span class="text-sm font-semibold text-slate-400 uppercase">Всего записей</span>
                    <span class="text-3xl font-black text-slate-800 mt-2">{{ $totalAppointments }}</span>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                    <span class="text-sm font-semibold text-slate-400 uppercase">Ожидают подтверждения</span>
                    <span class="text-3xl font-black text-amber-600 mt-2">{{ $pendingAppointments }}</span>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                    <span class="text-sm font-semibold text-slate-400 uppercase">Зарегистрировано клиентов</span>
                    <span class="text-3xl font-black text-slate-800 mt-2">{{ $totalClients }}</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Добро пожаловать в панель управления!</h2>
                <p class="text-slate-600">С чего начнем разработку? Можем сразу вывести сюда таблицу заявок, которые `pending`, чтобы администратор мог одобрять или отклонять записи клиентов кликом по кнопке.</p>
            </div>
        </main>
    </div>
</div>

</body>
</html>
