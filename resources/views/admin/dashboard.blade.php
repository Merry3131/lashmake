@extends('admin.layouts.admin_menu')

@section('title', 'Категории услуг')

@section('content')

        <div class="p-4 border-t border-slate-800 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-all">
                ← На главную сайта
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 transition-all cursor-pointer">
                    Выйти из системы
                </button>
            </form>
        </div>
    </div>

@endsection
