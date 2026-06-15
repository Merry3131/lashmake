@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование работы')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider text-[#1e1f22]">Редактирование работы</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Изменение параметров или замена фотографии в портфолио</p>
            </div>
            <a href="{{ route('admin.works.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm tracking-wider font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                ← Назад к списку
            </a>
        </div>

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm p-8">
            <form action="{{ route('admin.works.update', $work->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm tracking-wider text-[#7c7e8c]">Мастер</label>
                        <select name="specialist_id" id="specialist_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}" {{ old('specialist_id', $work->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                    {{ $specialist->user->last_name }} {{ $specialist->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="service_id" class="block text-sm tracking-wider text-[#7c7e8c]">Услуга</label>
                        <select name="service_id" id="service_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $work->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-3 p-5 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <div class="flex items-start gap-4">
                        <div class="w-24 h-24 shrink-0 rounded-xl overflow-hidden bg-white border border-slate-200 flex items-center justify-center">
                            @if($work->hasMedia('images'))
                                <img src="{{ $work->getFirstMediaUrl('images', 'preview') }}" alt="Текущее фото" class="w-full h-full object-cover">
                            @else
                                <span class="text-sm text-slate-300 font-light">Нет фото</span>
                            @endif
                        </div>
                        <div class="space-y-1.5 flex-1">
                            <label for="image" class="block text-sm tracking-wider text-[#1e1f22]">Заменить фотографию</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-pink-100 file:text-pink-600 hover:file:bg-pink-200 file:cursor-pointer focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all">
                            <p class="text-[11px] text-[#7c7e8c] font-light">Оставьте поле пустым, если не хотите менять текущее изображение результата.</p>
                        </div>
                    </div>
                    @error('image')
                    <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm tracking-wider text-[#7c7e8c]">Описание работы / Заметки</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all resize-y">{{ old('description', $work->description) }}</textarea>
                    @error('description')
                    <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.works.index') }}"
                       class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-sm  font-normal rounded-xl transition-all duration-200 text-center">
                        Отмена
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm  font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                        Обновить данные
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
