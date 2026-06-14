@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование работы')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ШАПКА ФОРМЫ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Редактирование работы</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Изменение параметров или замена фотографии в портфолио</p>
            </div>
            <a href="{{ route('admin.works.index') }}" class="text-xs uppercase tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        {{-- ОСНОВНОЙ БЛОК --}}
        <main class="w-full">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm text-left">
                <form action="{{ route('admin.works.update', $work->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- ВЫБОР МАСТЕРА --}}
                        <div class="space-y-2">
                            <label for="specialist_id" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Мастер</label>
                            <select name="specialist_id" id="specialist_id" required
                                    class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                                @foreach($specialists as $specialist)
                                    <option value="{{ $specialist->id }}" {{ old('specialist_id', $work->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                        {{ $specialist->user->last_name }} {{ $specialist->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ВЫБОР УСЛУГИ --}}
                        <div class="space-y-2">
                            <label for="service_id" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Услуга</label>
                            <select name="service_id" id="service_id" required
                                    class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors">
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $work->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ТЕКУЩЕЕ ФОТО И ЗАГРУЗКА НОВОГО --}}
                    <div class="space-y-3 p-4 bg-[#fafafc] rounded-2xl border border-gray-100">
                        <div class="flex items-start gap-4">
                            <div class="w-24 h-24 shrink-0 rounded-xl overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                @if($work->hasMedia('images'))
                                    <img src="{{ $work->getFirstMediaUrl('images', 'preview') }}" alt="Текущее фото" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs text-gray-300 font-light uppercase">Нет фото</span>
                                @endif
                            </div>
                            <div class="space-y-1.5 flex-1">
                                <label for="image" class="block text-xs font-medium uppercase tracking-wider text-[#1e1f22]">Заменить фотографию</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                       class="w-full bg-white border border-[#e2e2e9] rounded-xl px-3 py-2 text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-[#ff5c8a]/10 file:text-[#ff5c8a] hover:file:bg-[#ff5c8a]/20 file:cursor-pointer focus:outline-none">
                                <p class="text-[11px] text-[#7c7e8c] font-light">Оставьте поле пустым, если не хотите менять текущее изображение результата.</p>
                            </div>
                        </div>
                        @error('image')
                        <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ОПИСАНИЕ --}}
                    <div class="space-y-2">
                        <label for="description" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Описание работы / Заметки</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors resize-y font-light">{{ old('description', $work->description) }}</textarea>
                        @error('description')
                        <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- КНОПКИ ДЕЙСТВИЙ --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.works.index') }}"
                           class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-200 text-center">
                            Отмена
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                            Обновить данные
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
@endsection
