@extends('admin.layouts.admin_menu')

@section('title', 'Примеры работ')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal  text-[#1e1f22]">Примеры работ</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Портфолио мастеров студии и фотографии результатов процедур</p>
            </div>
            <a href="{{ route('admin.works.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm  font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                + Добавить работу
            </a>
        </div>

        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-normal tracking-wide shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-[10px]">✓</span>
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

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] bg-[#f8f8fa]">
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Фото</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Мастер студии</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Процедура / Услуга</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Описание результата</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium text-right pr-8">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @forelse($works as $work)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center">
                                    @if($work->hasMedia('works'))
                                        <img src="{{ $work->getFirstMediaUrl('works', 'preview') }}" alt="Превью" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] text-slate-300 font-light">Нет фото</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-normal text-[#1e1f22]">
                                {{ $work->specialist->user->last_name }} {{ $work->specialist->user->first_name }}
                            </td>
                            <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-sm bg-slate-100 font-light text-slate-600">
                                        {{ $work->service->name }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#7c7e8c] font-light max-w-xs truncate">
                                {{ $work->description ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5 pr-8 whitespace-nowrap">
                                <a href="{{ route('admin.works.edit', $work->id) }}" class="inline-flex items-center justify-center px-3 py-2 border border-[#f1f1f5] bg-white hover:bg-[#f8f8fa] text-[#1e1f22] text-sm font-light rounded-xl transition-all duration-200 shadow-sm">
                                    Изменить
                                </a>
                                <form action="{{ route('admin.works.destroy', $work->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-2 border border-rose-100 bg-rose-50/50 hover:bg-rose-50 text-rose-600 text-sm font-light rounded-xl transition-all duration-200 cursor-pointer shadow-sm">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-[#7c7e8c]">
                                <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">В портфолио пока нет добавленных примеров работ.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
