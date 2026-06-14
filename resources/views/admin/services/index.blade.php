@extends('admin.layouts.admin_menu')

@section('title', 'Список услуг')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ВЕРХНЯЯ ЧАСТЬ: ЗАГОЛОВОК И КНОПКА ДОБАВЛЕНИЯ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Список услуг</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Управление прейскурантом, описаниями и статусами активности процедур студии</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                + Добавить услугу
            </a>
        </div>

        {{-- УВЕДОМЛЕНИЕ ОБ УСПЕШНОМ ДЕЙСТВИИ --}}
        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-normal tracking-wide shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-[10px] font-bold">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <label for="hide-alert-checkbox" class="ms-4 p-1.5 inline-flex items-center justify-center rounded-xl text-emerald-500 hover:bg-emerald-100 hover:text-emerald-900 transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </label>
                </div>
            </div>
        @endif

        {{-- ПОЛНОШИРИННАЯ ТАБЛИЦА УСЛУГ --}}
        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] bg-[#f8f8fa]">
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium w-16 text-center">ID</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Название услуги</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Описание</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Категория</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium">Статус</th>
                        <th scope="col" class="px-6 py-4 text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium text-right pr-8">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @forelse($services as $service)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200">
                            {{-- ID --}}
                            <td class="px-6 py-4.5 text-xs text-[#7c7e8c] font-light text-center bg-[#f8f8fa]/30">
                                {{ $service->id }}
                            </td>

                            {{-- Название --}}
                            <td class="px-6 py-4.5 text-sm font-medium text-[#1e1f22] whitespace-nowrap">
                                {{ $service->name }}
                            </td>

                            {{-- Описание --}}
                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light max-w-xs truncate">
                                {{ $service->description ?? '—' }}
                            </td>

                            {{-- Категория --}}
                            <td class="px-6 py-4.5 text-sm text-[#1e1f22] font-normal whitespace-nowrap">
                                {{ $service->category->display_name }}
                            </td>

                            {{-- Статус активности --}}
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($service->active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-normal tracking-wide uppercase rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Активна
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-normal tracking-wide uppercase rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        Недоступна
                                    </span>
                                @endif
                            </td>

                            {{-- Действия --}}
                            <td class="px-6 py-4.5 text-right space-x-1.5 pr-8 whitespace-nowrap">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="inline-flex items-center justify-center px-3 py-2 border border-[#f1f1f5] bg-white hover:bg-[#f8f8fa] text-[#1e1f22] text-xs font-light rounded-xl transition-all duration-200 shadow-sm">
                                    Изменить
                                </a>

                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить эту услугу?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-2 border border-rose-100 bg-rose-50/50 hover:bg-rose-50 text-rose-600 text-xs font-light rounded-xl transition-all duration-200 cursor-pointer shadow-sm">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-[#7c7e8c]">
                                <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">Услуг пока нет. Создайте первую!</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
