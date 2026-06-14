@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование мастера')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ВЕРХНЯЯ ЧАСТЬ: ЗАГОЛОВОК И НАЗАД --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Редактирование мастера</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">
                    Изменение профиля специалиста: <span class="font-normal text-[#ff5c8a]">{{ $specialist->user->last_name }} {{ $specialist->user->first_name }}</span>
                </p>
            </div>
            <a href="{{ route('admin.specialists.index') }}" class="text-xs uppercase tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        {{-- ФОРМА РЕДАКТИРОВАНИЯ (ПОЛНАЯ ШИРИНА И ОДИН СТОЛБЕЦ) --}}
        <main class="w-full">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm text-left">

                <form action="{{ route('admin.specialists.update', $specialist->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-6 w-full">

                        {{-- 1. Уровень мастера --}}
                        <div class="w-full">
                            <label for="level_id" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Уровень мастера
                            </label>
                            <select name="level_id"
                                    id="level_id"
                                    required
                                    class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light appearance-none">
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id', $specialist->level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 2. Опыт работы --}}
                        <div class="w-full">
                            <label for="experience" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Опыт работы
                            </label>
                            <input type="text"
                                   id="experience"
                                   name="experience"
                                   value="{{ old('experience', $specialist->experience) }}"
                                   required
                                   class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light" />
                            @error('experience')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 3. Описание / Биография --}}
                        <div class="w-full">
                            <label for="bio" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Описание
                            </label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="4"
                                      placeholder="Опишите услугу для клиентов..."
                                      class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:bg-white focus:ring-[#ff5c8a]/20 text-sm p-3 transition-all duration-200 outline-none font-light resize-y">{{ old('bio', $specialist->bio) }}</textarea>
                            @error('bio')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- КНОПКИ ДЕЙСТВИЙ --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50 w-full">
                        <a href="{{ route('admin.specialists.index') }}"
                           class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-200 text-center">
                            Отмена
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                            Обновить
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
@endsection
