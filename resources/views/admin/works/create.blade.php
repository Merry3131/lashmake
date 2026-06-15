@extends('admin.layouts.admin_menu')

@section('title', 'Добавление примера работы')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal  text-[#1e1f22]">Портфолио</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Добавление фотографии выполненной процедуры в галерею студии</p>
            </div>
            <a href="{{ route('admin.works.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm  font-normal rounded-xl transition-all duration-300 shadow-sm hover:cursor-pointer whitespace-nowrap">
                ← Назад к списку
            </a>
        </div>

        <div class="w-full bg-white rounded-3xl border border-[#f1f1f5] shadow-sm p-8">
            <form action="{{ route('admin.works.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="specialist_id" class="block text-sm  text-[#7c7e8c]">Мастер, выполнивший работу</label>
                        <select name="specialist_id" id="specialist_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('specialist_id') border-red-400 @enderror">
                            <option value="" disabled selected>Выберите специалиста из списка...</option>
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}" {{ old('specialist_id') == $specialist->id ? 'selected' : '' }}>
                                    {{ $specialist->user->last_name }} {{ $specialist->user->name }} ({{ $specialist->level_id ?? 'Мастер' }})
                                </option>
                            @endforeach
                        </select>
                        @error('specialist_id')
                        <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="service_id" class="block text-sm  text-[#7c7e8c]">Выполненная услуга</label>
                        <select name="service_id" id="service_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all @error('service_id') border-red-400 @enderror">
                            <option value="" disabled selected>Выберите процедуру...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                        <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="image" class="block text-sm  text-[#7c7e8c]">Фотография результата</label>
                    <input type="file" name="image" id="image" required accept="image/*"
                           class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-normal file:bg-pink-100 file:text-pink-600 hover:file:bg-pink-200 file:cursor-pointer focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all @error('image') border-red-400 @enderror">
                    <p class="text-[11px] text-[#7c7e8c] font-light">Поддерживаются форматы JPG, PNG, WEBP. Максимальный размер файла: 5 МБ.</p>
                    @error('image')
                    <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm  text-[#7c7e8c]">Комментарии / Описание работы</label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="Например: Использовали изгиб C, толщина 0.07, эффект лисий. Время работы — 2 часа..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all resize-y">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.works.index') }}"
                       class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-sm font-normal rounded-xl transition-all duration-200 text-center">
                        Отмена
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-sm font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                        Сохранить работу
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
