@extends('admin.layouts.admin_menu')

@section('title', 'Заполнение данных мастера')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.specialists.create') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">← Вернуться к выбору пользователя</a>
        <h1 class="text-xl font-bold text-slate-800 mt-2">Шаг 2: Данные специалиста</h1>
        <p class="text-sm text-slate-400">Вы назначаете мастером: <strong class="text-slate-900">{{ $user->last_name }} {{ $user->name }}</strong> ({{ $user->email }})</p>
    </div>

    <div class="max-w-xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('admin.specialists.store') }}" method="POST" class="space-y-5">
            @csrf

            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div>
                <label for="level_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Уровень квалификации
                </label>
                <select id="level_id" name="level_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">
                    <option value="">-- Выберите уровень квалификации --</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                            {{ match($level->name) { 'master' => 'Мастер', 'top' => 'Топ-Мастер', 'lead' => 'Ведущий мастер', default => $level->name } }}
                        </option>
                    @endforeach
                </select>
                @error('level_id')
                <p class="text-xs text-rose-500 font-semibold mt-1.5">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="experience" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Опыт работы</label>
                <input type="text" id="experience" name="experience" value="{{ old('experience') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all"
                       placeholder="Например: 3 года или 8 лет">
                @error('experience')
                <p class="text-xs text-rose-500 font-semibold mt-1.5">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">О себе / Биография мастера</label>
                <textarea id="bio" name="bio" rows="4"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all"
                          placeholder="Расскажите об особенностях работы мастера, сильных сторонах...">{{ old('bio') }}</textarea>
                @error('bio')
                <p class="text-xs text-rose-500 font-semibold mt-1.5">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.specialists.create') }}" class="w-full py-3.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-center text-xs uppercase tracking-wide flex items-center justify-center">Отмена</a>
                <button type="submit" class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl text-center text-xs uppercase tracking-wide shadow-md shadow-pink-100">Создать мастера</button>
            </div>
        </form>
    </div>
@endsection
