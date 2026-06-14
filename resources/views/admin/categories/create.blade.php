@extends('admin.layouts.admin_menu')

@section('title', 'Категории услуг')

@section('content')

    <div class="w-full font-['Manrope'] text-[#1e1f22]">

        {{-- ВЕРХНЯЯ ЧАСТЬ: ЗАГОЛОВОК И ССЫЛКА НАЗАД --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal tracking-wider uppercase text-[#1e1f22] font-[Playfair_Display]">Новая категория</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Создание нового раздела для классификации процедур студии</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-xs uppercase tracking-wider text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>

        {{-- ПОЛНОШИРИННАЯ ФОРМА В СТИЛЕ КАРТОЧКИ --}}
        <main class="w-full overflow-y-auto">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm hover:shadow-sm transition-all duration-300 text-left">

                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Сетка для полей (если в будущем добавятся еще поля, они встанут рядом) --}}
                    <div class="grid grid-cols-1 gap-6">

                        {{-- Поле: Название категории --}}
                        <div>
                            <label for="display_name" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Название категории (отображаемое на сайте)
                            </label>
                            <input type="text"
                                   id="display_name"
                                   name="display_name"
                                   value="{{ old('display_name') }}"
                                   required
                                   class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light"
                                   placeholder="Например: Наращивание ресниц" />

                            @error('display_name')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Поле: Описание категории --}}
                        <div>
                            <label for="description" class="block text-[10px] uppercase tracking-wider text-[#7c7e8c] font-medium mb-1.5">
                                Описание категории
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="5"
                                      class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light resize-none"
                                      placeholder="Опишите категорию услуг для клиентов...">{{ old('description') }}</textarea>

                            @error('description')
                            <p class="text-xs text-rose-500 font-light mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Блок кнопок действий, прижатый к правому краю для баланса на больших экранах --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-200 text-center">
                            Отмена
                        </a>

                        <button type="submit" class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs tracking-wider uppercase font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                            Сохранить категорию
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

@endsection
