@extends('admin.layouts.admin_menu')

@section('title', 'Список мастеров')

@section('content')

    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class=" px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800">Редактирование мастера: {{ $specialist->user->last_name }} {{ $specialist->user->first_name }}</h1>
            <a href="{{ route('admin.specialists.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                ← Назад к списку
            </a>
        </header>

        <main class="p-8">
            <div class="max-w-xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">

                <form action="{{ route('admin.specialists.update', $specialist->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="level_id" class="block text-xl tracking-wider text-slate-500 mb-2">
                            Уровень мастера
                        </label>

                        <select name="level_id" id="level_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-500/50 focus:ring-4 focus:ring-pink-505/10 transition-all">
                            <option value="">-- Выберите категорию --</option>

                            @foreach($levels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('level_id', $specialist->level_id) == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('level_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="experience" class="block text-xl tracking-wider text-slate-500 mb-2">
                            Опыт работы
                        </label>
                        <input type="text"
                               id="experience"
                               name="experience"
                               value="{{ old('experience', $specialist->experience) }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium"
                        />

                        @error('experience')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5"> {{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="bio" class="block text-xl tracking-wider text-slate-500 mb-2">
                            Описание
                        </label>
                        <textarea id="bio"
                                  name="bio"
                                  rows="4"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium"
                                  placeholder="Опишите услугу для клиентов...">{{ old('bio', $specialist->bio) }}</textarea>

                        @error('bio')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.specialists.index') }}" class="w-full py-3 text-black font-bold rounded-xl transition-all tracking-wide text-xs">
                            Отмена
                        </a>
                        <button type="submit" class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl transition-all shadow-md shadow-pink-100 tracking-wide text-xs">
                            Обновить
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>


@endsection
