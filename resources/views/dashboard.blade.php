<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{--данные пользователя--}}
            <div class="bg-white shadow-sm border border-pink-50 p-6 md:p-8 rounded-3xl mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl text-center text-pink-500 mb-6 font-bold pb-4 border-b border-pink-50">
                    Личные данные
                </h1>

                <div class="space-y-4 text-gray-700 flex flex-col">
                    <div class="grid grid-cols-1 gap-4">
                        <p class="text-lg"><strong class="text-gray-900">Имя:</strong> {{ Auth::user()->first_name }}</p>
                        <p class="text-lg"><strong class="text-gray-900">Фамилия:</strong> {{ Auth::user()->last_name }}</p>
                        <p class="text-lg"><strong class="text-gray-900">Телефон:</strong> {{ Auth::user()->phone }}</p>
                        <p class="text-lg"><strong class="text-gray-900">Элекетронная почта:</strong> {{ Auth::user()->email }}</p>
                    </div>

                    <div class="pt-6 flex m-auto">
                        <a href="{{ route('profile.edit') }}" class="w-full md:w-auto px-8 py-3 bg-pink-500 text-white font-bold rounded-2xl hover:bg-pink-600 transition-colors shadow-lg shadow-pink-200 active:scale-95">
                            Редактировать
                        </a>
                    </div>
                </div>
            </div>
            {{-- список записей--}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-pink-500 mb-4 italic">Ваши ближайшие записи</h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата и время</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Услуга</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">

                                    </tbody>
                                </table>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
