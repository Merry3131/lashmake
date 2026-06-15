@extends('admin.layouts.admin_menu')

@section('title', 'Акции')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal  text-[#1e1f22]">Акции</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Управление специальными предложениями и скидками</p>
            </div>
            <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm  font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                + Добавить акцию
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
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Название акции</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Услуга</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Мастер</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium text-center">Скидка</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium text-center">Цена со скидкой</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium">Период действия</th>
                        <th scope="col" class="px-6 py-4 text-sm text-[#7c7e8c] font-medium text-right pr-8">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @forelse($promotions as $promotion)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200">
                            <td class="px-6 py-4.5 text-sm font-normal text-[#1e1f22] whitespace-nowrap">
                                <span class="hover:text-[#ff5c8a] transition-colors duration-200">{{ $promotion->title }}</span>
                            </td>
                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light">
                                {{ $promotion->service->name ?? 'Не указана' }}
                            </td>
                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light">
                                @if($promotion->specialist)
                                    {{ $promotion->specialist->user->first_name }} {{ $promotion->specialist->user->last_name }}
                                    <span class="text-[10px] text-[#9ca0b0] block">
                                            ({{ $promotion->specialist->level->display_name ?? $promotion->specialist->level->name ?? 'уровень не задан' }})
                                        </span>
                                @else
                                    Все мастера
                                @endif
                            </td>
                            <td class="px-6 py-4.5 text-center text-sm font-normal text-[#ff5c8a]">
                                -{{ $promotion->discount_percent }}%
                            </td>
                            <td class="px-6 py-4.5 text-center text-sm font-medium text-[#1e1f22]">
                                {{ $promotion->price_display }}
                            </td>
                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($promotion->start_date)->format('d.m.Y') }} — {{ \Carbon\Carbon::parse($promotion->end_date)->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4.5 text-right space-x-1.5 pr-8 whitespace-nowrap">
                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="inline-flex items-center justify-center px-3 py-2 border border-[#f1f1f5] bg-white hover:bg-[#f8f8fa] text-[#1e1f22] text-xs font-light rounded-xl transition-all duration-200 shadow-sm">
                                    Изменить
                                </a>
                                <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-2 border border-[#f1f1f5] bg-white hover:bg-[#f8f8fa] text-[#1e1f22] text-xs font-light rounded-xl transition-all duration-200 shadow-sm">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-[#7c7e8c]">
                                <div class="w-12 h-12 bg-[#f8f8fa] border border-[#f1f1f5] rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">Акций пока нет. Создайте первую!</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
