<x-app-layout title="Центр Ресниц | Специалисты">
    <section class="max-w-7xl mx-auto pt-32 pb-20 px-6">
        {{-- мастера --}}
        <div class="text-center mb-20">
            <h2 class="text-4xl lg:text-5xl font-serif text-gray-900 uppercase tracking-widest mb-4">Наши мастера</h2>
            <div class="w-24 h-1 bg-pink-400 mx-auto rounded-full"></div>
            <p class="text-gray-500 italic mt-6 text-lg">Профессионалы, которым доверяют свой взгляд</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($team as $member)
                <div class="group relative bg-white rounded-[2rem] p-8 shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col items-center">
                    <div class="relative w-48 h-48 mb-8">
                        <div class="absolute inset-0 bg-pink-200 rounded-full rotate-6 group-hover:rotate-12 transition-transform duration-500"></div>
                        <div class="relative w-full h-full overflow-hidden rounded-full border-4 border-white shadow-md">
                            <img src="{{ $member->user->avatar ?? '/img/team/default.jpg' }}"
                                 alt="{{ $member->user->first_name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>

                        <div class="absolute -bottom-2 -right-2 bg-pink-500 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                            {{ $member->experience_years }}+ года опыта
                        </div>
                    </div>


                    <div class="text-center flex-grow">
                    <span class="text-xs font-bold uppercase tracking-widest text-pink-400 mb-2 block">
                        {{ $member->specialization ?? 'Lash-master' }}
                    </span>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            {{ $member->user->first_name }} {{ $member->user->last_name }}
                        </h3>
                        <p class="text-gray-600 font-light leading-relaxed mb-6 text-sm">
                            {{ $member->bio ?? 'Сертифицированный специалист по созданию идеального взгляда и уходу за ресницами.' }}
                        </p>
                    </div>


                    <div class="w-full pt-6 border-t border-gray-50">
                        <a href="#" class="block w-full py-3 rounded-full bg-gray-900 text-white font-medium hover:bg-pink-500 transition-colors duration-300">
                            Записаться к мастеру
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-24 text-center">
            <p class="text-gray-400 text-sm">Все наши специалисты проходят регулярное повышение квалификации</p>
        </div>
    </section>
</x-app-layout>
