@extends('admin.layouts.admin_menu')

@section('title', 'Категории услуг')

@section('content')

    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800">Редактирование категории: {{ $category->display_name }}</h1>
            <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                ← Назад к списку
            </a>
        </header>

        <main class="p-8">
            <div class="max-w-xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">

                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT') <div>
                        <label for="display_name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Название категории
                        </label>
                        <input type="text"
                               id="display_name"
                               name="display_name"
                               value="{{ old('display_name', $category->display_name) }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium" />

                        @error('display_name')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Описание категории
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium">{{ old('description', $category->description) }}</textarea>

                        @error('description')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3 flex">
                        <a href="{{ route('admin.categories.index') }}" class="w-full py-3 rounded-xl transition-all text-center shadow-md text-xs">
                            Отмена
                        </a>
                        <button type="submit" class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl transition-all shadow-md shadow-pink-100 uppercase tracking-wide text-xs">
                            Обновить
                        </button>

                    </div>
                </form>

            </div>
        </main>
    </div>


@endsection
