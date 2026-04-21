<x-app-layout title="Центр Ресниц | Примеры работ">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6">
        @foreach($works as $work)
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 flex flex-col">

                {{-- 2. Вывод фото через Spatie Media Library --}}
                <div class="aspect-square relative bg-gray-50">
{{--                    @if($work->hasMedia('works'))--}}
{{--                        <img src="{{ $work->getFirstMediaUrl('works') }}" class="w-full h-full object-cover" alt="{{ $work->service->name }}">--}}
{{--                    @else--}}
{{--                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs italic">--}}
{{--                            Нет фото--}}
{{--                        </div>--}}
{{--                    @endif--}}

                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xl font-bold text-gray-900 leading-tight">
                        {{ $work->service->category->display_name ?? 'Без категории' }}
                    </span>
                    <h4 class="text-xl font-bold text-gray-900 leading-tight">
                        {{ $work->service->name }}
                    </h4>
                    <p class="text-pink-400 text-sm font-semibold mt-2">
                        Мастер: {{ $work->specialist->user->first_name }}
                    </p>

                    <div class="mt-4 text-gray-600 text-sm leading-relaxed flex-grow">
                        {{ $work->description }}
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                        <span class="text-xs text-gray-400 uppercase tracking-tighter">Портфолио Lashmake</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
