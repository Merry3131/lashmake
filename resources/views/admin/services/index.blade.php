@extends('admin.layouts.admin_menu')

@section('title', 'Список услуг')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

{{--        <a href="{{ route('admin.services.print') }}"--}}
{{--           target="_blank"--}}
{{--           class="inline-flex items-center justify-center px-5 py-3 bg-white border border-[#ff5c8a] text-[#ff5c8a] hover:bg-[#ff5c8a] hover:text-white text-xs tracking-wider font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">--}}
{{--            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>--}}
{{--            </svg>--}}
{{--            Распечатать прайс-лист--}}
{{--        </a>--}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl  text-[#1e1f22] font-[Playfair_Display]">Список услуг</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Управление прейскурантом, описаниями и статусами активности процедур студии</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs   font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                + Добавить услугу
            </a>
        </div>


        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-normal tracking-wide shadow-sm peer-checked:hidden transition-all">
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-sm font-bold">✓</span>
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
                <table class="w-full border-collapse text-left">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] bg-[#f8f8fa]">
                        <th scope="col" class="px-6 py-4 text-sm  text-[#7c7e8c] font-medium">Название услуги</th>
                        <th scope="col" class="px-6 py-4 text-sm  text-[#7c7e8c] font-medium">Описание</th>
                        <th scope="col" class="px-6 py-4 text-sm  text-[#7c7e8c] font-medium">Категория</th>
                        <th scope="col" class="px-6 py-4 text-sm  text-[#7c7e8c] font-medium">Статус</th>
                        <th scope="col" class="px-6 py-4 text-sm  text-[#7c7e8c] font-medium text-right pr-8">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @forelse($services as $service)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200">

                            <td class="px-6 py-4.5 text-sm font-medium text-[#1e1f22] whitespace-nowrap">
                                {{ $service->name }}
                            </td>


                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light max-w-xs truncate">
                                {{ $service->description ?? '—' }}
                            </td>


                            <td class="px-6 py-4.5 text-sm text-[#1e1f22] font-normal whitespace-nowrap">
                                {{ $service->category->display_name }}
                            </td>


                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($service->active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-normal tracking-wide  rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Активна
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-normal tracking-wide  rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                                        Недоступна
                                    </span>
                                @endif
                            </td>


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
