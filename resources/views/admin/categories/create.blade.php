@extends('admin.layouts.admin_menu')

@section('title', 'Категории услуг')

@section('content')

    <div class="w-full font-['Manrope'] text-[#1e1f22]">


        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl  text-[#1e1f22] font-[Playfair_Display]">Новая категория</h1>
                <p class="text-xs text-[#7c7e8c] font-light mt-1">Создание нового раздела для классификации процедур студии</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-xs  text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>


        <main class="w-full overflow-y-auto">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm hover:shadow-sm transition-all duration-300 text-left">

                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                    @csrf


                    <div class="grid grid-cols-1 gap-6">


                        <div>
                            <label for="display_name" class="block text-sm  text-[#7c7e8c] font-medium mb-1.5">
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
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="description" class="block text-sm  text-[#7c7e8c] font-medium mb-1.5">
                                Описание категории
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="5"
                                      class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light resize-none"
                                      placeholder="Опишите категорию услуг для клиентов...">{{ old('description') }}</textarea>

                            @error('description')
                            <p class="text-xs text-rose-500 font-light mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>


                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 text-center text-slate-600 rounded-lg transition-all tracking-wide text-xs bg-slate-100 hover:bg-slate-200 hover:text-slate-800">
                            Отмена
                        </a>

                        <button type="submit" class="px-6 py-2 text-center text-slate-600 rounded-lg transition-all tracking-wide text-xs bg-slate-100 hover:bg-slate-200 hover:text-slate-800">
                            Сохранить категорию
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

@endsection
