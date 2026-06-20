@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование мастера')

@section('content')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="w-full font-['Manrope'] text-[#1e1f22]" x-data="{
        photoPreview: null,
        previewPhoto(event) {
            const file = event.target.files[0];
            if (!file) {
                this.photoPreview = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider text-[#1e1f22] font-[Playfair_Display]">Редактирование мастера</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">
                    Изменение профиля специалиста: <span class="font-normal text-[#ff5c8a]">{{ $specialist->user->last_name }} {{ $specialist->user->first_name }}</span>
                </p>
            </div>
            <a href="{{ route('admin.specialists.index') }}" class="text-xs tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        <main class="w-full">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm text-left">

                <form action="{{ route('admin.specialists.update', $specialist->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-6 w-full">

                        <!-- Уровень мастера -->
                        <div class="w-full">
                            <label for="level_id" class="block text-sm tracking-wider text-[#7c7e8c] font-medium mb-1.5">
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
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Опыт работы -->
                        <div class="w-full">
                            <label for="experience" class="block text-sm tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Опыт работы
                            </label>
                            <input type="text"
                                   id="experience"
                                   name="experience"
                                   value="{{ old('experience', $specialist->experience) }}"
                                   required
                                   class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light" />
                            @error('experience')
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Описание (биография) -->
                        <div class="w-full">
                            <label for="bio" class="block text-sm tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Описание
                            </label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="4"
                                      placeholder="Опишите услугу для клиентов..."
                                      class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:bg-white focus:ring-[#ff5c8a]/20 text-sm p-3 transition-all duration-200 outline-none font-light resize-y">{{ old('bio', $specialist->bio) }}</textarea>
                            @error('bio')
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Услуги мастера -->
                        <div class="w-full">
                            <label class="block text-sm tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Услуги мастера
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-[#f8f8fa] rounded-xl border border-[#f1f1f5]">
                                @foreach($services as $service)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#ff5c8a] transition-colors">
                                        <input type="checkbox"
                                               name="services[]"
                                               value="{{ $service->id }}"
                                               {{ in_array($service->id, $assignedServices ?? []) ? 'checked' : '' }}
                                               class="w-4 h-4 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
                                        <span class="text-sm text-gray-700 font-light">{{ $service->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('services')
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Текущее фото и поле для загрузки нового -->
                        <div class="w-full">
                            <label class="block text-sm tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Фото мастера
                            </label>

                            <!-- Текущее фото (показываем, если нет превью нового) -->
                            <template x-if="!photoPreview">
                                <div class="mb-3">
                                    @if($specialist->getFirstMediaUrl('avatar'))
                                        <p class="text-xs text-[#7c7e8c] mb-1">Текущее фото:</p>
                                        <img src="{{ $specialist->getFirstMediaUrl('avatar', 'preview') }}"
                                             alt="Фото мастера"
                                             class="max-w-[150px] max-h-[150px] rounded-xl border border-slate-200 shadow-sm" />
                                    @else
                                        <p class="text-xs text-[#7c7e8c] mb-2">Фото не загружено</p>
                                    @endif
                                </div>
                            </template>

                            <!-- Превью нового фото -->
                            <template x-if="photoPreview">
                                <div class="mb-3">
                                    <p class="text-xs text-[#7c7e8c] mb-1">Новое фото:</p>
                                    <img x-bind:src="photoPreview" alt="Предпросмотр нового фото" class="max-w-[150px] max-h-[150px] rounded-xl border border-slate-200 shadow-sm" />
                                </div>
                            </template>

                            <input type="file"
                                   id="photo"
                                   name="photo"
                                   accept="image/*"
                                   x-on:change="previewPhoto($event)"
                                   class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all" />
                            <p class="text-xs text-[#7c7e8c] mt-1">Загрузите новое фото, чтобы заменить текущее.</p>

                            @error('photo')
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50 w-full">
                        <a href="{{ route('admin.specialists.index') }}"
                           class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs font-normal rounded-xl transition-all duration-200 text-center">
                            Отмена
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                            Обновить
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
@endsection
