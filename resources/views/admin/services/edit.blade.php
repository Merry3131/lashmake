@extends('admin.layouts.admin_menu')

@section('title', 'Редактирование услуги')

@section('content')
    <div class="w-full font-['Manrope'] text-[#1e1f22]">


        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-normal   text-[#1e1f22] font-[Playfair_Display]">Редактирование услуги</h1>
                <p class="text-sm text-[#7c7e8c] font-light mt-1">Изменение информации для: <span class="font-normal text-[#ff5c8a]">{{ $service->name }}</span></p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="text-xs   text-[#7c7e8c] hover:text-[#ff5c8a] transition-colors duration-200 font-medium">
                ← Назад к списку
            </a>
        </div>


        <main class="w-full">
            <div class="w-full bg-white p-6 md:p-8 rounded-3xl border border-[#f1f1f5] shadow-sm text-left">

                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-6 w-full">


                        <div class="w-full">
                            <label for="name" class="block text-sm   text-[#7c7e8c] font-medium mb-1.5">
                                Название услуги
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $service->name) }}"
                                   required
                                   class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light" />
                            @error('name')
                            <p class="text-xs text-rose-500 font-light mt-1"> {{ $message }}</p>
                            @enderror
                        </div>


                        <div class="w-full">
                            <label for="category_id" class="block text-sm   text-[#7c7e8c] font-medium mb-1.5">
                                Категория процедуры
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    required
                                    class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:ring-[#ff5c8a]/20 text-sm p-3 transition-colors duration-200 outline-none font-light appearance-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="text-xs text-rose-500 font-light mt-1"> {{ $message }}</p>
                            @enderror
                        </div>


                        <div class="w-full">
                            <span class="block text-sm   text-[#7c7e8c] font-medium mb-2.5">
                                Доступность для онлайн-записи
                            </span>
                            <div class="flex flex-col sm:flex-row gap-4 sm:items-center h-auto sm:h-11">
                                <label class="inline-flex items-center cursor-pointer select-none">
                                    <input type="radio"
                                           name="active"
                                           value="1"
                                           {{ old('active', $service->active) == '1' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#ff5c8a] border-gray-300 focus:ring-[#ff5c8a]/20" />
                                    <span class="ml-2 text-sm text-[#1e1f22] font-light flex items-center">

                                        Активна и видна
                                    </span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer select-none">
                                    <input type="radio"
                                           name="active"
                                           value="0"
                                           {{ old('active', $service->active) == '0' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#ff5c8a] border-gray-300 focus:ring-[#ff5c8a]/20" />
                                    <span class="ml-2 text-sm text-[#1e1f22] font-light flex items-center">

                                        Скрыта / Недоступна
                                    </span>
                                </label>
                            </div>
                            @error('active')
                            <p class="text-xs text-rose-500 font-light mt-1"> {{ $message }}</p>
                            @enderror
                        </div>


                        <div class="w-full">
                            <label for="description" class="block text-sm   text-[#7c7e8c] font-medium mb-1.5">
                                Подробное описание услуги для клиентов
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      class="block w-full rounded-xl border border-[#f1f1f5] bg-[#f8f8fa] focus:border-[#ff5c8a] focus:bg-white focus:ring-[#ff5c8a]/20 text-sm p-3 transition-all duration-200 outline-none font-light resize-y">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                            <p class="text-xs text-rose-500 font-light mt-1"> {{ $message }}</p>
                            @enderror
                        </div>

                    </div>


                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50 w-full">
                        <a href="{{ route('admin.services.index') }}"
                           class="w-full sm:w-auto sm:px-8 py-3.5 border border-gray-200 text-gray-500 hover:text-[#1e1f22] hover:bg-gray-50 text-xs   font-normal rounded-xl transition-all duration-200 text-center">
                            Отмена
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto sm:px-8 py-3.5 bg-[#ff5c8a] hover:bg-[#e04b75] text-white text-xs   font-normal rounded-xl transition-all duration-300 shadow-sm cursor-pointer">
                            Обновить
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
@endsection
