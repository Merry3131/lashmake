@extends('admin.layouts.admin_menu')

@section('title', 'Примеры работ')

@section('content')
    <div class="max-w-7xl mx-auto font-['Manrope'] text-[#1e1f22]">

        {{-- ШАПКА --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Примеры работ</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Портфолио мастеров студии и фотографии результатов процедур</p>
            </div>
            <a href="{{ route('admin.works.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer">
                + Добавить работу
            </a>
        </div>

        {{-- УВЕДОМЛЕНИЯ --}}
        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-semibold shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <label for="hide-alert-checkbox" class="ms-4 p-1.5 inline-flex items-center justify-center rounded-xl text-emerald-500 hover:bg-emerald-100 hover:text-emerald-900 transition-colors cursor-pointer">✕</label>
                </div>
            </div>
        @endif

        {{-- ТАБЛИЦА С ДАННЫМИ --}}
        <div class="bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                <tr class="border-b border-[#f1f1f5] text-[#7c7e8c] text-[11px] font-bold uppercase tracking-wider bg-[#fafafc]">
                    <th class="px-6 py-4 w-24">Фото</th>
                    <th class="px-6 py-4">Мастер студии</th>
                    <th class="px-6 py-4">Процедура / Услуга</th>
                    <th class="px-6 py-4 max-w-xs">Описание результата</th>
                    <th class="px-6 py-4 text-right">Действия</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f1f5] text-sm text-[#1e1f22]">
                @forelse($works as $work)
                    <tr class="hover:bg-[#fafafc]/50 transition-colors duration-150">
                        {{-- Вывод превью через Spatie --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex items-center justify-center">
                                @if($work->hasMedia('works'))
                                    <img src="{{ $work->getFirstMediaUrl('works', 'preview') }}" alt="Превью" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] text-gray-300 uppercase font-light">Нет фото</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-[#1e1f22]">
                            {{ $work->specialist->user->last_name }} {{ $work->specialist->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs bg-gray-100 font-light text-gray-600">
                                {{ $work->service->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[#7c7e8c] max-w-xs truncate font-light">
                            {{ $work->description ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.works.edit', $work->id) }}"
                               class="inline-flex items-center justify-center px-3 py-2 border border-gray-100 bg-[#fafafc] hover:bg-[#f1f1f5] text-[#1e1f22] text-xs font-light rounded-xl transition-all duration-200 cursor-pointer shadow-sm">
                                Изменить
                            </a>
                            <form action="{{ route('admin.works.destroy', $work->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить этот пример работы?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-3 py-2 border border-rose-100 bg-rose-50/50 hover:bg-rose-50 text-rose-600 text-xs font-light rounded-xl transition-all duration-200 cursor-pointer shadow-sm">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-[#7c7e8c]">
                            <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                                📷
                            </div>
                            <p class="font-light text-sm">В портфолио пока нет добавленных примеров работ.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
