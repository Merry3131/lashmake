<x-app-layout title="Центр Ресниц | Отзывы">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6">

        @foreach($reviews as $review)
            <div class="bg-white rounded-3xl shadow-lg border border-gray-50 flex flex-col p-6 transition-transform hover:scale-[1.02]">

                <div class="flex justify-between items-start mb-4">
                    <div>

                        <h5 class="text-lg font-bold text-gray-900 leading-none">
                            {{ $review->user->first_name }} {{ $review->user->last_name }}
                        </h5>

                        <span class="text-[10px] text-gray-400 uppercase tracking-widest">
                            {{ $review->created_at->format('d.m.Y') }}
                        </span>
                    </div>
                    <div class="flex text-pink-500 text-sm">

                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </div>

                <div class="bg-pink-50 rounded-2xl p-3 mb-4 flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] bg-pink-500 text-white px-2 py-0.5 rounded-full uppercase">Услуга</span>
                        <span class="text-sm font-medium text-gray-700">
                            {{ $review->appointment->service->name }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full uppercase">Мастер</span>
                        <span class="text-sm font-medium text-gray-700">
                            {{ $review->specialist->user->first_name }}
                        </span>
                    </div>
                </div>

                <div class="flex-grow">
                    <p class="text-gray-600 text-sm italic leading-relaxed">
                        "{{ $review->comment }}"
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-[10px] text-gray-300 uppercase tracking-tighter">Lashmake Feedback</span>
                    <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-500">
                        <i class="fas fa-quote-right text-xs"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
