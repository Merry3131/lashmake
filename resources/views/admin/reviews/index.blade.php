@extends('admin.layouts.admin_menu')

@section('title', 'Модерация отзывов')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl tracking-wider text-[#1e1f22] font-[Playfair_Display]">Модерация отзывов</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Просмотр, одобрение публикаций и удаление отзывов клиентов студии</p>
            </div>
        </div>

        <div class="flex items-center gap-2 p-1 bg-[#f8f8fa] border border-[#f1f1f5] rounded-xl w-fit mb-6">
            <a href="{{ route('admin.reviews.index') }}"
               class="px-4 py-2 text-xs tracking-wide rounded-lg transition-all duration-200 {{ !request()->has('status') ? 'bg-white text-[#1e1f22] shadow-sm font-medium' : 'text-[#7c7e8c] hover:text-[#1e1f22]' }}">
                Все
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
               class="px-4 py-2 text-xs tracking-wide rounded-lg transition-all duration-200 {{ request()->get('status') === 'pending' ? 'bg-white text-[#1e1f22] shadow-sm font-medium' : 'text-[#7c7e8c] hover:text-[#1e1f22]' }}">
                На модерации
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
               class="px-4 py-2 text-xs tracking-wide rounded-lg transition-all duration-200 {{ request()->get('status') === 'approved' ? 'bg-white text-[#1e1f22] shadow-sm font-medium' : 'text-[#7c7e8c] hover:text-[#1e1f22]' }}">
                Опубликованные
            </a>
        </div>

        @if(session('success'))
            <div class="relative mb-6">
                <input type="checkbox" id="hide-alert-checkbox" class="peer hidden" />
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs tracking-wide shadow-sm peer-checked:hidden transition-all">
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

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                    <tr class="border-b border-[#f1f1f5] bg-[#f8f8fa]">
                        <th scope="col" class="px-6 py-4 text-sm tracking-wider text-[#7c7e8c] font-medium">Клиент</th>
                        <th scope="col" class="px-6 py-4 text-sm tracking-wider text-[#7c7e8c] font-medium">Мастер</th>
                        <th scope="col" class="px-6 py-4 text-sm tracking-wider text-[#7c7e8c] font-medium text-center">Рейтинг</th>
                        <th scope="col" class="px-6 py-4 text-sm tracking-wider text-[#7c7e8c] font-medium">Комментарий</th>
                        <th scope="col" class="px-6 py-4 text-sm tracking-wider text-[#7c7e8c] font-medium">Статус</th>
                        <th scope="col" class="px-6 py-4 text-sm tracking-wider text-[#7c7e8c] font-medium text-right pr-8">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f1f5]">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-[#f8f8fa]/50 transition-colors duration-200 {{ !$review->is_approved ? 'bg-amber-50/20' : '' }}">

                            <td class="px-6 py-4.5 text-sm text-[#1e1f22] whitespace-nowrap">
                                <div class="font-medium">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                            </td>

                            <td class="px-6 py-4.5 text-sm text-[#7c7e8c] font-light whitespace-nowrap">
                                {{ $review->specialist->user->first_name }} {{ $review->specialist->user->last_name }}
                            </td>

                            <td class="px-6 py-4.5 text-sm text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700">
                                    ★ {{ $review->rating }} / 5
                                </span>
                            </td>

                            <td class="px-6 py-4.5 text-sm text-[#1e1f22] max-w-xs md:max-w-md break-words font-light">
                                {{ $review->comment ?? 'Без комментария' }}
                            </td>

                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($review->is_approved)
                                    <span class="inline-flex items-center px-2.5 py-1 text-sm tracking-wide rounded-lg bg-emerald-50 text-emerald-700">
                                        Опубликован
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-sm tracking-wide rounded-lg bg-amber-50 text-amber-700">
                                        На модерации
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4.5 text-right space-x-1.5 pr-8 whitespace-nowrap">
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-light rounded-xl transition-all duration-200 shadow-sm cursor-pointer">
                                            Одобрить
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить этот отзыв?')">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                                <p class="font-light text-sm">Отзывов пока нет.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 px-2">
            {{ $reviews->links() }}
        </div>

    </div>
@endsection
