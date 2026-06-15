<x-app-layout>
    <div class="w-full bg-cover bg-center pt-16 pb-16 relative" style="background-image: url('{{ asset('img/bg_main.png') }}');">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl lg:text-4xl text-[#1e1f22] tracking-widest mb-4 font-[Playfair_Display]">
                Редактирование профиля
            </h2>
            <div class="w-24 h-0.5 bg-[#ff5c8a] mx-auto mb-4 rounded-full"></div>
            <p class="text-sm text-[#7c7e8c] font-light tracking-wide font-[Manrope]">
                Управление вашими личными данными и настройками
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pt-8 pb-24 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6">
            <div class="bg-white rounded-3xl border border-[#f1f1f5] p-8 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-3xl border border-[#f1f1f5] p-8 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-3xl border border-[#f1f1f5] p-8 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
