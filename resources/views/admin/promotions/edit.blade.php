@extends('admin.layouts.admin_menu')

@section('title', 'Акции')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <div class="w-full px-12 py-10 font-['Rubik'] bg-[#fafafc] min-h-screen">

        <div class="flex justify-between items-center mb-4 w-full">
            <div class="text-[#9ca0b0] text-xs space-x-1">
                <span>Главная</span>
                <span>/</span>
                <a href="{{ route('admin.promotions.index') }}" class="hover:text-[#ff5c8a] transition-colors">Акции студии</a>
                <span>/</span>
                <span class="text-[#ff5c8a]">Редактирование акции</span>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="text-[#9ca0b0] hover:text-[#1e1f22] text-xs tracking-wide transition-colors">
                ← назад к списку
            </a>
        </div>

        <h1 class="text-[#1e1f22] text-4xl tracking-tight mb-8 w-full">Редактирование акции</h1>

        <div class="bg-white border border-[#f1f1f5] rounded-3xl p-10 shadow-sm w-full">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" class="space-y-8 w-full">
                @csrf
                @method('PUT')

                <div class="space-y-3 w-full">
                    <label for="title" class="block text-sm text-[#7c7e8c] ml-1">название акции (отображаемое на сайте)</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $promotion->title) }}"
                           placeholder="например: весеннее преображение"
                           class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-5 py-4 text-base text-[#1e1f22] placeholder-[#c4c6d0] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('title') border-red-300 @enderror">
                    @error('title')
                    <p class="text-xs text-red-400 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                    <div class="space-y-3">
                        <label for="service_id" class="block text-sm text-[#7c7e8c] ml-1">услуга студии</label>
                        <select name="service_id"
                                id="service_id"
                                class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-5 py-4 text-base text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $promotion->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label for="specialist_id" class="block text-sm text-[#7c7e8c] ml-1">мастер (необязательно)</label>
                        <select name="specialist_id"
                                id="specialist_id"
                                class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-5 py-4 text-base text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                            <option value="">все мастера студии</option>
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}" {{ old('specialist_id', $promotion->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                    {{ $specialist->user->last_name }} {{ $specialist->user->first_name }} {{ $specialist->user->middle_name ?? '' }}
                                    @if(!empty($specialist->level->name))
                                        ({{ $specialist->level->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
                    <div class="space-y-3">
                        <label for="discount_percent" class="block text-sm text-[#7c7e8c] ml-1">скидка (%)</label>
                        <input type="number"
                               name="discount_percent"
                               id="discount_percent"
                               min="1"
                               max="100"
                               value="{{ old('discount_percent', $promotion->discount_percent) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-5 py-4 text-base text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                    </div>

                    <div class="space-y-3">
                        <label for="start_date" class="block text-sm text-[#7c7e8c] ml-1">дата начала</label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               value="{{ old('start_date', is_string($promotion->start_date) ? $promotion->start_date : $promotion->start_date->format('Y-m-d')) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-5 py-4 text-base text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                    </div>

                    <div class="space-y-3">
                        <label for="end_date" class="block text-sm text-[#7c7e8c] ml-1">дата окончания</label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               value="{{ old('end_date', is_string($promotion->end_date) ? $promotion->end_date : $promotion->end_date->format('Y-m-d')) }}"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-5 py-4 text-base text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                    </div>
                </div>

                <div class="pt-10 flex justify-between items-center border-t border-[#f1f1f5] w-full">
                    <a href="{{ route('admin.promotions.index') }}"
                       class="text-sm text-[#1e1f22] tracking-widest hover:text-[#ff5c8a] transition-colors uppercase">
                        отмена
                    </a>

                    <button type="submit"
                            class="bg-[#e31e65] hover:bg-[#c41353] text-white text-base py-4 px-10 rounded-2xl transition-all duration-200 shadow-sm font-medium">
                        сохранить изменения
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
