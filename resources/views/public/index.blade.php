<x-app-layout title="Центр Ресниц | Главная">
    {{--        начальная страница--}}
    <div class="bg-[#f8f5f2]">
        <section class="max-w-7xl mx-auto py-20 px-6 relative flex flex-col md:flex-row items-center justify-between overflow-hidden pb-20">

            <div class="absolute top-10 right-0 w-72 h-48 bg-[#D4B4A1] opacity-50 z-0"></div>

            <div class="absolute right-1/4 top-1/3 w-32 h-32 bg-[#D4B412] opacity-20 z-10 hidden lg:block"></div>

            <div class="relative z-20 w-full md:w-1/2 mb-10 md:mb-0">
                <h2 class="text-6xl lg:text-8xl font-serif leading-tight text-gray-900">
                    Центр<br><span class="text-[#D4B4A1]">Ресниц</span>
                </h2>
                <p class="mt-6 text-gray-600 max-w-sm text-lg">
                    Салон красоты по наращиванию ресниц, оформлению и ламинированию бровей.
                </p>

                <button class="mt-8 border-2 border-black px-10 py-4 uppercase text-xs font-bold tracking-[0.2em] hover:bg-black hover:text-white transition-all">
                    О нас
                </button>
            </div>

            <div class="relative z-20 w-full md:w-5/12">
                <div class="absolute -bottom-6 -left-6 w-full h-full border-[16px] border-[#D4B4A1] z-0"></div>

                <div class="relative z-10 aspect-[3/4] overflow-hidden shadow-2xl">
                    <img src="girl.jpg" class="w-full h-full object-cover" alt="Lash Center Model">
                </div>
            </div>
        </section>
    </div>

{{--    блок о нас--}}
    <div class="bg-[#CCC5B9]/30">
        <section class="max-w-7xl mx-auto py-20 px-6 relative">
            <div class="text-center mb-16">
                <div class="mb-5">
                    {{-- Родительский контейнер с Flexbox, выравниванием по центру и отступами --}}
                    <h2 class="text-4xl lg:text-5xl font-serif text-center relative flex items-center justify-center gap-6 text-gray-900 mx-auto max-w-lg">
                        <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
                        <span class="relative z-10 px-2">О нас</span>
                        <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
                    </h2>

                </div>
                <p class="text-gray-500 uppercase tracking-widest text-sm">Ваша индивидуальность — наше вдохновение</p>
            </div>

            <div class="bg-white rounded-3xl overflow-hidden shadow-xl flex flex-col md:flex-row items-stretch">
                <div class="md:w-1/2 bg-gray-200 flex items-center justify-center min-h-[300px]">
                    <span class="text-gray-400 font-bold uppercase tracking-widest">Место для фото студии</span>
                    {{-- <img src="studio.jpg" class="w-full h-full object-cover"> --}}
                </div>
                <div class="md:w-1/2 p-8 lg:p-16 flex flex-col justify-center">
                    <p class="text-xl lg:text-2xl font-light leading-relaxed italic text-gray-700">
                        «Мы создаем не просто объем, а гармоничный образ, подчеркивающий вашу индивидуальность».
                    </p>
                    <p class="mt-6 text-gray-600 leading-relaxed">
                        В нашем арсенале — безграничный выбор длин, изгибов и эксклюзивных оттенков, которые позволяют воплотить в жизнь любую идею: от утонченной классики до самых смелых трендовых эффектов.
                    </p>
                </div>
            </div>

            <div class="mt-24">
                <h3 class="text-3xl font-serif text-center mb-12">Почему выбирают нас?</h3>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Карточки --}}
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border-b-4 border-transparent hover:border-pink-400 group">
                        <div class="text-pink-400 mb-4">
                            <img src="" alt="вставить картинку">
                        </div>
                        <h4 class="text-lg font-bold mb-3">Экспертность и преемственность. </h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Наши мастера — опытные специалисты, прошедшие личное обучение у основательницы студии и ведущего эксперта Алёны Хабибуллиной. Мы гарантируем безупречную технику и безопасность каждой процедуры.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border-b-4 border-transparent hover:border-pink-400 group">
                        <div class="text-pink-400 mb-4">
                            <img src="" alt="вставить картинку">
                        </div>
                        <h4 class="text-lg font-bold mb-3">Абсолютный комфорт</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Забудьте об усталости: наши анатомические кушетки созданы для того, чтобы время пролетело незаметно, а вы чувствовали себя максимально расслабленно.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border-b-4 border-transparent hover:border-pink-400 group">
                        <div class="text-pink-400 mb-4">
                            <img src="" alt="вставить картинку">
                        </div>
                        <h4 class="text-lg font-bold mb-3">Забота в подарок</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Мы ценим ваш отдых. В качестве комплимента каждому гостю мы предлагаем сеанс массажа всего тела в нашем профессиональном кресле — абсолютно бесплатно до или после процедуры.</p>
                    </div>

                </div>
            </div>
        </section>

        <div class="bg-[#f8f5f2] py-24">
            <section class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <div class="mb-5">
                        <h2 class="text-4xl lg:text-5xl font-serif text-center relative flex items-center justify-center gap-6 text-gray-900 mx-auto max-w-lg">

                            <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>

                            <span class="relative z-10 px-2">Наши услуги</span>

                            <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>

                        </h2>
                    </div>
                    <p class="text-gray-500 uppercase tracking-widest text-sm">Искусство преображения взгляда</p>
                </div>

                <div class="space-y-24">
                    <div class="flex flex-col md:flex-row items-center gap-12">
                        <div class="md:w-1/2 aspect-video bg-gray-100 rounded-2xl overflow-hidden shadow-lg">
                            <img src="lashes.jpg" class="w-full h-full object-cover">
                        </div>
                        <div class="md:w-1/2 text-center md:text-left">
                            <h4 class="text-2xl lg:text-3xl font-bold text-pink-500 mb-6">Наращивание и ламинирование ресниц</h4>
                            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                                Создаем идеальный объем: от нежной классики до роскошного «Голливуда». Наши мастера работают в тонких техниках, сохраняя здоровье ваших натуральных ресниц.
                            </p>
                            <a href="{{ url('/example_of_works') }}" class="inline-block border-2 border-pink-400 text-pink-500 px-8 py-3 rounded-full font-bold hover:bg-pink-400 hover:text-white transition-all transform hover:scale-105">
                                Примеры работ
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row-reverse items-center gap-12">
                        <div class="md:w-1/2 aspect-video bg-gray-100 rounded-2xl overflow-hidden shadow-lg">
                            <img src="brows.jpg" class="w-full h-full object-cover">
                        </div>
                        <div class="md:w-1/2 text-center md:text-left">
                            <h4 class="text-2xl lg:text-3xl font-bold text-pink-500 mb-6">Оформление бровей</h4>
                            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                                Создаем идеальную форму: от мягкой натуральности до четкой графики. Просыпайтесь сразу ухоженной — с безупречным цветом и глубоким взглядом.
                            </p>
                            <a href="/services" class="inline-block border-2 border-pink-400 text-pink-500 px-8 py-3 rounded-full font-bold hover:bg-pink-400 hover:text-white transition-all transform hover:scale-105">
                                Примеры работ
                            </a>
                        </div>
                    </div>
                </div>
            </section>


        </div>
        <section class="max-w-7xl mx-auto pt-30 pb-12 px-6">
            <div class="mb-16">
                <h2 class="text-4xl lg:text-5xl font-serif text-center relative flex items-center justify-center gap-6 text-gray-900 mx-auto max-w-2xl">
                    <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
                    <span class="relative z-10 px-2 uppercase tracking-widest">Наши услуги</span>
                    <span class="flex-grow h-0.5 bg-pink-400 rounded-full" aria-hidden="true"></span>
                </h2>
                <p class="text-center text-gray-500 italic mt-4">Искусство преображения взгляда</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                @foreach($categories as $category)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col h-full">
                        <div class="aspect-[4/3] bg-gray-200 relative overflow-hidden flex-shrink-0">
                            <div class="absolute inset-0 bg-pink-900/10 group-hover:bg-transparent transition-colors duration-500"></div>
                            <img src="/img/categories/{{ $category->slug }}.jpg" alt="{{ $category->display_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>

                        <div class="p-8 text-center flex flex-col flex-grow">
                            <h3 class="text-2xl font-bold mb-4 group-hover:text-pink-500 transition-colors">{{ $category->display_name }}</h3>
                            <p class="text-gray-600 font-light mb-8 leading-relaxed flex-grow">
                                {{ $category->description }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ url('/services') }}" class="block border border-pink-500 rounded-full py-2 px-6 w-full mt-6 font-medium hover:bg-pink-400 hover:text-white transition-all duration-300">
                                    Смотреть услуги
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button x-data
                    @click="$store.modalManager.openBooking()"
                    class="block border border-pink-500 rounded-full py-2 px-6 w-full mt-6 font-medium hover:bg-pink-400 hover:text-white transition-all duration-300">
                Записаться онлайн
            </button>

        </section>
    </div>

    {{--            модальное окно записи--}}
    <x-booking-modal />
</x-app-layout>



