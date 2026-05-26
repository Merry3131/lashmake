@extends('admin.layouts.admin_menu')

@section('title', 'Список услуг')

@section('content')



    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class=" px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800">Редактирование услуги: {{ $service->name }}</h1>
            <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                ← Назад к списку
            </a>
        </header>

        <main class="p-8">
            <div class="max-w-xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">

                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="display_name" class="block text-xl tracking-wider text-slate-500 mb-2">
                            Название услуги
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $service->name) }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium"
                        />

                        @error('name')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5"> {{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="description" class="block text-xl tracking-wider text-slate-500 mb-2">
                            Описание услуги
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-400 focus:bg-white focus:ring-4 focus:ring-pink-100 transition-all font-medium"
                                  placeholder="Опишите услугу для клиентов...">{{ old('description', $service->description) }}</textarea>

                        @error('description')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-xl tracking-wider text-slate-500 mb-2">
                            Категория услуги
                        </label>

                        <select name="category_id" id="category_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:border-pink-500/50 focus:ring-4 focus:ring-pink-505/10 transition-all">
                            <option value="">-- Выберите категорию --</option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->display_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Статус услуги (Активность на сайте)
                        </label>

                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer select-none">
                                <input type="radio"
                                       name="active"
                                       value="1"
                                       {{ old('active', $service->active) == '1' ? 'checked' : '' }}
                                       class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500 focus:ring-2">
                                <span class="ml-2 text-sm text-gray-900 font-medium flex items-center">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>
                                    Активна
                                </span>
                            </label>

                            <label class="flex items-center cursor-pointer select-none">
                                <input type="radio"
                                       name="active"
                                       value="0"
                                       {{ old('active', $service->active) == '0' ? 'checked' : '' }}
                                       class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500 focus:ring-2">
                                <span class="ml-2 text-sm text-gray-900 font-medium flex items-center">
                                <span class="w-2 h-2 rounded-full bg-red-400 mr-1.5"></span>
                                    Недоступна
                                </span>
                            </label>
                        </div>

                        @error('active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.services.index') }}" class="w-full py-3 text-black font-bold rounded-xl transition-all tracking-wide text-xs">
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
