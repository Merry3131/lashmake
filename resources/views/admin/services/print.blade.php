<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Прайс-лист услуг</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @media print {
            body {
                padding: 20px !important;
            }
            .no-print {
                display: none !important;
            }
            .print\\:shadow-none {
                box-shadow: none !important;
            }
            .break-inside-avoid {
                break-inside: avoid;
            }
        }
        body {
            font-family: 'Manrope', sans-serif;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-gray-50 p-8 print:p-5">

<div class="max-w-6xl mx-auto">
    <button onclick="window.print();" class="no-print block mx-auto mb-8 px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white text-sm font-medium rounded-xl transition-all duration-300 shadow-md hover:shadow-lg cursor-pointer">
        🖨️ Распечатать / Сохранить PDF
    </button>

    <div class="bg-white rounded-3xl shadow-lg overflow-hidden print:shadow-none">
        <div class="text-center border-b border-gray-100 py-8 px-6">
            <h1 class="text-3xl font-serif font-normal text-gray-800 tracking-wide">Прайс-лист услуг</h1>
            <p class="text-sm text-gray-400 font-light mt-2">Студия красоты</p>
            <div class="w-16 h-0.5 bg-pink-400 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="px-6 pt-4 pb-2">
            <p class="text-right text-xs text-gray-400 font-light">
                Дата формирования: {{ now()->format('d.m.Y') }}
            </p>
        </div>

        <div class="overflow-x-auto p-6">
            <table class="w-full text-left">
                <thead>
                <tr class="border-b-2 border-gray-100 bg-gray-50/50">
                    <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-1/3">Услуга</th>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-1/4">Категория</th>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-2/5">Описание</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach($services as $service)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                            {{ $service->name }}
                        </td>
                        <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 text-xs font-light text-pink-600 bg-pink-50 rounded-lg">
                                        {{ $service->category->display_name }}
                                    </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-light">
                            {{ $service->description ?? '—' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center border-t border-gray-100 py-4 px-6">
            <p class="text-[10px] text-gray-400 font-light">
                © {{ date('Y') }} Студия красоты. Все права защищены.
            </p>
        </div>
    </div>
</div>

</body>
</html>
