@extends('admin.layouts.admin_menu')

@section('title', 'Специалисты | Lashmake Admin')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider  text-[#1e1f22] font-[Playfair_Display]">Мастера</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Управление командой мастеров, их квалификацией и профилями</p>
            </div>
            <a href="{{ route('admin.specialists.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider  font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer">
                + Добавить мастера
            </a>
        </div>

        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-light shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-normal">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <label for="hide-alert-checkbox" class="ms-4 p-1.5 inline-flex items-center justify-center rounded-xl text-emerald-500 hover:bg-emerald-100 hover:text-emerald-900 transition-colors cursor-pointer text-xs  tracking-wider">
                        Закрыть
                    </label>
                </div>
            </div>
        @endif

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] text-[#7c7e8c] text-[10px] font-medium  tracking-wider bg-[#f8f8fa]">
                        <th class="px-6 py-4 text-sm  tracking-wider text-[#7c7e8c] font-medium">ФИО Специалиста</th>
                        <th class="px-6 py-4 text-sm  tracking-wider text-[#7c7e8c] font-medium">Email</th>
                        <th class="px-6 py-4 text-sm  tracking-wider text-[#7c7e8c] font-medium">Квалификация</th>
                        <th class="px-6 py-4 text-sm  tracking-wider text-[#7c7e8c] font-medium">Опыт</th>
                        <th class="px-6 py-4 text-sm  tracking-wider text-[#7c7e8c] font-medium text-center">Описание</th>
                        <th class="px-6 py-4 text-sm  tracking-wider text-[#7c7e8c] font-medium text-right">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5] text-sm font-light">
                    @forelse($specialists as $specialist)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-150">
                            <td class="px-6 py-4 font-normal text-[#1e1f22]">
                                {{ $specialist->user->last_name }} {{ $specialist->user->first_name }}
                            </td>
                            <td class="px-6 py-4 text-[#7c7e8c] font-light">
                                {{ $specialist->user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-normal bg-pink-50 text-[#ff5c8a] border border-pink-100/50">
                                    {{ $specialist->level->display_name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[#1e1f22]">
                                {{ $specialist->experience ?? '—' }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-[#7c7e8c]">
                                {{ $specialist->bio ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.specialists.edit', $specialist->id) }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-100 bg-[#f8f8fa] hover:bg-gray-100 text-gray-600 text-xs font-light rounded-xl transition-all duration-200 cursor-pointer shadow-sm">
                                    Изменить
                                </a>
                                <form action="{{ route('admin.specialists.destroy', $specialist->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить мастера из системы?')">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">Мастера салона еще не добавлены.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
