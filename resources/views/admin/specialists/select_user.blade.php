@extends('admin.layouts.admin_menu')

@section('title', 'Выбор пользователя')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ВЕРХНЯЯ ЧАСТЬ: ЗАГОЛОВОК И НАЗАД --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Шаг 1: Выберите пользователя</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Найдите клиента или сотрудника, которого хотите назначить мастером салона</p>
            </div>
            <a href="{{ route('admin.specialists.index') }}" class="text-xs uppercase tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к мастерам
            </a>
        </div>

        {{-- ПАНЕЛЬ ПОИСКА НА ВСЮ ШИРИНУ --}}
        <div class="w-full bg-white p-4 rounded-3xl border border-[#f1f1f5] shadow-sm mb-6">
            <form action="{{ route('admin.specialists.create') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">
                <div class="relative flex-1 w-full">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Введите Фамилию, Имя или номер телефона..."
                           class="w-full px-4 py-3 rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] text-sm text-[#1e1f22] placeholder-gray-400 focus:outline-none focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 transition-all font-light" />
                </div>
                <div class="flex gap-2 shrink-0">
                    <button type="submit" class="px-6 py-3 bg-[#1e1f22] hover:bg-black text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-200 cursor-pointer">
                        Найти
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.specialists.create') }}" class="px-4 py-3 border border-gray-200 text-gray-500 hover:text-[#1e1f22] text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-200 text-center flex items-center justify-center">
                            Сбросить
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ТАБЛИЦА РЕЗУЛЬТАТОВ НА ВСЮ ШИРИНУ --}}
        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] text-[#7c7e8c] text-[10px] font-medium uppercase tracking-wider bg-[#f8f8fa]">
                        <th class="px-6 py-4">ФИО Пользователя</th>
                        <th class="px-6 py-4">Телефон</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-right">Действие</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5] text-sm font-light">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-150">
                            <td class="px-6 py-4 font-normal text-[#1e1f22]">
                                {{ $user->last_name }} {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 text-[#1e1f22]">
                                {{ $user->phone ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-[#7c7e8c]">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.specialists.build', $user->id) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer whitespace-nowrap">
                                    Сделать мастером →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-[#7c7e8c]">
                                <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">Пользователи не найдены или все из них уже назначены мастерами.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
