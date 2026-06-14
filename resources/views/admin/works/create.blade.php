@extends('admin.layouts.admin_menu')

@section('title', 'Добавление примера работы')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ШАПКА ФОРМЫ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Новый пример работы</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Добавление фотографии выполненной процедуры в галерею студии</p>
            </div>
            <a href="{{ route('admin.works.index') }}" class="text-xs uppercase tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        {{-- ОСНОВНОЙ БЛОК --}}
        <main class="w-full">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm text-left">
                <form action="{{ route('admin.works.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- ВЫБОР МАСТЕРА --}}
                        <div class="space-y-2">
                            <label for="specialist_id" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Мастер, выполнивший работу</label>
                            <select name="specialist_id" id="specialist_id" required
                                    class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('specialist_id') border-red-400 @enderror">
                                <option value="" disabled selected>Выберите специалиста из списка...</option>
                                @foreach($specialists as $specialist)
                                    <option value="{{ $specialist->id }}" {{ old('specialist_id') == $specialist->id ? 'selected' : '' }}>
                                        {{ $specialist->user->last_name }} {{ $specialist->user->name }} ({{ $specialist->level_id ?? 'Мастер' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('specialist_id')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ВЫБОР УСЛУГИ --}}
                        <div class="space-y-2">
                            <label for="service_id" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Выполненная услуга</label>
                            <select name="service_id" id="service_id" required
                                    class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3.5 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors @error('service_id') border-red-400 @enderror">
                                <option value="" disabled selected>Выберите процедуру...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ЗАГРУЗКА ИЗОБРАЖЕНИЯ --}}
                    <div class="space-y-2">
                        <label for="image" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Фотография результата</label>
                        <input type="file" name="image" id="image" required accept="image/*"
                               class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-normal file:bg-[#ff5c8a]/10 file:text-[#ff5c8a] hover:file:bg-[#ff5c8a]/20 file:cursor-pointer focus:outline-none @error('image') border-red-400 @enderror">
                        <p class="text-[11px] text-[#7c7e8c] font-light ml-1">Поддерживаются форматы JPG, PNG, WEBP. Максимальный размер файла: 5 МБ.</p>
                        @error('image')
                        <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ОПИСАНИЕ --}}
                    <div class="space-y-2">
                        <label for="description" class="block text-xs font-medium uppercase tracking-wider text-[#7c7e8c] ml-1">Комментарии / Описание работы</label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Например: Использовали изгиб C, толщина 0.07, эффект лисий. Время работы — 2 часа..."
                                  class="w-full bg-[#fafafc] border border-[#e2e2e9] rounded-xl px-4 py-3 text-sm text-[#1e1f22] focus:outline-none focus:border-[#ff5c8a] transition-colors resize-y font-light">{{ old('description') }}</textarea>
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
                            Сохранить работу
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
@endsection
