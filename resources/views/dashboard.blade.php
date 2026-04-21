<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Личный кабинет') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <span class="text-2xl">🌸</span> Рады вас видеть, <strong>{{ Auth::user()->first_name }}</strong>!
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-pink-500 mb-4 italic">Ваши ближайшие записи</h3>

                        @if(Auth::user()->appointments->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 mb-4">У вас пока нет активных записей.</p>
                                <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 bg-pink-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-600 transition">
                                    Выбрать услугу
                                </a>
                            </div>
                        @else
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
                                    @foreach(Auth::user()->appointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $appointment->appointment_time->format('d.m.Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $appointment->service->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Подтверждено
                                                    </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gradient-to-br from-pink-400 to-pink-500 overflow-hidden shadow-sm sm:rounded-lg text-white">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold mb-2">Ваша скидка</h3>
                        <div class="text-4xl font-black mb-4">10%</div>
                        <p class="text-sm opacity-90">как постоянному клиенту Lashmake</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
