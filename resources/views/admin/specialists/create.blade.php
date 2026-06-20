@extends('admin.layouts.admin_menu')

@section('title', 'Заполнение данных мастера')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <a href="{{ route('admin.specialists.create') }}" class="text-xs tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium mb-2 inline-block">
                    ← Вернуться к выбору пользователя
                </a>
                <h1 class="text-2xl text-[#1e1f22] font-[Playfair_Display]">Шаг 2: Данные специалиста</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">
                    Вы назначаете мастером: <span class="font-normal text-[#ff5c8a]">{{ $user->last_name }} {{ $user->first_name }}</span> ({{ $user->email }})
                </p>
            </div>
        </div>

        <main class="w-full">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm text-left">

                <form action="{{ route('admin.specialists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="flex flex-col gap-6 w-full">

                        {{-- Уровень квалификации --}}
                        <div class="w-full">
                            <label for="level_id" class="block text-xs tracking-wider text-[#7c7e8c] mb-1.5">
                                Уровень квалификации
                            </label>
                            <select id="level_id"
                                    name="level_id"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all appearance-none">
                                <option value="" disabled selected>Выберите уровень квалификации...</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Опыт работы --}}
                        <div class="w-full">
                            <label for="experience" class="block text-xs tracking-wider text-[#7c7e8c] mb-1.5">
                                Опыт работы (текст для отображения)
                            </label>
                            <input type="text"
                                   id="experience"
                                   name="experience"
                                   value="{{ old('experience') }}"
                                   required
                                   placeholder="Например: 3 года или 8 лет"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all" />
                            @error('experience')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Услуги мастера --}}
                        <div class="w-full">
                            <label class="block text-xs tracking-wider text-[#7c7e8c] mb-1.5">
                                Услуги мастера
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-slate-50/50 rounded-xl border border-slate-200">
                                @foreach($services as $service)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-pink-500 transition-colors">
                                        <input type="checkbox"
                                               name="services[]"
                                               value="{{ $service->id }}"
                                               {{ is_array(old('services')) && in_array($service->id, old('services')) ? 'checked' : '' }}
                                               class="w-4 h-4 text-pink-500 border-slate-300 rounded focus:ring-pink-500">
                                        <span class="text-sm text-slate-700 font-light">{{ $service->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('services')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Биография --}}
                        <div class="w-full">
                            <label for="bio" class="block text-xs tracking-wider text-[#7c7e8c] mb-1.5">
                                О себе / Биография мастера
                            </label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="4"
                                      placeholder="Расскажите об особенностях работы мастера, сильных сторонах, техниках и дипломах..."
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all resize-y">{{ old('bio') }}</textarea>
                            @error('bio')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Фото мастера (новое поле) --}}
                        <div class="w-full">
                            <label for="photo" class="block text-xs tracking-wider text-[#7c7e8c] mb-1.5">
                                Фото мастера
                            </label>
                            <input type="file"
                                   id="photo"
                                   name="photo"
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all" />
                            <div id="photo-preview" class="mt-2 hidden">
                                <img id="preview-img" src="#" alt="Предпросмотр фото" class="max-w-[200px] max-h-[200px] rounded-xl border border-slate-200 shadow-sm" />
                            </div>
                            @error('photo')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50 w-full">
                        <a href="{{ route('admin.specialists.create') }}"
                           class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs tracking-wider font-normal rounded-xl transition-all duration-200 text-center">
                            Отмена
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                            Сохранить данные мастера
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('photo-preview');
                const img = document.getElementById('preview-img');
                img.src = reader.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
