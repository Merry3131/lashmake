@extends('admin.layouts.admin_menu')

@section('title', 'Акции')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <div class="container mx-auto px-12 py-10 font-['Rubik'] bg-[#fafafc] min-h-screen">

        <div class="text-[#9ca0b0] text-xs space-x-1 mb-4 tracking-wide">
            <span>главная</span>
            <span>/</span>
            <span class="text-[#ff5c8a]">акции студии</span>
        </div>

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-[#1e1f22] text-4xl tracking-tight">Акции студии</h1>

            <a href="{{ route('admin.promotions.create') }}"
               class="bg-[#b30047] hover:bg-[#8a0036] text-white text-sm py-3 px-6 rounded-2xl transition-colors duration-200 shadow-sm">
                Добавить акцию
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-[#f1f1f5] rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                <tr class="bg-[#f5f5f7] border-b border-[#e2e2e9] text-xs text-[#7c7e8c] tracking-wide">
                    <th class="py-5 px-8">название акции</th>
                    <th class="py-5 px-6">услуга</th>
                    <th class="py-5 px-6">мастер</th>
                    <th class="py-5 px-6 text-center">скидка</th>
                    <th class="py-5 px-6">период действия</th>
                    <th class="py-5 px-8 text-right">действия</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f1f5] text-sm text-[#1e1f22]">
                @forelse($promotions as $promotion)
                    <tr class="hover:bg-[#fafafc] transition-colors duration-150">
                        <td class="py-5 px-8 text-base text-[#1e1f22]">
                            {{ $promotion->title }}
                        </td>

                        <td class="py-5 px-6 text-[#5c5e66]">
                            {{ $promotion->service->name ?? 'Не указана' }}
                        </td>

                        <td class="py-5 px-6 text-[#5c5e66]">
                            @if($promotion->specialist)
                                {{ $promotion->specialist->user->first_name }} {{ $promotion->specialist->user->last_name }}
                            @else
                                Все мастера студии
                            @endif
                        </td>

                        <td class="py-5 px-6 text-center text-[#ff5c8a] text-base">
                            -{{ $promotion->discount_percent }}%
                        </td>

                        <td class="py-5 px-6 text-xs text-[#9ca0b0]">
                            {{ date_format(date_create($promotion->start_date), 'd.m.Y') }} — {{ date_format(date_create($promotion->end_date), 'd.m.Y') }}
                        </td>

                        <td class="py-5 px-8 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                   class="bg-[#e2e2e9] hover:bg-[#d4d4dc] text-[#5c5e66] text-xs py-2 px-4 rounded-xl transition-colors duration-150">
                                    Изменить
                                </a>

                                <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Вы уверены?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-[#fff0f2] hover:bg-[#ffe1e5] text-[#ff5c8a] text-xs py-2 px-4 rounded-xl transition-colors duration-150">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-[#7c7e8c] text-base">
                            Активных акций в базе данных не найдено.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
